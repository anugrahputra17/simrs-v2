@extends('layouts.app')

@section('title', 'Audit Trail — SYMPHONY SIMRS')
@section('page-title', 'System Audit Trail')
@section('page-subtitle', 'Modul 6 — Log Forensik & Keamanan')

@section('content')
<div class="card p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-crimson-light flex items-center justify-center">
            <svg class="w-5 h-5 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <div>
            <h3 class="font-semibold text-text-primary">Log Aktivitas Sistem</h3>
            <p class="text-xs text-text-muted">Merekam semua mutasi data dengan presisi microsecond</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-clean w-full">
            <thead>
                <tr>
                    <th>Timestamp (Microseconds)</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Target Table</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trails as $trail)
                    <tr>
                        <td class="font-mono text-xs text-text-secondary">
                            {{ $trail->created_at->format('Y-m-d H:i:s.u') }}
                        </td>
                        <td class="font-medium text-text-primary">
                            {{ $trail->user->username ?? 'System' }}
                        </td>
                        <td>
                            <span class="text-xs uppercase tracking-wider text-text-muted font-semibold">
                                {{ $trail->user->role ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $trail->action === 'CREATE' ? 'bg-emerald-light text-emerald-primary' : ($trail->action === 'UPDATE' ? 'bg-blue-light text-blue-info' : 'badge-danger') }}">
                                {{ $trail->action }}
                            </span>
                        </td>
                        <td class="font-mono text-xs text-purple-600 font-semibold">
                            {{ $trail->table_name }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-text-muted">Belum ada log aktivitas tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $trails->links() }}
    </div>
</div>
@endsection
