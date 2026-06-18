@extends('layouts.app')

@section('title', 'Admission Desk — SYMPHONY SIMRS')
@section('page-title', 'Admission Desk')
@section('page-subtitle', 'Modul 1 — Pendaftaran Pasien & Antrean Elektronik')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    {{-- Registration Form (Left Panel) --}}
    <div class="lg:col-span-3">
        <div class="card p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-text-primary">Registrasi Pasien Baru</h3>
                    <p class="text-xs text-text-muted">Nomor RM otomatis: <span class="font-mono font-semibold text-emerald-primary">{{ $nextRm }}</span></p>
                </div>
            </div>

            <form method="POST" action="/admission" class="space-y-5" id="admissionForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="nama" class="form-label">Nama Lengkap Pasien</label>
                        <input type="text" id="nama" name="nama" class="form-input" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                    </div>

                    <div>
                        <label for="nik" class="form-label">NIK (16 Digit)</label>
                        <input type="text" id="nik" name="nik" class="form-input" placeholder="3201234567890123" maxlength="16" pattern="[0-9]{16}" value="{{ old('nik') }}" required>
                        <p class="text-xs text-text-muted mt-1" id="nikCount">0/16 digit</p>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir') }}" required>
                        <p class="text-xs mt-1 font-medium" id="ageDisplay">&nbsp;</p>
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="penjamin" class="form-label">Penjamin / Asuransi</label>
                        <select id="penjamin" name="penjamin" class="form-select" required>
                            <option value="">— Pilih —</option>
                            <option value="BPJS" {{ old('penjamin') === 'BPJS' ? 'selected' : '' }}>BPJS Kesehatan</option>
                            <option value="Asuransi Swasta" {{ old('penjamin') === 'Asuransi Swasta' ? 'selected' : '' }}>Asuransi Swasta</option>
                            <option value="Umum" {{ old('penjamin') === 'Umum' ? 'selected' : '' }}>Umum (Mandiri)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="klinik_tujuan" class="form-label">Klinik Tujuan</label>
                        <select id="klinik_tujuan" name="klinik_tujuan" class="form-select" required>
                            <option value="">— Pilih Klinik —</option>
                            <option value="Poli Umum" {{ old('klinik_tujuan') === 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                            <option value="Poli Penyakit Dalam" {{ old('klinik_tujuan') === 'Poli Penyakit Dalam' ? 'selected' : '' }}>Poli Penyakit Dalam</option>
                            <option value="Poli Bedah" {{ old('klinik_tujuan') === 'Poli Bedah' ? 'selected' : '' }}>Poli Bedah</option>
                            <option value="Poli Anak" {{ old('klinik_tujuan') === 'Poli Anak' ? 'selected' : '' }}>Poli Anak</option>
                            <option value="IGD" {{ old('klinik_tujuan') === 'IGD' ? 'selected' : '' }}>IGD</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Daftarkan Pasien
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Active Queue (Right Panel) --}}
    <div class="lg:col-span-2">
        <div class="card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-text-primary">Antrean Aktif</h3>
                    <p class="text-xs text-text-muted">{{ $queue->count() }} pasien menunggu</p>
                </div>
            </div>

            @if($queue->count() > 0)
                <div class="space-y-2">
                    @foreach($queue as $index => $reg)
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-border hover:bg-warm-bg-alt transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-slate-header text-white flex items-center justify-center text-xs font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-text-primary truncate">{{ $reg->patient->nama }}</p>
                                <p class="text-xs text-text-muted">{{ $reg->patient->nomor_rm }} · {{ $reg->klinik_tujuan }}</p>
                            </div>
                            <span class="badge {{ $reg->status_antrean === 'waiting' ? 'badge-waiting' : 'badge-treating' }}">
                                {{ $reg->status_antrean }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-text-muted">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-sm">Belum ada pasien dalam antrean</p>
                </div>
            @endif
        </div>

        {{-- Patient List --}}
        <div class="card p-6 mt-6">
            <h3 class="font-semibold text-text-primary mb-4">Daftar Pasien Terdaftar</h3>
            @if($patients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>JK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td class="font-mono text-xs">{{ $patient->nomor_rm }}</td>
                                    <td>{{ $patient->nama }}</td>
                                    <td>{{ $patient->jenis_kelamin }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            @else
                <p class="text-sm text-text-muted text-center py-4">Belum ada data pasien</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Real-time age calculator
    document.getElementById('tanggal_lahir').addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        const display = document.getElementById('ageDisplay');

        if (isNaN(dob.getTime()) || dob > today) {
            display.innerHTML = '&nbsp;';
            display.className = 'text-xs mt-1 font-medium';
            return;
        }

        let years = today.getFullYear() - dob.getFullYear();
        let months = today.getMonth() - dob.getMonth();
        let days = today.getDate() - dob.getDate();

        if (days < 0) {
            months--;
        }
        if (months < 0) {
            years--;
            months += 12;
        }

        display.textContent = `Usia: ${years} tahun ${months} bulan`;
        display.className = 'text-xs mt-1 font-medium text-emerald-primary';
    });

    // NIK character counter
    document.getElementById('nik').addEventListener('input', function() {
        const count = this.value.length;
        const counter = document.getElementById('nikCount');
        counter.textContent = `${count}/16 digit`;
        counter.className = count === 16
            ? 'text-xs text-emerald-primary mt-1 font-medium'
            : 'text-xs text-text-muted mt-1';
    });
</script>
@endpush
@endsection
