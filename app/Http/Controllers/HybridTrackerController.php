<?php

namespace App\Http\Controllers;

use App\Models\HybridTracker;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class HybridTrackerController extends Controller
{
    public function index()
    {
        $trackers = HybridTracker::with('patient')->orderBy('created_at', 'desc')->paginate(15);
        return view('hybrid-tracker.index', compact('trackers'));
    }

    public function update(Request $request, $id)
    {
        $tracker = HybridTracker::findOrFail($id);

        $request->validate([
            'nomor_rak' => 'nullable|string|max:50',
            'status_scan' => 'nullable|boolean',
            'is_lengkap' => 'nullable|boolean',
        ]);

        $tracker->update([
            'nomor_rak' => $request->input('nomor_rak', $tracker->nomor_rak),
            'status_scan' => $request->has('status_scan') ? (bool) $request->status_scan : $tracker->status_scan,
            'is_lengkap' => $request->has('is_lengkap') ? (bool) $request->is_lengkap : $tracker->is_lengkap,
        ]);

        AuditTrail::log('UPDATE', 'hybrid_trackers');

        return redirect('/hybrid-tracker')->with('success', 'Status berkas berhasil diperbarui.');
    }
}
