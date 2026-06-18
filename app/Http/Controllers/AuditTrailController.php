<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;

class AuditTrailController extends Controller
{
    public function index()
    {
        $trails = AuditTrail::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('audit-trail.index', compact('trails'));
    }
}
