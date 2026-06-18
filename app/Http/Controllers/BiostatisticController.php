<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Coding;
use App\Models\HybridTracker;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BiostatisticController extends Controller
{
    public function index()
    {
        $totalMorbidity = Registration::distinct('patient_id')->count('patient_id');

        $avgAge = Patient::whereHas('registrations.medicalRecord.codings')
            ->get()
            ->map(function ($p) {
                return Carbon::parse($p->tanggal_lahir)->age;
            });
        $averageAge = $avgAge->count() > 0 ? round($avgAge->avg(), 1) : 0;

        $totalTrackers = HybridTracker::count();
        $completedTrackers = HybridTracker::where('is_lengkap', true)->count();
        $klpcm = $totalTrackers > 0 ? round(($completedTrackers / $totalTrackers) * 100, 1) : 0;

        $topDiagnoses = Coding::select('icd10_mapped_code', DB::raw('COUNT(*) as total'))
            ->whereNotNull('icd10_mapped_code')
            ->where('icd10_mapped_code', '!=', 'N/A')
            ->groupBy('icd10_mapped_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $chartLabels = $topDiagnoses->pluck('icd10_mapped_code')->toArray();
        $chartData = $topDiagnoses->pluck('total')->toArray();

        $chartColors = [
            '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6',
            '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1',
        ];

        return view('biostatistic.index', compact(
            'totalMorbidity',
            'averageAge',
            'klpcm',
            'chartLabels',
            'chartData',
            'chartColors',
            'topDiagnoses'
        ));
    }
}
