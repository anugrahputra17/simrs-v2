@extends('layouts.app')

@section('title', 'Clinical Workstation — SYMPHONY SIMRS')
@section('page-title', 'Clinical Workstation')
@section('page-subtitle', 'Modul 2 — SOAP / CPPT & Tanda Vital')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Queue Sidebar --}}
    <div class="lg:col-span-1">
        <div class="card p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-lg bg-blue-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm text-text-primary">Antrean Pasien</h3>
                    <p class="text-xs text-text-muted">Klik untuk memulai</p>
                </div>
            </div>

            @if($queue->count() > 0)
                <div class="space-y-2" id="queueList">
                    @foreach($queue as $reg)
                        <div class="queue-row flex items-center gap-3 p-3 rounded-xl border border-border"
                             data-registration-id="{{ $reg->id }}"
                             onclick="selectPatient({{ $reg->id }})">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr($reg->patient->nama_lengkap, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $reg->patient->nama_lengkap }}</p>
                                <p class="text-xs text-text-muted">{{ $reg->patient->no_rm }} · {{ $reg->klinik_tujuan }}</p>
                            </div>
                            <span class="badge {{ $reg->status_antrean === 'waiting' ? 'badge-waiting' : 'badge-treating' }}">
                                {{ $reg->status_antrean === 'waiting' ? 'W' : 'T' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-text-muted">
                    <p class="text-sm">Antrean kosong</p>
                </div>
            @endif
        </div>
    </div>

    {{-- SOAP Form (Gatekeeper Protected) --}}
    <div class="lg:col-span-3">
        {{-- Patient Info Banner --}}
        <div class="card p-5 mb-6 hidden" id="patientBanner">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-lg font-bold text-white" id="patientInitials">--</div>
                    <div>
                        <h3 class="font-semibold text-text-primary" id="patientName">-</h3>
                        <p class="text-xs text-text-muted">
                            <span id="patientRm">-</span> ·
                            <span id="patientGender">-</span> ·
                            <span id="patientAge">-</span> ·
                            <span id="patientClinic">-</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SOAP Form --}}
        <div class="card p-6 gatekeeper-overlay" id="soapFormContainer">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-text-primary">Catatan SOAP & Tanda Vital</h3>
                    <p class="text-xs text-text-muted">Structured clinical documentation</p>
                </div>
            </div>

            <form method="POST" action="/clinical" id="soapForm">
                @csrf
                <input type="hidden" name="registration_id" id="registrationId" value="">

                {{-- Vital Signs Row --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="tensi" class="form-label">Tensi (mmHg)</label>
                        <input type="text" id="tensi" name="tensi" class="form-input" placeholder="120/80" disabled required>
                    </div>
                    <div>
                        <label for="nadi" class="form-label">Nadi (x/menit)</label>
                        <input type="text" id="nadi" name="nadi" class="form-input" placeholder="80" disabled required>
                    </div>
                    <div>
                        <label for="suhu" class="form-label">Suhu (°C)</label>
                        <input type="text" id="suhu" name="suhu" class="form-input" placeholder="36.5" disabled required>
                    </div>
                </div>

                {{-- SOAP Fields --}}
                <div class="space-y-5">
                    <div>
                        <label for="subjektif" class="form-label">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-blue-info text-white text-xs font-bold flex items-center justify-center">S</span>
                                Subjektif
                            </span>
                        </label>
                        <textarea id="subjektif" name="subjektif" rows="3" class="form-input" placeholder="Keluhan utama pasien..." disabled required></textarea>
                    </div>
                    <div>
                        <label for="objektif" class="form-label">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-emerald-primary text-white text-xs font-bold flex items-center justify-center">O</span>
                                Objektif
                            </span>
                        </label>
                        <textarea id="objektif" name="objektif" rows="3" class="form-input" placeholder="Hasil pemeriksaan fisik..." disabled required></textarea>
                    </div>
                    <div>
                        <label for="asesmen" class="form-label">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-amber-warn text-white text-xs font-bold flex items-center justify-center">A</span>
                                Asesmen
                            </span>
                        </label>
                        <textarea id="asesmen" name="asesmen" rows="3" class="form-input" placeholder="Diagnosis / penilaian klinis..." disabled required></textarea>
                    </div>
                    <div>
                        <label for="plan" class="form-label">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-purple-600 text-white text-xs font-bold flex items-center justify-center">P</span>
                                Plan
                            </span>
                        </label>
                        <textarea id="plan" name="plan" rows="3" class="form-input" placeholder="Rencana tindakan / terapi..." disabled required></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="btn btn-primary" id="saveSoapBtn" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan & Selesaikan Pelayanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectPatient(registrationId) {
        // Highlight selected row
        document.querySelectorAll('.queue-row').forEach(r => r.classList.remove('selected'));
        const selectedRow = document.querySelector(`[data-registration-id="${registrationId}"]`);
        if (selectedRow) selectedRow.classList.add('selected');

        // Fetch patient data
        fetch(`/clinical/patient/${registrationId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Show patient banner
                const banner = document.getElementById('patientBanner');
                banner.classList.remove('hidden');
                document.getElementById('patientName').textContent = data.patient.nama_lengkap;
                document.getElementById('patientRm').textContent = data.patient.no_rm;
                document.getElementById('patientGender').textContent = data.patient.jenis_kelamin == '1' ? 'Laki-laki' : 'Perempuan';
                document.getElementById('patientAge').textContent = data.age;
                document.getElementById('patientClinic').textContent = data.registration.klinik_tujuan;
                document.getElementById('patientInitials').textContent = data.patient.nama_lengkap.substring(0, 2).toUpperCase();

                // Set registration ID
                document.getElementById('registrationId').value = registrationId;

                // Unlock gatekeeper
                const container = document.getElementById('soapFormContainer');
                container.classList.add('unlocked');

                // Enable all fields
                const fields = ['tensi', 'nadi', 'suhu', 'subjektif', 'objektif', 'asesmen', 'plan'];
                fields.forEach(f => {
                    document.getElementById(f).disabled = false;
                });
                document.getElementById('saveSoapBtn').disabled = false;

                // Pre-fill if existing record
                if (data.medical_record) {
                    const mr = data.medical_record;
                    document.getElementById('tensi').value = mr.tensi || '';
                    document.getElementById('nadi').value = mr.nadi || '';
                    document.getElementById('suhu').value = mr.suhu || '';
                    document.getElementById('subjektif').value = mr.subjektif || '';
                    document.getElementById('objektif').value = mr.objektif || '';
                    document.getElementById('asesmen').value = mr.asesmen || '';
                    document.getElementById('plan').value = mr.plan || '';
                } else {
                    fields.forEach(f => document.getElementById(f).value = '');
                }
            }
        })
        .catch(err => console.error('Error loading patient:', err));
    }
</script>
@endpush
@endsection
