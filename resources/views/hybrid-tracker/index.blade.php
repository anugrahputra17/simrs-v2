@extends('layouts.app')

@section('title', 'Hybrid Tracker — SYMPHONY SIMRS')
@section('page-title', 'Hybrid Chart Tracker')
@section('page-subtitle', 'Modul 4 — Manajemen Berkas Rekam Medis (Fisik & Digital)')

@section('content')
<div class="card p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-light flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-warn" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-text-primary">Status Berkas Rekam Medis</h3>
                <p class="text-xs text-text-muted">Tracking dokumen fisik ke rak & proses digitalisasi</p>
            </div>
        </div>
    </div>

    @if($trackers->count() > 0)
        <div class="overflow-x-auto">
            <table class="table-clean w-full">
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Nomor Rak (Fisik)</th>
                        <th>Status Scan PDF (Digital)</th>
                        <th>Kelengkapan (KLPCM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trackers as $tracker)
                        <tr>
                            <td class="font-mono text-xs font-semibold">{{ $tracker->patient->no_rm }}</td>
                            <td class="font-medium">{{ $tracker->patient->nama_lengkap }}</td>
                            <td>
                                <form action="{{ route('hybrid-tracker.update', $tracker->id) }}" method="POST" class="flex items-center gap-2" id="form-rak-{{ $tracker->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nomor_rak" value="{{ $tracker->nomor_rak }}" class="form-input py-1 px-2 text-sm w-24" placeholder="Cth: R-01-A">
                                    <button type="submit" class="btn btn-secondary btn-sm py-1 px-2" title="Simpan Rak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('hybrid-tracker.update', $tracker->id) }}" method="POST" id="form-scan-{{ $tracker->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_scan" value="{{ $tracker->status_scan ? 0 : 1 }}">
                                    <div class="toggle-switch {{ $tracker->status_scan ? 'active' : '' }}" onclick="document.getElementById('form-scan-{{ $tracker->id }}').submit()"></div>
                                    <span class="text-xs mt-1 block {{ $tracker->status_scan ? 'text-emerald-primary' : 'text-text-muted' }}">{{ $tracker->status_scan ? 'Scanned' : 'Pending' }}</span>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('hybrid-tracker.update', $tracker->id) }}" method="POST" id="form-lengkap-{{ $tracker->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_lengkap" value="{{ $tracker->is_lengkap ? 0 : 1 }}">
                                    <div class="toggle-switch {{ $tracker->is_lengkap ? 'active' : '' }}" onclick="document.getElementById('form-lengkap-{{ $tracker->id }}').submit()"></div>
                                    <span class="text-xs mt-1 block {{ $tracker->is_lengkap ? 'text-emerald-primary' : 'text-text-muted' }}">{{ $tracker->is_lengkap ? 'Lengkap' : 'Incomplete' }}</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $trackers->links() }}
        </div>
    @else
        <div class="text-center py-10 text-text-muted">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-base font-medium">Belum ada data tracker</p>
            <p class="text-sm mt-1">Data akan muncul setelah pasien didaftarkan.</p>
        </div>
    @endif
</div>
@endsection
