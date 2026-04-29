<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditorController extends Controller
{
    /**
     * Display the auditor dashboard
     */
    public function dashboard(): View
    {
        // Get all risks
        $risks = Risk::with('user', 'mitigations')->orderBy('created_at', 'desc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        $context = \App\Models\AppContext::where('year', date('Y'))->first();
        
        $assessments = \App\Models\AuditAssessment::with('auditor')->orderBy('created_at', 'desc')->get();

        return view('auditor.dashboard', [
            'risks' => $risks,
            'units' => $units,
            'context' => $context,
            'assessments' => $assessments
        ]);
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required|string',
            'period_year' => 'required|string',
            'triwulan' => 'nullable|string',
            'audit_date' => 'nullable|date',
            'answers' => 'nullable|array',
            'self_answers' => 'nullable|array',
            'self_status' => 'nullable|string',
            'status' => 'nullable|string',
            'auditor_notes' => 'nullable|string'
        ]);

        // Find or create the assessment record
        $assessment = \App\Models\AuditAssessment::firstOrNew([
            'unit' => $validated['unit'],
            'period_year' => $validated['period_year'],
            'triwulan' => $validated['triwulan'] ?? null,
        ]);

        // Selectively update only the fields present in the request
        // This prevents a unit user from wiping auditor data and vice versa
        if ($request->has('self_answers')) {
            $assessment->self_answers = $validated['self_answers'];
        }
        if ($request->has('self_status')) {
            $assessment->self_status = $validated['self_status'];
        }
        if ($request->has('answers')) {
            $assessment->answers = $validated['answers'];
        }
        if ($request->has('status')) {
            $assessment->status = $validated['status'];
        }
        if ($request->has('auditor_notes')) {
            $assessment->auditor_notes = $validated['auditor_notes'];
        }

        // Set auditor_id only when an auditor is saving
        if (auth()->user()->isAuditor()) {
            $assessment->auditor_id = auth()->id();
        }

        $assessment->audit_date = $validated['audit_date'] ?? date('Y-m-d');
        $assessment->save();

        return response()->json([
            'success' => true,
            'message' => 'Data assessment berhasil diperbarui.',
            'assessment' => $assessment->fresh()->load('auditor')
        ]);
    }

    public function printAssessment($id)
    {
        $assessment = \App\Models\AuditAssessment::with('auditor')->findOrFail($id);
        return view('auditor.print-assessment', compact('assessment'));
    }
}
