@extends('layouts.app')

@section('title', 'Dashboard — SYMPHONY SIMRS')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di SYMPHONY SIMRS v2.0')

@section('content')
<div class="space-y-8">
    {{-- Welcome Banner --}}
    <div class="card p-8 bg-gradient-to-r from-slate-header to-slate-dark text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 opacity-5">
            <svg viewBox="0 0 200 200" fill="currentColor"><path d="M100 0C44.8 0 0 44.8 0 100s44.8 100 100 100 100-44.8 100-100S155.2 0 100 0zm0 180c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/><path d="M100 40c-33.1 0-60 26.9-60 60s26.9 60 60 60 60-26.9 60-60-26.9-60-60-60zm0 100c-22.1 0-40-17.9-40-40s17.9-40 40-40 40 17.9 40 40-17.9 40-40 40z"/></svg>
        </div>
        <div class="relative">
            <p class="text-emerald-300 text-sm font-medium mb-1">{{ now()->format('l, d F Y') }}</p>
            <h3 class="text-2xl font-bold mb-2">Halo, {{ $user->username }}! 👋</h3>
            <p class="text-slate-300 text-sm max-w-lg">Anda login sebagai <span class="text-emerald-300 font-semibold uppercase">{{ $user->role }}</span>. Gunakan sidebar navigasi untuk mengakses modul yang tersedia.</p>
        </div>
    </div>

    {{-- Quick Access Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="/admission" class="stat-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-light flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">Admission Desk</h4>
                    <p class="text-xs text-text-muted">Pendaftaran pasien baru &amp; antrean</p>
                </div>
            </div>
        </a>

        <a href="/clinical" class="stat-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-light flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">Clinical Workstation</h4>
                    <p class="text-xs text-text-muted">Input SOAP &amp; tanda vital</p>
                </div>
            </div>
        </a>

        <a href="/hybrid-tracker" class="stat-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-light flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-warn" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">Hybrid Tracker</h4>
                    <p class="text-xs text-text-muted">Manajemen berkas rekam medis</p>
                </div>
            </div>
        </a>

        <a href="/biostatistic" class="stat-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">Biostatistics</h4>
                    <p class="text-xs text-text-muted">Dashboard statistik deskriptif</p>
                </div>
            </div>
        </a>

        <a href="/audit-trail" class="stat-card group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-crimson-light flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-text-primary">Audit Trail</h4>
                    <p class="text-xs text-text-muted">Log forensik keamanan sistem</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
