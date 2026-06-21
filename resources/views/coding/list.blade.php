@extends('layouts.app')

@section('title', 'Medical Coding Worklist — SYMPHONY SIMRS')
@section('page-title', 'Medical Coding Dashboard')
@section('page-subtitle', 'Daftar Pasien Menunggu Proses Coding (ICD-10 & SNOMED CT)')

@section('content')
<div class="card p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div>
                <h3 class="font-semibold text-text-primary">Worklist Antrean Coding</h3>
                <p class="text-xs text-text-muted">Data berasal dari Clinical Workstation yang telah melengkapi resume medis.</p>
            </div>
        </div>
    </div>

    @if($medicalRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="table-clean w-full">
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Penjamin</th>
                        <th>Poliklinik Asal</th>
                        <th>Waktu Layanan</th>
                        <th>Status Coding</th>
                        <th class="w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalRecords as $record)
                        @php
                            $isDone = $record->status_coding === 'done';
                        @endphp
                        <tr>
                            <td class="font-mono text-xs font-semibold">{{ $record->registration->patient->no_rm }}</td>
                            <td class="font-medium">{{ $record->registration->patient->nama_lengkap }}</td>
                            <td>
                                <span class="badge {{ $record->registration->penjamin === 'BPJS' ? 'bg-emerald-light text-emerald-primary' : 'bg-blue-light text-blue-info' }}">
                                    {{ $record->registration->penjamin }}
                                </span>
                            </td>
                            <td>{{ $record->registration->klinik_tujuan }}</td>
                            <td class="text-xs text-text-muted">{{ $record->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($isDone)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-warn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('coding.index', $record->id) }}" class="btn {{ $isDone ? 'btn-secondary' : 'btn-primary' }} btn-sm w-full justify-center">
                                    {{ $isDone ? 'Lihat' : 'Proses Coding' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $medicalRecords->links() }}
        </div>
    @else
        <div class="text-center py-10 text-text-muted">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-base font-medium">Belum ada antrean coding</p>
            <p class="text-sm mt-1">Data akan muncul setelah dokter menyelesaikan pemeriksaan (Clinical Workstation).</p>
        </div>
    @endif
</div>
@endsection
