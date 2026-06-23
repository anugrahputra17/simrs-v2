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

    public function searchPatient(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['error' => 'Query parameter is missing'], 400);
        }

        $patient = Patient::where('nik', $query)->orWhere('no_bpjs', $query)->first();

        if ($patient) {
            AuditTrail::logSearch($query, 'FOUND');
            return response()->json([
                'found' => true,
                'patient' => $patient
            ]);
        } else {
            AuditTrail::logSearch($query, 'NOT_FOUND');
            return response()->json([
                'found' => false
            ]);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'patient_id' => 'nullable|exists:patients,id',
            'nama_lengkap' => 'required|string|max:255',
            'gelar_kehormatan' => 'nullable|string|max:255',
            'nik' => 'required|string|size:16',
            'no_identitas_lain' => 'nullable|string|max:255',
            'status_merokok' => 'required|boolean',
            'nama_ibu_kandung' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|integer|between:0,4',
            'agama' => 'required|integer|between:1,8',
            'agama_lainnya' => 'nullable|string|max:255',
            'suku' => 'required|string|max:255',
            'bahasa_dikuasai' => 'required|string|max:255',
            'no_telepon_rumah' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:255',
            'pendidikan' => 'required|integer|between:0,8',
            'pekerjaan' => 'required|integer|between:0,5',
            'pekerjaan_lainnya' => 'nullable|string|max:255',
            'status_pernikahan' => 'required|integer|between:1,4',
            
            // Emergency Contact Data
            'emergency_nama' => 'required|string|max:255',
            'emergency_hubungan' => 'required|string|max:255',
            'emergency_no_ktp' => 'required|string|size:16',
            'emergency_no_hp' => 'required|string|max:255',
            'emergency_alamat' => 'required|string',

            // Alamat KTP
            'alamat_ktp' => 'required|string',
            'rt_ktp' => 'required|string|max:3',
            'rw_ktp' => 'required|string|max:3',
            'kelurahan_ktp' => 'required|string|max:255',
            'kecamatan_ktp' => 'required|string|max:255',
            'kabupaten_ktp' => 'required|string|max:255',
            'provinsi_ktp' => 'required|string|max:255',
            'kode_pos_ktp' => 'required|string|max:10',
            'negara_ktp' => 'required|string|max:255',

            // Alamat Domisili
            'alamat_domisili' => 'required|string',
            'rt_domisili' => 'required|string|max:3',
            'rw_domisili' => 'required|string|max:3',
            'kelurahan_domisili' => 'required|string|max:255',
            'kecamatan_domisili' => 'required|string|max:255',
            'kabupaten_domisili' => 'required|string|max:255',
            'provinsi_domisili' => 'required|string|max:255',
            'kode_pos_domisili' => 'required|string|max:10',
            'negara_domisili' => 'required|string|max:255',

            'penjamin' => 'required|string|max:255',
            'klinik_tujuan' => 'required|string|max:255',
        ];

        // BPJS validation
        if ($request->penjamin === 'BPJS') {
            $rules['no_bpjs'] = 'required|string|size:13';
        } else {
            $rules['no_bpjs'] = 'nullable|string|size:13';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $patientData = [
                'gelar_kehormatan' => $request->gelar_kehormatan,
                'nama_lengkap' => strtoupper($request->nama_lengkap),
                'nik' => $request->nik,
                'no_bpjs' => $request->no_bpjs,
                'no_identitas_lain' => $request->no_identitas_lain,
                'status_merokok' => $request->status_merokok,
                'nama_ibu_kandung' => $request->nama_ibu_kandung,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'agama_lainnya' => $request->agama_lainnya,
                'suku' => $request->suku,
                'bahasa_dikuasai' => $request->bahasa_dikuasai,
                'no_telepon_rumah' => $request->no_telepon_rumah,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
                'pekerjaan' => $request->pekerjaan,
                'pekerjaan_lainnya' => $request->pekerjaan_lainnya,
                'status_pernikahan' => $request->status_pernikahan,
                
                'emergency_nama' => $request->emergency_nama,
                'emergency_hubungan' => $request->emergency_hubungan,
                'emergency_no_ktp' => $request->emergency_no_ktp,
                'emergency_no_hp' => $request->emergency_no_hp,
                'emergency_alamat' => $request->emergency_alamat,

                'alamat_ktp' => $request->alamat_ktp,
                'rt_ktp' => $request->rt_ktp,
                'rw_ktp' => $request->rw_ktp,
                'kelurahan_ktp' => $request->kelurahan_ktp,
                'kecamatan_ktp' => $request->kecamatan_ktp,
                'kabupaten_ktp' => $request->kabupaten_ktp,
                'provinsi_ktp' => $request->provinsi_ktp,
                'kode_pos_ktp' => $request->kode_pos_ktp,
                'negara_ktp' => $request->negara_ktp,

                'alamat_domisili' => $request->alamat_domisili,
                'rt_domisili' => $request->rt_domisili,
                'rw_domisili' => $request->rw_domisili,
                'kelurahan_domisili' => $request->kelurahan_domisili,
                'kecamatan_domisili' => $request->kecamatan_domisili,
                'kabupaten_domisili' => $request->kabupaten_domisili,
                'provinsi_domisili' => $request->provinsi_domisili,
                'kode_pos_domisili' => $request->kode_pos_domisili,
                'negara_domisili' => $request->negara_domisili,

                'penjamin' => $request->penjamin,
            ];

            if ($request->filled('patient_id')) {
                $patient = Patient::findOrFail($request->patient_id);
                $patient->update($patientData);
                $typeKunjungan = 'Lama';
                AuditTrail::log('UPDATE', 'patients');
            } else {
                $patientData['no_rm'] = $this->generateNextRm();
                $patient = Patient::create($patientData);
                $typeKunjungan = 'Baru';
                AuditTrail::log('CREATE', 'patients');
                
                HybridTracker::create([
                    'patient_id' => $patient->id,
                    'nomor_rak' => null,
                    'status_scan' => false,
                    'is_lengkap' => false,
                ]);
            }

            Registration::create([
                'patient_id' => $patient->id,
                'type_kunjungan' => $typeKunjungan,
                'klinik_tujuan' => $request->klinik_tujuan,
                'status_antrean' => 'waiting',
            ]);

            AuditTrail::log('CREATE', 'registrations');

            DB::commit();
            return redirect('/admission')->with('success', 'Kunjungan pasien ' . strtolower($typeKunjungan) . ' berhasil didaftarkan (No. RM: ' . $patient->no_rm . ')');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pendaftaran pasien: ' . $e->getMessage())->withInput();
        }
    }

    private function generateNextRm(): string
    {
        $last = Patient::orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = (int) str_replace('RM-', '', $last->no_rm);
            return 'RM-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }
        return 'RM-000001';
    }
}
