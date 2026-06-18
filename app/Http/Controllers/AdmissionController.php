<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Registration;
use App\Models\HybridTracker;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(10);
        $queue = Registration::with('patient')
            ->whereIn('status_antrean', ['waiting', 'treating'])
            ->orderBy('created_at', 'asc')
            ->get();
        $nextRm = $this->generateNextRm();

        return view('admission.index', compact('patients', 'queue', 'nextRm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:patients,nik',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'penjamin' => 'required|string|max:255',
            'klinik_tujuan' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $patient = Patient::create([
                'nomor_rm' => $this->generateNextRm(),
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'penjamin' => $request->penjamin,
            ]);

            Registration::create([
                'patient_id' => $patient->id,
                'klinik_tujuan' => $request->klinik_tujuan,
                'status_antrean' => 'waiting',
            ]);

            HybridTracker::create([
                'patient_id' => $patient->id,
                'nomor_rak' => null,
                'status_scan' => false,
                'is_lengkap' => false,
            ]);

            AuditTrail::log('CREATE', 'patients');
            AuditTrail::log('CREATE', 'registrations');

            DB::commit();
            return redirect('/admission')->with('success', 'Pasien berhasil didaftarkan dengan Nomor RM: ' . $patient->nomor_rm);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mendaftarkan pasien: ' . $e->getMessage())->withInput();
        }
    }

    public function registerExisting(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'klinik_tujuan' => 'required|string|max:255',
        ]);

        Registration::create([
            'patient_id' => $request->patient_id,
            'klinik_tujuan' => $request->klinik_tujuan,
            'status_antrean' => 'waiting',
        ]);

        AuditTrail::log('CREATE', 'registrations');

        return redirect('/admission')->with('success', 'Pasien berhasil didaftarkan ke antrean.');
    }

    private function generateNextRm(): string
    {
        $last = Patient::orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = (int) str_replace('RM-', '', $last->nomor_rm);
            return 'RM-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'RM-000001';
    }
}
