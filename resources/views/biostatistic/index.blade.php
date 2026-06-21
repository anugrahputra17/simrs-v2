@extends('layouts.app')

@section('title', 'Biostatistics — SYMPHONY SIMRS')
@section('page-title', 'Biostatistics Dashboard')
@section('page-subtitle', 'Modul 5 — Epidemiologi & KPI Direktur')

@section('content')
<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-semibold">Total Kunjungan</p>
                    <h3 class="text-2xl font-bold text-text-primary">{{ $totalMorbidity }} <span class="text-sm font-medium text-text-muted">pasien unik</span></h3>
                </div>
            </div>
            <p class="text-xs text-emerald-primary flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Morbidity count
            </p>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-semibold">Rata-rata Usia</p>
                    <h3 class="text-2xl font-bold text-text-primary">{{ $averageAge }} <span class="text-sm font-medium text-text-muted">tahun</span></h3>
                </div>
            </div>
            <p class="text-xs text-text-muted flex items-center gap-1">
                Arithmetic Mean pasien terdiagnosis
            </p>
        </div>

        <div class="stat-card">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-light flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-text-muted uppercase tracking-wider font-semibold">KLPCM Rate</p>
                    <h3 class="text-2xl font-bold {{ $klpcm >= 80 ? 'text-emerald-primary' : ($klpcm >= 50 ? 'text-amber-warn' : 'text-crimson') }}">{{ $klpcm }}%</h3>
                </div>
            </div>
            <p class="text-xs text-text-muted flex items-center gap-1">
                Kelengkapan Pengisian Rekam Medis
            </p>
        </div>
    </div>

    {{-- Chart Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pie Chart --}}
        <div class="card p-6">
            <h3 class="font-semibold text-text-primary mb-1">Distribusi Top 10 Diagnosis (ICD-10)</h3>
            <p class="text-xs text-text-muted mb-6">Proporsi penyakit berdasarkan mapping SNOMED CT</p>
            
            @if(count($chartLabels) > 0)
                <div class="w-full h-72 flex justify-center">
                    <canvas id="diagnosisChart"></canvas>
                </div>
            @else
                <div class="w-full h-72 flex flex-col items-center justify-center text-text-muted">
                    <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    <p>Belum ada data diagnosis terkode.</p>
                </div>
            @endif
        </div>

        {{-- Table Data --}}
        <div class="card p-6">
            <h3 class="font-semibold text-text-primary mb-4">Tabel Frekuensi Morbiditas</h3>
            
            @if(count($topDiagnoses) > 0)
                <div class="overflow-x-auto">
                    <table class="table-clean w-full">
                        <thead>
                            <tr>
                                <th>Kode ICD-10</th>
                                <th>Deskripsi Umum</th>
                                <th class="text-right">Total Kasus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topDiagnoses as $diag)
                                <tr>
                                    <td class="font-mono font-semibold text-blue-info">{{ $diag->icd10_mapped_code }}</td>
                                    <td>
                                        <span class="text-xs bg-warm-bg-alt px-2 py-1 rounded text-text-muted">Mapped from SNOMED</span>
                                    </td>
                                    <td class="text-right font-semibold">{{ $diag->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="w-full h-32 flex items-center justify-center text-text-muted">
                    <p class="text-sm">Tidak ada data untuk ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@if(count($chartLabels) > 0)
<script src="{{ asset('js/chart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('diagnosisChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: {!! json_encode($chartColors) !!},
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: "'Inter', sans-serif", size: 13 },
                        bodyFont: { family: "'Inter', sans-serif", size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' kasus';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
@endsection
