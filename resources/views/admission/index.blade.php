@extends('layouts.app')

@section('title', 'Admission Desk — SYMPHONY SIMRS')
@section('page-title', 'Pendaftaran Kunjungan')
@section('page-subtitle', 'Identifikasi dan Registrasi Pasien (RME)')

@section('content')
<style>
    /* Google Stitch & Material Design Inspired Aesthetics */
    body {
        background-color: #faf8f5 !important;
    }
    .stitch-card {
        background-color: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 0 3px rgba(15, 23, 42, 0.02);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stitch-input {
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.2s ease-in-out;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.01);
    }
    .stitch-input:focus {
        background-color: #ffffff;
        border-color: #0d9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        outline: none;
    }
    .stitch-input:read-only {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #64748b;
        cursor: not-allowed;
    }
    .stitch-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .stitch-btn-primary {
        background-color: #0d9488;
        color: white;
        border-radius: 9999px;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.025em;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        border: none;
        cursor: pointer;
    }
    .stitch-btn-primary:hover {
        background-color: #0f766e;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(13, 148, 136, 0.4);
    }
    .stitch-btn-secondary {
        background-color: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 9999px; /* pill shape */
        padding: 1rem 2.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.25s ease;
    }
    .stitch-btn-secondary:hover {
        background-color: #f1f5f9;
    }
    .radio-card input:checked + div {
        background-color: #0d9488;
        color: white;
        border-color: #0d9488;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
    }
    .radio-card input:focus-visible + div {
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.2);
    }
    
    .spinner {
        border: 2.5px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 2.5px solid white;
        width: 1.25rem;
        height: 1.25rem;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div x-data="admissionApp()" class="max-w-7xl mx-auto space-y-10 pb-20 pt-4">

    {{-- 1. HERO SEARCH SECTION --}}
    <section class="stitch-card p-10 flex flex-col md:flex-row gap-10 items-center bg-gradient-to-br from-white to-[#f8fafc]">
        <div class="flex-1 w-full space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 text-teal-700 font-semibold text-xs uppercase tracking-widest mb-2">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                Pencarian Cerdas
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-[#1e293b] leading-tight">Mulai Pendaftaran <br>dengan Identitas Pasien.</h2>
            <p class="text-lg text-slate-500 font-medium">Masukkan 16 digit NIK atau 13 digit Kartu BPJS untuk menarik rekam medis secara otomatis.</p>
            
            <form @submit.prevent="searchPatient" class="mt-8 flex flex-col sm:flex-row gap-4 relative">
                <div style="position: relative; flex: 1; display: flex; align-items: center;" class="group">
                    <svg style="position: absolute; left: 1.5rem; width: 1.5rem; height: 1.5rem; pointer-events: none; color: #94a3b8; transition: color 0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" x-model="searchQuery" class="stitch-input text-xl tracking-wider" style="padding-top: 1rem; padding-bottom: 1rem; padding-left: 3.5rem; padding-right: 1.25rem; width: 100%;" placeholder="Cth: 3201234567890001" required>
                </div>
                <button type="submit" class="stitch-btn-primary px-10" :disabled="isSearching">
                    <span x-show="!isSearching">Telusuri Data</span>
                    <span x-show="isSearching" class="spinner"></span>
                </button>
            </form>

            {{-- Alerts --}}
            <div x-show="searchStatus === 'not_found'" x-cloak x-transition.opacity class="mt-4 p-5 bg-amber-50 rounded-2xl flex gap-4 items-start border border-amber-100">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800">Pasien Belum Terdaftar</h4>
                    <p class="text-slate-600 mt-1">Sistem siap membuat Rekam Medis Elektronik (RME) baru. Silakan lengkapi formulir di bawah untuk pendaftaran.</p>
                </div>
            </div>

            <div x-show="searchStatus === 'found'" x-cloak x-transition.opacity class="mt-4 p-5 bg-teal-50 rounded-2xl flex gap-4 items-start border border-teal-100">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800">Pasien Ditemukan — RM: <span x-text="form.no_rm" class="text-teal-700"></span></h4>
                    <p class="text-slate-600 mt-1">Data profil berhasil disinkronisasi. Pastikan data mutakhir, kemudian pilih Poliklinik tujuan untuk kunjungan hari ini.</p>
                </div>
            </div>
        </div>

        {{-- Active Queue Widget --}}
        <div class="hidden md:block w-80 shrink-0">
            <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 h-full flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-800 text-lg">Antrean Hari Ini</h3>
                    <div class="bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-sm">{{ $queue->count() }}</div>
                </div>
                
                @if($queue->count() > 0)
                    <div class="space-y-4 overflow-y-auto max-h-[220px] pr-2 custom-scrollbar">
                        @foreach($queue as $index => $reg)
                            <div class="flex gap-3 items-center group">
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 font-bold text-sm group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate">{{ $reg->patient->nama_lengkap }}</p>
                                    <p class="text-xs text-slate-500">{{ $reg->klinik_tujuan }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-sm font-medium">Belum ada pasien terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 2. MAIN REGISTRATION FORM --}}
    <form method="POST" action="{{ route('admission.store') }}" x-show="searchStatus !== 'idle'" x-cloak x-transition.opacity.duration.500ms class="space-y-10">
        @csrf
        <input type="hidden" name="patient_id" x-model="form.id">

        {{-- Demografis Card --}}
        <section class="stitch-card p-8 md:p-12">
            <div class="max-w-3xl mb-10">
                <h3 class="text-2xl font-bold text-slate-800">Profil Demografis Pasien</h3>
                <p class="text-slate-500 mt-2 text-lg">Informasi personal sesuai dengan kartu identitas resmi yang berlaku.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="md:col-span-2 mb-2">
                    <label class="inline-flex items-center cursor-pointer p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-100">
                        <input type="checkbox" x-model="isWna" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 w-5 h-5">
                        <div class="ml-4">
                            <span class="block text-base font-bold text-slate-700">Pasien WNA / Kondisi Darurat Tanpa NIK</span>
                            <span class="block text-sm text-slate-500 mt-0.5">Sistem akan menggunakan NIK sementara (9999...)</span>
                        </div>
                    </label>
                </div>

                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="stitch-label">Gelar</label>
                            <input type="text" name="gelar_kehormatan" x-model="form.gelar_kehormatan" class="stitch-input" placeholder="Cth: Tn/Ny">
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">Nama Lengkap Pasien <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" class="stitch-input uppercase" required>
                        </div>
                    </div>

                    <div>
                        <label class="stitch-label">Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" x-model="form.nik" :readonly="isWna" class="stitch-input font-mono text-lg tracking-widest" maxlength="16" pattern="[0-9]{16}" required>
                    </div>

                    <div x-show="isWna" x-collapse>
                        <label class="stitch-label">Nomor Paspor / ID Lain <span class="text-red-500">*</span></label>
                        <input type="text" name="no_identitas_lain" x-model="form.no_identitas_lain" class="stitch-input" :required="isWna">
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="stitch-label">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" class="stitch-input" required>
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" class="stitch-input" required>
                        </div>
                    </div>

                    <div class="bg-teal-50 text-teal-800 rounded-2xl p-4 flex items-center justify-between">
                        <span class="font-semibold text-sm">Kalkulasi Usia Sistem</span>
                        <span class="text-lg font-bold" x-text="calculatedAge || '-'"></span>
                    </div>

                    <div>
                        <label class="stitch-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="1" x-model="form.jenis_kelamin" class="sr-only" required>
                                <div class="px-4 py-3 rounded-xl border border-slate-200 text-center font-semibold text-slate-600 transition-all">Laki-laki</div>
                            </label>
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="2" x-model="form.jenis_kelamin" class="sr-only" required>
                                <div class="px-4 py-3 rounded-xl border border-slate-200 text-center font-semibold text-slate-600 transition-all">Perempuan</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="stitch-label">Nama Ibu Kandung <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_ibu_kandung" x-model="form.nama_ibu_kandung" class="stitch-input" required>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="stitch-label">Agama <span class="text-red-500">*</span></label>
                            <select name="agama" x-model="form.agama" class="stitch-input" required>
                                <option value="">— Pilih —</option>
                                <option value="1">Islam</option>
                                <option value="2">Kristen Protestan</option>
                                <option value="3">Katolik</option>
                                <option value="4">Hindu</option>
                                <option value="5">Buddha</option>
                                <option value="6">Konghucu</option>
                                <option value="7">Penghayat</option>
                                <option value="8">Lainnya</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">Status Pernikahan <span class="text-red-500">*</span></label>
                            <select name="status_pernikahan" x-model="form.status_pernikahan" class="stitch-input" required>
                                <option value="">— Pilih —</option>
                                <option value="1">Belum Kawin</option>
                                <option value="2">Kawin</option>
                                <option value="3">Cerai Hidup</option>
                                <option value="4">Cerai Mati</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="stitch-label">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                            <select name="pendidikan" x-model="form.pendidikan" class="stitch-input" required>
                                <option value="">— Pilih —</option>
                                <option value="0">Tidak Sekolah</option>
                                <option value="1">SD</option>
                                <option value="2">SMP</option>
                                <option value="3">SMA</option>
                                <option value="4">D1-D3</option>
                                <option value="5">S1</option>
                                <option value="6">S2</option>
                                <option value="7">S3</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">Pekerjaan Saat Ini <span class="text-red-500">*</span></label>
                            <select name="pekerjaan" x-model="form.pekerjaan" class="stitch-input" required>
                                <option value="">— Pilih —</option>
                                <option value="0">Tidak Bekerja</option>
                                <option value="1">PNS/TNI/POLRI</option>
                                <option value="2">Pegawai Swasta</option>
                                <option value="3">Wiraswasta</option>
                                <option value="4">Pelajar/Mahasiswa</option>
                                <option value="5">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="stitch-label">Suku Bangsa <span class="text-red-500">*</span></label>
                            <input type="text" name="suku" x-model="form.suku" class="stitch-input" required>
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">Bahasa Utama <span class="text-red-500">*</span></label>
                            <input type="text" name="bahasa_dikuasai" x-model="form.bahasa_dikuasai" class="stitch-input" required>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="stitch-label">Telp. Rumah</label>
                            <input type="text" name="no_telepon_rumah" x-model="form.no_telepon_rumah" class="stitch-input font-mono">
                        </div>
                        <div class="flex-1">
                            <label class="stitch-label">No. WhatsApp / HP Utama <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" x-model="form.no_hp" class="stitch-input font-mono font-bold text-slate-800" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="stitch-label">Status Merokok Pasien <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="status_merokok" value="0" x-model="form.status_merokok" class="sr-only" required>
                                <div class="px-4 py-3 rounded-xl border border-slate-200 text-center font-semibold text-slate-600 transition-all">Tidak Merokok</div>
                            </label>
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="status_merokok" value="1" x-model="form.status_merokok" class="sr-only" required>
                                <div class="px-4 py-3 rounded-xl border border-slate-200 text-center font-semibold text-slate-600 transition-all">Ya, Perokok</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Alamat & Kontak Darurat Card --}}
        <section class="stitch-card p-8 md:p-12">
            <div class="max-w-3xl mb-10">
                <h3 class="text-2xl font-bold text-slate-800">Alamat & Kontak Darurat</h3>
                <p class="text-slate-500 mt-2 text-lg">Informasi domisili saat ini dan kontak orang terdekat (Penanggung Jawab).</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- Alamat KTP --}}
                <div class="space-y-5">
                    <h4 class="font-bold text-slate-800 text-lg mb-2">Alamat Domisili KTP</h4>
                    <div>
                        <label class="stitch-label">Jalan / Gedung / Perumahan <span class="text-red-500">*</span></label>
                        <textarea name="alamat_ktp" x-model="alamat.ktp.alamat" rows="2" class="stitch-input resize-none" required></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="stitch-label">RT <span class="text-red-500">*</span></label><input type="text" name="rt_ktp" x-model="alamat.ktp.rt" maxlength="3" class="stitch-input" required></div>
                        <div><label class="stitch-label">RW <span class="text-red-500">*</span></label><input type="text" name="rw_ktp" x-model="alamat.ktp.rw" maxlength="3" class="stitch-input" required></div>
                        <div><label class="stitch-label">ID Provinsi <span class="text-red-500">*</span></label><input type="number" name="provinsi_id_ktp" x-model="alamat.ktp.provinsi_id" class="stitch-input" required></div>
                        <div><label class="stitch-label">ID Kab/Kota <span class="text-red-500">*</span></label><input type="number" name="kabupaten_id_ktp" x-model="alamat.ktp.kabupaten_id" class="stitch-input" required></div>
                        <div><label class="stitch-label">ID Kecamatan <span class="text-red-500">*</span></label><input type="number" name="kecamatan_id_ktp" x-model="alamat.ktp.kecamatan_id" class="stitch-input" required></div>
                        <div><label class="stitch-label">ID Kel/Desa <span class="text-red-500">*</span></label><input type="number" name="kelurahan_id_ktp" x-model="alamat.ktp.kelurahan_id" class="stitch-input" required></div>
                        <div><label class="stitch-label">Kode Pos <span class="text-red-500">*</span></label><input type="text" name="kode_pos_ktp" x-model="alamat.ktp.kode_pos" class="stitch-input" required></div>
                        <div><label class="stitch-label">Negara <span class="text-red-500">*</span></label><input type="text" name="negara_ktp" x-model="alamat.ktp.negara" class="stitch-input" required></div>
                    </div>
                </div>

                {{-- Alamat Domisili --}}
                <div class="space-y-5">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-slate-800 text-lg">Alamat Tinggal Saat Ini</h4>
                        <label class="inline-flex items-center cursor-pointer px-3 py-1 bg-slate-100 rounded-full hover:bg-slate-200 transition-colors">
                            <input type="checkbox" x-model="copyKtp" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500 w-4 h-4">
                            <span class="ml-2 text-xs font-bold text-slate-600 uppercase tracking-widest">Sama dengan KTP</span>
                        </label>
                    </div>
                    <div :class="{'opacity-60 pointer-events-none': copyKtp}" class="space-y-5 transition-opacity duration-300">
                        <div>
                            <label class="stitch-label">Jalan / Gedung / Perumahan <span class="text-red-500">*</span></label>
                            <textarea name="alamat_domisili" rows="2" class="stitch-input resize-none" :value="copyKtp ? alamat.ktp.alamat : alamat.domisili.alamat" @input="alamat.domisili.alamat = $event.target.value" required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="stitch-label">RT <span class="text-red-500">*</span></label><input type="text" name="rt_domisili" maxlength="3" class="stitch-input" :value="copyKtp ? alamat.ktp.rt : alamat.domisili.rt" @input="alamat.domisili.rt = $event.target.value" required></div>
                            <div><label class="stitch-label">RW <span class="text-red-500">*</span></label><input type="text" name="rw_domisili" maxlength="3" class="stitch-input" :value="copyKtp ? alamat.ktp.rw : alamat.domisili.rw" @input="alamat.domisili.rw = $event.target.value" required></div>
                            <div><label class="stitch-label">ID Provinsi <span class="text-red-500">*</span></label><input type="number" name="provinsi_id_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.provinsi_id : alamat.domisili.provinsi_id" @input="alamat.domisili.provinsi_id = $event.target.value" required></div>
                            <div><label class="stitch-label">ID Kab/Kota <span class="text-red-500">*</span></label><input type="number" name="kabupaten_id_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.kabupaten_id : alamat.domisili.kabupaten_id" @input="alamat.domisili.kabupaten_id = $event.target.value" required></div>
                            <div><label class="stitch-label">ID Kecamatan <span class="text-red-500">*</span></label><input type="number" name="kecamatan_id_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.kecamatan_id : alamat.domisili.kecamatan_id" @input="alamat.domisili.kecamatan_id = $event.target.value" required></div>
                            <div><label class="stitch-label">ID Kel/Desa <span class="text-red-500">*</span></label><input type="number" name="kelurahan_id_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.kelurahan_id : alamat.domisili.kelurahan_id" @input="alamat.domisili.kelurahan_id = $event.target.value" required></div>
                            <div><label class="stitch-label">Kode Pos <span class="text-red-500">*</span></label><input type="text" name="kode_pos_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.kode_pos : alamat.domisili.kode_pos" @input="alamat.domisili.kode_pos = $event.target.value" required></div>
                            <div><label class="stitch-label">Negara <span class="text-red-500">*</span></label><input type="text" name="negara_domisili" class="stitch-input" :value="copyKtp ? alamat.ktp.negara : alamat.domisili.negara" @input="alamat.domisili.negara = $event.target.value" required></div>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="lg:col-span-2 mt-4 pt-8 border-t border-slate-100">
                    <h4 class="font-bold text-slate-800 text-lg mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Kontak Darurat (Keluarga Terdekat)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <label class="stitch-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_nama" x-model="form.emergency_nama" class="stitch-input" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="stitch-label">Hubungan <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_hubungan" x-model="form.emergency_hubungan" class="stitch-input" placeholder="Cth: Suami/Anak" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="stitch-label">Nomor KTP <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_no_ktp" x-model="form.emergency_no_ktp" class="stitch-input font-mono" maxlength="16" pattern="[0-9]{16}" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="stitch-label">No. Handphone <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_no_hp" x-model="form.emergency_no_hp" class="stitch-input font-mono" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="stitch-label">Alamat Lengkap Kontak Darurat <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_alamat" x-model="form.emergency_alamat" class="stitch-input" required>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Layanan Medis & Penjaminan --}}
        <section class="stitch-card p-8 md:p-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
                <svg class="w-64 h-64 text-teal-900" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8.5 15H9v-2h1.5v2zm0-4H9V7h1.5v7zm4 4h-1.5v-2H14.5v2zm0-4h-1.5V7H14.5v7z"/></svg>
            </div>
            
            <div class="max-w-3xl mb-10 relative z-10">
                <h3 class="text-2xl font-bold text-slate-800">Tujuan Layanan Klinis</h3>
                <p class="text-slate-500 mt-2 text-lg">Pilih metode penjaminan dan poliklinik tujuan untuk kunjungan hari ini.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 relative z-10">
                <div class="space-y-6">
                    <div>
                        <label class="stitch-label mb-3">Tipe Penjamin / Asuransi <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="penjamin" value="BPJS" x-model="form.penjamin" class="sr-only" required>
                                <div class="px-4 py-4 rounded-2xl border border-slate-200 text-center font-bold text-slate-700 transition-all">BPJS Kesehatan</div>
                            </label>
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="penjamin" value="Asuransi Swasta" x-model="form.penjamin" class="sr-only" required>
                                <div class="px-4 py-4 rounded-2xl border border-slate-200 text-center font-bold text-slate-700 transition-all">Asuransi Lain</div>
                            </label>
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="penjamin" value="Umum" x-model="form.penjamin" class="sr-only" required>
                                <div class="px-4 py-4 rounded-2xl border border-slate-200 text-center font-bold text-slate-700 transition-all">Umum (Pribadi)</div>
                            </label>
                        </div>
                    </div>
                    
                    <div x-show="form.penjamin === 'BPJS'" x-cloak x-collapse>
                        <label class="stitch-label">Nomor Kartu BPJS (13 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="no_bpjs" x-model="form.no_bpjs" class="stitch-input text-xl py-4 font-mono font-bold text-emerald-700 bg-emerald-50/50" maxlength="13" pattern="[0-9]{13}" :required="form.penjamin === 'BPJS'" placeholder="Ketik 13 digit angka">
                    </div>
                </div>

                <div>
                    <label class="stitch-label mb-3">Poliklinik Tujuan (Kunjungan Hari Ini) <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="klinik in ['Poli Umum', 'Poli Penyakit Dalam', 'Poli Bedah', 'Poli Anak', 'IGD']" :key="klinik">
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="klinik_tujuan" :value="klinik" x-model="form.klinik_tujuan" class="sr-only" required>
                                <div class="px-4 py-4 rounded-2xl border border-slate-200 text-center font-bold text-slate-700 transition-all">
                                    <span x-text="klinik"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-14 pt-8 border-t border-slate-100 flex items-center justify-end gap-4 relative z-10">
                <button type="button" @click="resetForm(); searchStatus = 'idle'; searchQuery = '';" class="stitch-btn-secondary">
                    Batalkan
                </button>
                <button type="submit" class="stitch-btn-primary">
                    <span x-text="searchStatus === 'found' ? 'Simpan Data & Buat Antrean' : 'Daftarkan Pasien & Antrean'"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </section>
    </form>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('admissionApp', () => ({
            searchQuery: '',
            searchStatus: 'idle', // 'idle', 'searching', 'found', 'not_found'
            isSearching: false,
            
            isWna: false,
            copyKtp: false,
            
            // Unified Form State for Both New and Existing
            form: {
                id: '', no_rm: '', gelar_kehormatan: '', nama_lengkap: '', nik: '', no_identitas_lain: '', no_bpjs: '', status_merokok: '', tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', nama_ibu_kandung: '', agama: '', suku: '', bahasa_dikuasai: 'Indonesia', status_pernikahan: '', pendidikan: '', pekerjaan: '', no_telepon_rumah: '', no_hp: '', emergency_nama: '', emergency_hubungan: '', emergency_no_ktp: '', emergency_no_hp: '', emergency_alamat: '', penjamin: '', klinik_tujuan: ''
            },
            alamat: {
                ktp: { alamat: '', rt: '', rw: '', provinsi_id: '', kabupaten_id: '', kecamatan_id: '', kelurahan_id: '', kode_pos: '', negara: 'Indonesia' },
                domisili: { alamat: '', rt: '', rw: '', provinsi_id: '', kabupaten_id: '', kecamatan_id: '', kelurahan_id: '', kode_pos: '', negara: 'Indonesia' }
            },

            init() {
                this.$watch('isWna', value => {
                    if(value) {
                        this.form.nik = '9999999999999999';
                    } else {
                        if(this.form.nik === '9999999999999999') this.form.nik = '';
                    }
                });
            },

            resetForm() {
                this.form = { id: '', no_rm: '', gelar_kehormatan: '', nama_lengkap: '', nik: '', no_identitas_lain: '', no_bpjs: '', status_merokok: '', tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', nama_ibu_kandung: '', agama: '', suku: '', bahasa_dikuasai: 'Indonesia', status_pernikahan: '', pendidikan: '', pekerjaan: '', no_telepon_rumah: '', no_hp: '', emergency_nama: '', emergency_hubungan: '', emergency_no_ktp: '', emergency_no_hp: '', emergency_alamat: '', penjamin: '', klinik_tujuan: '' };
                this.alamat.ktp = { alamat: '', rt: '', rw: '', provinsi_id: '', kabupaten_id: '', kecamatan_id: '', kelurahan_id: '', kode_pos: '', negara: 'Indonesia' };
                this.alamat.domisili = { alamat: '', rt: '', rw: '', provinsi_id: '', kabupaten_id: '', kecamatan_id: '', kelurahan_id: '', kode_pos: '', negara: 'Indonesia' };
                this.copyKtp = false;
                this.isWna = false;
            },

            populateForm(data) {
                this.form.id = data.id;
                this.form.no_rm = data.no_rm;
                this.form.gelar_kehormatan = data.gelar_kehormatan || '';
                this.form.nama_lengkap = data.nama_lengkap || '';
                this.form.nik = data.nik || '';
                this.form.no_identitas_lain = data.no_identitas_lain || '';
                this.form.no_bpjs = data.no_bpjs || '';
                this.form.status_merokok = data.status_merokok !== null ? data.status_merokok.toString() : '';
                this.form.tempat_lahir = data.tempat_lahir || '';
                this.form.tanggal_lahir = data.tanggal_lahir ? data.tanggal_lahir.split('T')[0] : '';
                this.form.jenis_kelamin = data.jenis_kelamin !== null ? data.jenis_kelamin.toString() : '';
                this.form.nama_ibu_kandung = data.nama_ibu_kandung || '';
                this.form.agama = data.agama !== null ? data.agama.toString() : '';
                this.form.suku = data.suku || '';
                this.form.bahasa_dikuasai = data.bahasa_dikuasai || 'Indonesia';
                this.form.status_pernikahan = data.status_pernikahan !== null ? data.status_pernikahan.toString() : '';
                this.form.pendidikan = data.pendidikan !== null ? data.pendidikan.toString() : '';
                this.form.pekerjaan = data.pekerjaan !== null ? data.pekerjaan.toString() : '';
                this.form.no_telepon_rumah = data.no_telepon_rumah || '';
                this.form.no_hp = data.no_hp || '';
                
                this.form.emergency_nama = data.emergency_nama || '';
                this.form.emergency_hubungan = data.emergency_hubungan || '';
                this.form.emergency_no_ktp = data.emergency_no_ktp || '';
                this.form.emergency_no_hp = data.emergency_no_hp || '';
                this.form.emergency_alamat = data.emergency_alamat || '';
                
                this.alamat.ktp.alamat = data.alamat_ktp || '';
                this.alamat.ktp.rt = data.rt_ktp || '';
                this.alamat.ktp.rw = data.rw_ktp || '';
                this.alamat.ktp.provinsi_id = data.provinsi_id_ktp || '';
                this.alamat.ktp.kabupaten_id = data.kabupaten_id_ktp || '';
                this.alamat.ktp.kecamatan_id = data.kecamatan_id_ktp || '';
                this.alamat.ktp.kelurahan_id = data.kelurahan_id_ktp || '';
                this.alamat.ktp.kode_pos = data.kode_pos_ktp || '';
                this.alamat.ktp.negara = data.negara_ktp || 'Indonesia';

                this.alamat.domisili.alamat = data.alamat_domisili || '';
                this.alamat.domisili.rt = data.rt_domisili || '';
                this.alamat.domisili.rw = data.rw_domisili || '';
                this.alamat.domisili.provinsi_id = data.provinsi_id_domisili || '';
                this.alamat.domisili.kabupaten_id = data.kabupaten_id_domisili || '';
                this.alamat.domisili.kecamatan_id = data.kecamatan_id_domisili || '';
                this.alamat.domisili.kelurahan_id = data.kelurahan_id_domisili || '';
                this.alamat.domisili.kode_pos = data.kode_pos_domisili || '';
                this.alamat.domisili.negara = data.negara_domisili || 'Indonesia';

                this.form.penjamin = '';
                this.form.klinik_tujuan = '';

                if(this.form.nik === '9999999999999999') {
                    this.isWna = true;
                }
            },

            async searchPatient() {
                if(!this.searchQuery) return;
                
                this.isSearching = true;
                this.searchStatus = 'searching';
                
                try {
                    const response = await fetch(`/admission/search?q=${encodeURIComponent(this.searchQuery)}`);
                    const data = await response.json();
                    
                    if (data.found) {
                        this.searchStatus = 'found';
                        this.populateForm(data.patient);
                        if (this.searchQuery.length === 13) {
                            this.form.penjamin = 'BPJS';
                        }
                    } else {
                        this.searchStatus = 'not_found';
                        this.resetForm();
                        // Auto-fill NIK / BPJS based on length
                        if (this.searchQuery.length === 16) {
                            this.form.nik = this.searchQuery;
                        } else if (this.searchQuery.length === 13) {
                            this.form.no_bpjs = this.searchQuery;
                            this.form.penjamin = 'BPJS';
                        }
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    alert('Terjadi kesalahan saat mencari data pasien.');
                    this.searchStatus = 'idle';
                } finally {
                    this.isSearching = false;
                }
            },

            get calculatedAge() {
                if(!this.form.tanggal_lahir) return '';
                
                const dob = new Date(this.form.tanggal_lahir);
                const today = new Date();
                if (isNaN(dob.getTime()) || dob > today) return '';

                let years = today.getFullYear() - dob.getFullYear();
                let months = today.getMonth() - dob.getMonth();
                let days = today.getDate() - dob.getDate();

                if (days < 0) {
                    months--;
                    const priorMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    days += priorMonth.getDate();
                }
                if (months < 0) {
                    years--;
                    months += 12;
                }

                return `${years} Tahun, ${months} Bulan`;
            }
        }))
    });
</script>
@endpush
@endsection
