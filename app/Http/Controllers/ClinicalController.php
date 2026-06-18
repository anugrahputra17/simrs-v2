<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\MedicalRecord;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class ClinicalController extends Controller
{
    public function index()
    {
        $queue = Registration::with('patient')
            ->whereIn('status_antrean', ['waiting', 'treating'])
            ->orderByRaw("FIELD(status_antrean, 'treating', 'waiting')")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('clinical.index', compact('queue'));
    }

    public function getPatientData($registrationId)
    {
        $registration = Registration::with('patient', 'medicalRecord')->findOrFail($registrationId);

        if ($registration->status_antrean === 'waiting') {
            $registration->update(['status_antrean' => 'treating']);
            AuditTrail::log('UPDATE', 'registrations');
        }

        $patient = $registration->patient;
        $dob = \Carbon\Carbon::parse($patient->tanggal_lahir);
        $age = $dob->diff(now());
        $ageString = $age->y . ' tahun ' . $age->m . ' bulan';

        return response()->json([
            'success' => true,
            'registration' => $registration,
            'patient' => $patient,
            'age' => $ageString,
            'medical_record' => $registration->medicalRecord,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'subjektif' => 'required|string',
            'objektif' => 'required|string',
            'asesmen' => 'required|string',
            'plan' => 'required|string',
            'tensi' => 'required|string',
            'nadi' => 'required|string',
            'suhu' => 'required|string',
        ]);

        $existing = MedicalRecord::where('registration_id', $request->registration_id)->first();
        if ($existing) {
            $existing->update($request->only([
                'subjektif', 'objektif', 'asesmen', 'plan', 'tensi', 'nadi', 'suhu'
            ]));
            AuditTrail::log('UPDATE', 'medical_records');
            $message = 'Rekam medis berhasil diperbarui.';
        } else {
            MedicalRecord::create($request->only([
                'registration_id', 'subjektif', 'objektif', 'asesmen', 'plan', 'tensi', 'nadi', 'suhu'
            ]));
            AuditTrail::log('CREATE', 'medical_records');
            $message = 'Rekam medis berhasil disimpan.';
        }

        return redirect('/clinical')->with('success', $message);
    }

    public function complete($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->update(['status_antrean' => 'done']);
        AuditTrail::log('UPDATE', 'registrations');

        return redirect('/clinical')->with('success', 'Pasien selesai dilayani.');
    }
}
