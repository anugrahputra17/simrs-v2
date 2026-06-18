<?php

namespace App\Http\Controllers;

use App\Models\Coding;
use App\Models\MedicalRecord;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerminologyProxyController extends Controller
{
    private string $snowstormBase = 'https://snowstorm.ihtsdotools.org/snowstorm/snomed-ct';
    private string $branch = 'MAIN/2024-03-01';

    public function index($medicalRecordId)
    {
        $medicalRecord = MedicalRecord::with(['registration.patient', 'codings'])->findOrFail($medicalRecordId);
        return view('coding.index', compact('medicalRecord'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term', '');
        if (strlen($term) < 2) {
            return response()->json(['items' => []]);
        }

        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->get("{$this->snowstormBase}/browser/{$this->branch}/descriptions", [
                    'term' => $term,
                    'active' => true,
                    'semanticTags' => ['disorder'],
                    'limit' => 15,
                    'language' => 'en',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $items = collect($data['items'] ?? [])->map(function ($item) {
                    return [
                        'conceptId' => $item['concept']['conceptId'] ?? '',
                        'term' => $item['term'] ?? '',
                        'fsn' => $item['concept']['fsn']['term'] ?? '',
                        'active' => $item['active'] ?? true,
                    ];
                });
                return response()->json(['items' => $items]);
            }

            return response()->json(['items' => [], 'error' => 'Snowstorm API returned: ' . $response->status()]);
        } catch (\Exception $e) {
            return response()->json(['items' => [], 'error' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    public function mapIcd10($conceptId)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->get("{$this->snowstormBase}/{$this->branch}/members", [
                    'referencedComponentId' => $conceptId,
                    'referenceSetId' => '447562003',
                    'active' => true,
                    'limit' => 5,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $members = $data['items'] ?? [];

                $mappings = collect($members)->map(function ($member) {
                    $additionalFields = $member['additionalFields'] ?? [];
                    return [
                        'mapTarget' => $additionalFields['mapTarget'] ?? 'N/A',
                        'mapGroup' => $additionalFields['mapGroup'] ?? 1,
                        'mapPriority' => $additionalFields['mapPriority'] ?? 1,
                        'mapRule' => $additionalFields['mapRule'] ?? '',
                    ];
                })->filter(fn($m) => $m['mapTarget'] !== 'N/A' && $m['mapTarget'] !== '');

                $primary = $mappings->sortBy('mapPriority')->first();

                return response()->json([
                    'success' => true,
                    'icd10Code' => $primary['mapTarget'] ?? 'N/A',
                    'allMappings' => $mappings->values(),
                ]);
            }

            return response()->json(['success' => false, 'icd10Code' => 'N/A']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'icd10Code' => 'N/A', 'error' => $e->getMessage()]);
        }
    }

    public function storeCoding(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'snomed_concept_id' => 'required|string',
            'snomed_term' => 'required|string',
            'icd10_mapped_code' => 'required|string',
            'is_primary_diagnosis' => 'required|boolean',
        ]);

        $miscoding = $this->evaluateMiscoding(
            $request->medical_record_id,
            $request->snomed_term,
            $request->is_primary_diagnosis
        );

        $coding = Coding::create([
            'medical_record_id' => $request->medical_record_id,
            'snomed_concept_id' => $request->snomed_concept_id,
            'snomed_term' => $request->snomed_term,
            'icd10_mapped_code' => $request->icd10_mapped_code,
            'is_primary_diagnosis' => $request->is_primary_diagnosis,
            'miscoding_status' => $miscoding,
        ]);

        AuditTrail::log('CREATE', 'codings');

        return response()->json([
            'success' => true,
            'coding' => $coding,
            'miscoding_status' => $miscoding,
        ]);
    }

    public function deleteCoding($id)
    {
        $coding = Coding::findOrFail($id);
        $coding->delete();
        AuditTrail::log('DELETE', 'codings');

        return response()->json(['success' => true]);
    }

    private function evaluateMiscoding(int $medicalRecordId, string $newTerm, bool $isPrimary): ?string
    {
        $chronicKeywords = [
            'chronic', 'kronis', 'gerd', 'gastroesophageal reflux',
            'chronic gastritis', 'peptic ulcer', 'irritable bowel',
            'functional dyspepsia', 'hiatal hernia',
        ];

        $acuteKeywords = [
            'acute', 'akut', 'appendicitis', 'acute gastritis',
            'acute pancreatitis', 'perforasi', 'perforation',
            'hemorrhage', 'obstruction', 'peritonitis',
            'acute cholecystitis', 'volvulus',
        ];

        $termLower = strtolower($newTerm);
        $isNewChronic = false;
        $isNewAcute = false;

        foreach ($chronicKeywords as $keyword) {
            if (str_contains($termLower, $keyword)) {
                $isNewChronic = true;
                break;
            }
        }

        foreach ($acuteKeywords as $keyword) {
            if (str_contains($termLower, $keyword)) {
                $isNewAcute = true;
                break;
            }
        }

        if ($isPrimary && $isNewChronic) {
            $existingCodings = Coding::where('medical_record_id', $medicalRecordId)
                ->where('is_primary_diagnosis', false)
                ->get();

            foreach ($existingCodings as $existing) {
                $existingLower = strtolower($existing->snomed_term);
                foreach ($acuteKeywords as $keyword) {
                    if (str_contains($existingLower, $keyword)) {
                        return 'chronic_over_acute';
                    }
                }
            }
        }

        if (!$isPrimary && $isNewAcute) {
            $primaryCoding = Coding::where('medical_record_id', $medicalRecordId)
                ->where('is_primary_diagnosis', true)
                ->first();

            if ($primaryCoding) {
                $primaryLower = strtolower($primaryCoding->snomed_term);
                foreach ($chronicKeywords as $keyword) {
                    if (str_contains($primaryLower, $keyword)) {
                        $primaryCoding->update(['miscoding_status' => 'chronic_over_acute']);
                        return null;
                    }
                }
            }
        }

        return null;
    }
}
