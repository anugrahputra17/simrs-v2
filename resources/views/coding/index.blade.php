@extends('layouts.app')

@section('title', 'Medical Coding — SYMPHONY SIMRS')
@section('page-title', 'Medical Coding Unit')
@section('page-subtitle', 'Modul 3 — SNOMED CT & ICD-10 Mapping')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- SOAP Summary (Auto-mirrored) --}}
    <div class="lg:col-span-1">
        <div class="card p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-lg bg-blue-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm text-text-primary">Resume Medis</h3>
                    <p class="text-xs text-text-muted">Auto-mirrored dari SOAP</p>
                </div>
            </div>

            {{-- Patient Info --}}
            <div class="bg-warm-bg-alt rounded-xl p-4 mb-4">
                <p class="text-sm font-semibold">{{ $medicalRecord->registration->patient->nama }}</p>
                <p class="text-xs text-text-muted">{{ $medicalRecord->registration->patient->nomor_rm }} · {{ $medicalRecord->registration->klinik_tujuan }}</p>
            </div>

            {{-- SOAP Summary --}}
            <div class="space-y-3">
                <div class="p-3 rounded-lg border border-border">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-info mb-1">
                        <span class="w-5 h-5 rounded bg-blue-info text-white text-[10px] font-bold flex items-center justify-center">S</span>
                        Subjektif
                    </span>
                    <p class="text-sm text-text-primary">{{ $medicalRecord->subjektif ?: '-' }}</p>
                </div>
                <div class="p-3 rounded-lg border border-border">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-primary mb-1">
                        <span class="w-5 h-5 rounded bg-emerald-primary text-white text-[10px] font-bold flex items-center justify-center">O</span>
                        Objektif
                    </span>
                    <p class="text-sm text-text-primary">{{ $medicalRecord->objektif ?: '-' }}</p>
                </div>
                <div class="p-3 rounded-lg border border-border">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-warn mb-1">
                        <span class="w-5 h-5 rounded bg-amber-warn text-white text-[10px] font-bold flex items-center justify-center">A</span>
                        Asesmen
                    </span>
                    <p class="text-sm text-text-primary">{{ $medicalRecord->asesmen ?: '-' }}</p>
                </div>
                <div class="p-3 rounded-lg border border-border">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 mb-1">
                        <span class="w-5 h-5 rounded bg-purple-600 text-white text-[10px] font-bold flex items-center justify-center">P</span>
                        Plan
                    </span>
                    <p class="text-sm text-text-primary">{{ $medicalRecord->plan ?: '-' }}</p>
                </div>
            </div>

            {{-- Vital Signs --}}
            <div class="mt-4 grid grid-cols-3 gap-2">
                <div class="bg-warm-bg-alt rounded-lg p-2 text-center">
                    <p class="text-[10px] text-text-muted uppercase">Tensi</p>
                    <p class="text-sm font-semibold">{{ $medicalRecord->tensi ?: '-' }}</p>
                </div>
                <div class="bg-warm-bg-alt rounded-lg p-2 text-center">
                    <p class="text-[10px] text-text-muted uppercase">Nadi</p>
                    <p class="text-sm font-semibold">{{ $medicalRecord->nadi ?: '-' }}</p>
                </div>
                <div class="bg-warm-bg-alt rounded-lg p-2 text-center">
                    <p class="text-[10px] text-text-muted uppercase">Suhu</p>
                    <p class="text-sm font-semibold">{{ $medicalRecord->suhu ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Coding Panel --}}
    <div class="lg:col-span-2">
        {{-- Miscoding Alert Banner --}}
        <div class="alert alert-miscoding mb-6 hidden" id="miscodingAlert">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            <div>
                <p class="font-bold">⚠ CDSS MISCODING ALERT</p>
                <p class="text-sm font-normal mt-1">Kondisi kronis ditetapkan sebagai diagnosis utama, padahal terdapat kondisi akut. Periksa kembali prioritas diagnosis sesuai kaidah ICD-10.</p>
            </div>
        </div>

        {{-- SNOMED Search --}}
        @if($medicalRecord->status_coding !== 'done')
        <div class="card p-6 mb-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-emerald-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-text-primary">Terminology Search Engine</h3>
                    <p class="text-xs text-text-muted">Cari istilah SNOMED CT (Snowstorm API)</p>
                </div>
            </div>

            <div style="position: relative; display: flex; align-items: center; width: 100%;">
                <svg style="position: absolute; left: 1.25rem; width: 1.25rem; height: 1.25rem; color: #94a3b8; pointer-events: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="snomedSearch" class="form-input text-sm" style="padding-top: 0.875rem; padding-bottom: 0.875rem; padding-left: 3.25rem; padding-right: 1rem; width: 100%;" placeholder="Ketik diagnosis (min 2 karakter)... cth: Appendicitis, Gastritis, GERD">
                
                <div style="position: absolute; right: 1.25rem;" class="hidden" id="searchSpinner">
                    <svg class="w-5 h-5 text-emerald-primary animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </div>
            </div>

            {{-- Search Results Dropdown --}}
            <div class="mt-2 border border-border rounded-xl max-h-64 overflow-y-auto hidden" id="searchResults">
                {{-- Populated by JS --}}
            </div>

            {{-- Selected Term Display --}}
            <div class="mt-4 hidden" id="selectedTermDisplay">
                <div class="bg-emerald-light/50 rounded-xl p-4 border border-emerald-primary/20">
                    <p class="text-xs text-text-muted mb-1">Term yang dipilih</p>
                    <p class="font-semibold text-text-primary" id="selectedTermText">-</p>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="text-xs text-text-muted">Concept ID: <span class="font-mono font-semibold text-emerald-primary" id="selectedConceptId">-</span></span>
                        <span class="text-xs text-text-muted">ICD-10: <span class="font-mono font-semibold text-blue-info" id="selectedIcd10">Loading...</span></span>
                    </div>

                    <div class="mt-3 flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="isPrimaryDiagnosis" class="w-4 h-4 rounded border-border text-emerald-primary focus:ring-emerald-primary">
                            <span class="text-sm font-medium">Diagnosis Utama (Primary)</span>
                        </label>
                        <button type="button" onclick="saveCoding()" class="btn btn-primary btn-sm ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Kode
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Existing Codings List --}}
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-text-primary mb-4">Daftar Diagnosis Terkode</h3>
            <div id="codingsList">
                @if($medicalRecord->codings->count() > 0)
                    <div class="space-y-2">
                        @foreach($medicalRecord->codings as $coding)
                            <div class="flex items-center gap-3 p-3 rounded-xl border {{ $coding->miscoding_status ? 'border-crimson bg-crimson-light/30' : 'border-border' }}" id="coding-{{ $coding->id }}">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        @if($coding->is_primary_diagnosis)
                                            <span class="badge bg-emerald-light text-emerald-primary">PRIMARY</span>
                                        @endif
                                        @if($coding->miscoding_status)
                                            <span class="badge badge-danger">MISCODING</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium mt-1">{{ $coding->snomed_term }}</p>
                                    <p class="text-xs text-text-muted mt-0.5">
                                        SNOMED: <span class="font-mono">{{ $coding->snomed_concept_id }}</span> ·
                                        ICD-10: <span class="font-mono font-semibold text-blue-info">{{ $coding->icd10_mapped_code }}</span>
                                    </p>
                                </div>
                                @if($medicalRecord->status_coding !== 'done')
                                <button onclick="deleteCoding({{ $coding->id }})" class="text-text-muted hover:text-crimson transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-text-muted text-center py-4" id="noCodingsMsg">Belum ada diagnosis terkode</p>
                @endif
            </div>
        </div>

        {{-- Finalize Action --}}
        @if($medicalRecord->status_coding !== 'done')
        <form method="POST" action="{{ route('coding.complete', $medicalRecord->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi data koding ini? Setelah difinalisasi, data tidak dapat diubah lagi.')">
            @csrf
            <button type="submit" class="btn btn-primary w-full py-3 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Finalisasi & Selesai Coding
            </button>
        </form>
        @else
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200 text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">Dokumen Rekam Medis Ini Telah Selesai Dikoding.</span>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const medicalRecordId = {{ $medicalRecord->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchTimeout = null;
    let selectedConcept = null;

    // Debounced SNOMED search
    document.getElementById('snomedSearch').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        const results = document.getElementById('searchResults');
        const spinner = document.getElementById('searchSpinner');

        if (term.length < 2) {
            results.classList.add('hidden');
            return;
        }

        spinner.classList.remove('hidden');

        searchTimeout = setTimeout(() => {
            fetch(`/api/snomed/search?term=${encodeURIComponent(term)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                spinner.classList.add('hidden');
                results.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    results.classList.remove('hidden');
                    data.items.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-warm-bg-alt cursor-pointer transition-colors border-b border-border last:border-b-0';
                        div.innerHTML = `
                            <p class="text-sm font-medium text-text-primary">${item.term}</p>
                            <p class="text-xs text-text-muted mt-0.5">Concept ID: <span class="font-mono">${item.conceptId}</span></p>
                        `;
                        div.addEventListener('click', () => selectTerm(item));
                        results.appendChild(div);
                    });
                } else {
                    results.classList.remove('hidden');
                    results.innerHTML = '<div class="p-4 text-sm text-text-muted text-center">Tidak ditemukan hasil</div>';
                }

                if (data.error) {
                    results.classList.remove('hidden');
                    results.innerHTML = `<div class="p-4 text-sm text-amber-warn text-center">${data.error}</div>`;
                }
            })
            .catch(err => {
                spinner.classList.add('hidden');
                console.error('Search error:', err);
            });
        }, 400);
    });

    function selectTerm(item) {
        selectedConcept = item;
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('snomedSearch').value = item.term;

        const display = document.getElementById('selectedTermDisplay');
        display.classList.remove('hidden');
        document.getElementById('selectedTermText').textContent = item.term;
        document.getElementById('selectedConceptId').textContent = item.conceptId;
        document.getElementById('selectedIcd10').textContent = 'Loading...';

        // Fetch ICD-10 mapping
        fetch(`/api/snomed/map/${item.conceptId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('selectedIcd10').textContent = data.icd10Code || 'N/A';
            selectedConcept.icd10Code = data.icd10Code || 'N/A';
        })
        .catch(() => {
            document.getElementById('selectedIcd10').textContent = 'Error';
        });
    }

    function saveCoding() {
        if (!selectedConcept) return;

        const isPrimary = document.getElementById('isPrimaryDiagnosis').checked;

        fetch('/api/coding/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                medical_record_id: medicalRecordId,
                snomed_concept_id: selectedConcept.conceptId,
                snomed_term: selectedConcept.term,
                icd10_mapped_code: selectedConcept.icd10Code || 'N/A',
                is_primary_diagnosis: isPrimary
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Check miscoding
                if (data.miscoding_status === 'chronic_over_acute') {
                    document.getElementById('miscodingAlert').classList.remove('hidden');
                }

                // Add to list
                const noCodingsMsg = document.getElementById('noCodingsMsg');
                if (noCodingsMsg) noCodingsMsg.remove();

                const list = document.getElementById('codingsList');
                let container = list.querySelector('.space-y-2');
                if (!container) {
                    container = document.createElement('div');
                    container.className = 'space-y-2';
                    list.appendChild(container);
                }

                const coding = data.coding;
                const div = document.createElement('div');
                div.className = `flex items-center gap-3 p-3 rounded-xl border ${coding.miscoding_status ? 'border-crimson bg-crimson-light/30' : 'border-border'}`;
                div.id = `coding-${coding.id}`;
                div.innerHTML = `
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            ${coding.is_primary_diagnosis ? '<span class="badge bg-emerald-light text-emerald-primary">PRIMARY</span>' : ''}
                            ${coding.miscoding_status ? '<span class="badge badge-danger">MISCODING</span>' : ''}
                        </div>
                        <p class="text-sm font-medium mt-1">${coding.snomed_term}</p>
                        <p class="text-xs text-text-muted mt-0.5">
                            SNOMED: <span class="font-mono">${coding.snomed_concept_id}</span> ·
                            ICD-10: <span class="font-mono font-semibold text-blue-info">${coding.icd10_mapped_code}</span>
                        </p>
                    </div>
                    <button onclick="deleteCoding(${coding.id})" class="text-text-muted hover:text-crimson transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                `;
                container.appendChild(div);

                // Reset search
                document.getElementById('snomedSearch').value = '';
                document.getElementById('selectedTermDisplay').classList.add('hidden');
                document.getElementById('isPrimaryDiagnosis').checked = false;
                selectedConcept = null;
            }
        })
        .catch(err => console.error('Save coding error:', err));
    }

    function deleteCoding(id) {
        if (!confirm('Hapus kode diagnosis ini?')) return;

        fetch(`/api/coding/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById(`coding-${id}`);
                if (el) el.remove();
            }
        });
    }
</script>
@endpush
@endsection
