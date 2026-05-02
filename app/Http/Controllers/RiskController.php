<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RiskController extends Controller
{
    /**
     * Display the main risk management view
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect Auditor strictly to their own dashboard
        if ($user->isAuditor()) {
            return redirect()->route('auditor.dashboard');
        }
        
        // Super Admin or Direktur see all risks
        if ($user->isAdmin() || $user->email === 'direktur@rsudmurjani.id') {
            if ($user->isWadir()) {
                $allowedUnits = \App\Models\User::where('bidang', $user->bidang)->pluck('name')->toArray();
                $risks = Risk::with('mitigations')
                    ->where(function($q) use ($allowedUnits, $user) {
                        $q->whereIn('unit', $allowedUnits)
                          ->orWhereJsonContains('shared_with', $user->name);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $risks = Risk::with('mitigations')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
        // Others see risks within their "bidang" so they can view the "gabungan" tab
        else {
            $risks = Risk::with('mitigations')
                ->where(function($query) use ($user) {
                    if ($user->bidang) {
                        $query->where('bidang', $user->bidang)
                              ->orWhereJsonContains('shared_with', $user->name);
                    } elseif ($user->unit) {
                        $query->where('unit', $user->unit)
                              ->orWhereJsonContains('shared_with', $user->name);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Fetch strategic contexts
        $contexts = \App\Models\AppContext::where('year', date('Y'))
            ->where(function($q) use ($user) {
                $q->where('bidang', $user->bidang)
                  ->orWhereNull('bidang');
            })
            ->orderByRaw('CASE WHEN bidang IS NULL THEN 1 ELSE 0 END')
            ->get();

        // Fetch regular announcements with SQLite-friendly queries
        $dbAnnouncements = \App\Models\Announcement::with('user')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) use ($user) {
                // Global announcements
                $q->where(function ($sq) {
                    $sq->where(function($ssq) {
                        $ssq->whereNull('bidang')->orWhere('bidang', '')->orWhere('bidang', 'Global');
                    })
                    ->where(function($ssq) {
                        $ssq->whereNull('target_units')->orWhere('target_units', '[]')->orWhere('target_units', '');
                    })
                    ->where(function($ssq) {
                        $ssq->whereNull('target_users')->orWhere('target_users', '[]')->orWhere('target_users', '');
                    });
                })
                // Targeted checks (SQLite-friendly)
                ->orWhere('bidang', $user->bidang)
                ->orWhere('target_units', 'LIKE', '%"' . $user->unit . '"%')
                ->orWhere('target_users', 'LIKE', '%"' . $user->name . '"%');
            })
            ->latest()
            ->get();

        $subUnits = \App\Models\SubUnit::orderBy('name', 'asc')->get();

        // Fetch Assessments
        if ($user->isAdmin() || $user->email === 'direktur@rsudmurjani.id') {
            if ($user->isWadir()) {
                $allowedUnits = \App\Models\User::where('bidang', $user->bidang)->pluck('name')->toArray();
                $assessments = \App\Models\AuditAssessment::whereIn('unit', $allowedUnits)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $assessments = \App\Models\AuditAssessment::orderBy('created_at', 'desc')->get();
            }
        } else {
            // Unit users see all their own assessments (Draft, Submitted, Final)
            $assessments = \App\Models\AuditAssessment::where('unit', $user->unit)
                ->orderBy('created_at', 'desc')->get();
        }
        
        $users = \App\Models\User::orderBy('name', 'asc')->get();

        // Merge strategic contexts and database announcements into a unified structure
        $announcements = collect();

        // 1. Add Strategic Contexts
        foreach ($contexts as $ctx) {
            $announcements->push((object)[
                'id' => 'context_' . $ctx->id,
                'title' => 'SASARAN STRATEGIS: ' . ($ctx->bidang ?? 'DIREKTUR'),
                'message' => $ctx->sasaran . "\n\nINDIKATOR:\n" . $ctx->indikator,
                'sasaran' => $ctx->sasaran,
                'indikator' => $ctx->indikator,
                'bidang' => $ctx->bidang,
                'is_context' => true,
                'created_at' => $ctx->created_at ? $ctx->created_at->toISOString() : now()->toISOString(),
                'notify_until' => $ctx->notify_until,
                'notify_targets' => $ctx->notify_targets,
                'user' => (object)['name' => 'System']
            ]);
        }

        // 2. Add Regular Announcements
        foreach ($dbAnnouncements as $a) {
            $announcements->push((object)[
                'id' => (string)$a->id,
                'title' => $a->title,
                'message' => $a->message,
                'sasaran' => null,
                'indikator' => null,
                'bidang' => $a->bidang,
                'is_context' => false,
                'created_at' => $a->created_at->toISOString(),
                'expires_at' => $a->expires_at,
                'user' => $a->user
            ]);
        }

        $announcements = $announcements->sortByDesc('created_at')->values();

        $myAnnouncements = [];
        if ($user->isAdmin() || $user->isUnitAdmin()) {
            $myAnnouncements = \App\Models\Announcement::where('user_id', $user->id)->latest()->get();
        }

        return view('risk', [
            'risks' => $risks,
            'units' => $units,
            'subUnits' => $subUnits,
            'categories' => $categories,
            'contexts' => $contexts,
            'context' => $contexts->first(),
            'assessments' => $assessments,
            'users' => $users,
            'announcements' => $announcements,
            'myAnnouncements' => $myAnnouncements,
        ]);
    }

    /**
     * Store a new risk
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->email === 'direktur@rsudmurjani.id' || $user->isAuditor()) {
            return response()->json(['success' => false, 'message' => 'Auditor / Direktur hanya memiliki akses baca.'], 403);
        }
        
        // Unit admin can only create risks for their unit
        if ($user->isUnitAdmin()) {
            $request->merge(['unit' => $user->unit]);
        }
        
        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'sub_unit' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:255',
            'risiko' => 'required|string',
            'dampak_deskripsi' => 'nullable|string',
            'penyebab' => 'nullable|string',
            'awal_d' => 'required|integer|min:1|max:5',
            'awal_p' => 'required|integer|min:1|max:5',
            'pengendalian' => 'nullable|string',
            'mitigations' => 'required|array|min:1',
            'mitigations.*.treatment' => 'required|string',
            'mitigations.*.status' => 'required|in:Not Started,In-Progress,Completed',
            'mitigations.*.evidence_link' => 'required_if:mitigations.*.status,Completed',
            'evaluasi' => 'nullable|string|max:255',
            'shared_with' => 'nullable|array',
            'escalated_to' => 'nullable|string|max:255',
            'residual_d' => 'required|integer|min:1|max:5',
            'residual_p' => 'required|integer|min:1|max:5',
            'pj' => 'nullable|string|max:255',
            'status' => 'required|in:Not Started,In-Progress,Completed',
            'triwulan' => 'nullable|string|max:255',
            'period_year' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'tanggal' => 'nullable|date',
        ]);
        
        $validated['shared_with'] = $validated['shared_with'] ?? [];

        // Dynamic Sub-Unit Creation
        $subUnit = null;
        if (!empty($validated['sub_unit']) && !empty($validated['unit'])) {
            $subUnit = \App\Models\SubUnit::firstOrCreate([
                'name' => $validated['sub_unit'],
                'unit_name' => $validated['unit']
            ]);
        }

        $validated['kode'] = Risk::generateNextKode();
        $validated['validasi'] = 'Menunggu';
        $validated['user_id'] = Auth::id();
        $validated['bidang'] = $user->bidang; // Auto-set bidang from user
        $validated['created_by_name'] = $user->name; // Audit trail
        
        $risk = Risk::create($validated);

        if (!empty($validated['mitigations'])) {
            foreach ($validated['mitigations'] as $mit) {
                $risk->mitigations()->create($mit);
            }
        } elseif ($request->has('pengendalian') && $request->pengendalian) {
            $risk->mitigations()->create([
                'treatment' => $request->pengendalian,
                'status' => $request->status,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil ditambahkan',
            'risk' => $risk->load('mitigations'),
            'sub_unit' => $subUnit,
        ]);
    }

    /**
     * Update an existing risk (for validation, status, etc.)
     */
    public function update(Request $request, Risk $risk): JsonResponse
    {
        $user = Auth::user();
        
        // Direktur or Auditor cannot edit anything
        if ($user->email === 'direktur@rsudmurjani.id' || $user->isAuditor()) {
            return response()->json(['success' => false, 'message' => 'Auditor / Direktur hanya memiliki akses baca.'], 403);
        }

        // Check access: Super admin or owner can update
        if (!$user->isAdmin() && $user->unit !== $risk->unit && $user->bidang !== $risk->bidang) {
            return response()->json(['success' => false, 'message' => 'Unauthorized: Anda tidak memiliki akses ke data unit ini.'], 403);
        }

        $validated = $request->validate([
            'unit' => 'sometimes|required|string|max:255',
            'sub_unit' => 'nullable|string|max:255',
            'kategori' => 'sometimes|required|string|max:255',
            'risiko' => 'sometimes|required|string',
            'dampak_deskripsi' => 'nullable|string',
            'penyebab' => 'nullable|string',
            'awal_d' => 'sometimes|required|integer|min:1|max:5',
            'awal_p' => 'sometimes|required|integer|min:1|max:5',
            'pengendalian' => 'nullable|string',
            'evaluasi' => 'nullable|string|max:255',
            'shared_with' => 'nullable|array',
            'escalated_to' => 'nullable|string|max:255',
            'residual_d' => 'sometimes|required|integer|min:1|max:5',
            'residual_p' => 'sometimes|required|integer|min:1|max:5',
            'pj' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:Not Started,In-Progress,Completed',
            'validasi' => 'sometimes|required|in:Menunggu,Valid,Revisi',
            'validator' => 'nullable|string|max:255',
            'triwulan' => 'nullable|string|max:255',
            'period_year' => 'nullable|integer',
            'is_active' => 'sometimes|required|boolean',
            'tanggal' => 'nullable|date',
            'mitigations' => 'sometimes|required|array|min:1',
            'mitigations.*.id' => 'sometimes|integer|exists:mitigations,id',
            'mitigations.*.treatment' => 'required|string',
            'mitigations.*.status' => 'required|in:Not Started,In-Progress,Completed',
            'mitigations.*.evidence_link' => 'required_if:mitigations.*.status,Completed',
        ]);

        if (isset($validated['shared_with'])) {
             $validated['shared_with'] = $validated['shared_with'] ?? [];
        }

        // Only Admin role (Super Admin or Unit Admin) can update validasi and validator
        if (!$user->isAdmin() && !$user->isUnitAdmin()) {
            if (isset($validated['validasi'])) unset($validated['validasi']);
            if (isset($validated['validator'])) unset($validated['validator']);
        }

        $validated['updated_by_name'] = $user->name; // Audit trail
        // Dynamic Sub-Unit Creation
        $subUnit = null;
        if (!empty($validated['sub_unit']) && !empty($validated['unit'])) {
            $subUnit = \App\Models\SubUnit::firstOrCreate([
                'name' => $validated['sub_unit'],
                'unit_name' => $validated['unit']
            ]);
        }

        $risk->update($validated);

        if (isset($validated['mitigations'])) {
            $mitIds = [];
            foreach ($validated['mitigations'] as $mit) {
                if (isset($mit['id'])) {
                    $m = \App\Models\Mitigation::find($mit['id']);
                    $m->update($mit);
                    $mitIds[] = $m->id;
                } else {
                    $m = $risk->mitigations()->create($mit);
                    $mitIds[] = $m->id;
                }
            }
            // Optional: delete mitigations not in the list
            $risk->mitigations()->whereNotIn('id', $mitIds)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil diperbarui',
            'risk' => $risk->load('mitigations')->fresh(),
            'sub_unit' => $subUnit,
        ]);
    }

    /**
     * Delete a risk
     */
    public function destroy(Risk $risk): JsonResponse
    {
        $user = Auth::user();
        
        // Direktur or Auditor cannot delete anything
        if ($user->email === 'direktur@rsudmurjani.id' || $user->isAuditor()) {
            return response()->json(['success' => false, 'message' => 'Auditor / Direktur hanya memiliki akses baca.'], 403);
        }

        // Check access: Super admin or owner can delete
        if (!$user->isAdmin() && $user->unit !== $risk->unit && $user->bidang !== $risk->bidang) {
            return response()->json(['success' => false, 'message' => 'Unauthorized: Anda tidak memiliki akses ke data unit ini.'], 403);
        }

        $risk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Risiko berhasil dihapus',
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();
        
        // Super Admin, Direktur, or Auditor see statistics for all
        if ($user->isAdmin() || $user->email === 'direktur@rsudmurjani.id' || $user->isAuditor()) {
            $risks = Risk::all();
        }
        else {
            $risks = Risk::where(function($query) use ($user) {
                    if ($user->bidang) {
                        $query->where('bidang', $user->bidang)
                              ->orWhereJsonContains('shared_with', $user->name);
                    } elseif ($user->unit) {
                        $query->where('unit', $user->unit)
                              ->orWhereJsonContains('shared_with', $user->name);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                })->get();
        }
        
        $total = $risks->count();
        $highRisk = $risks->filter(fn($r) => in_array($r->awal_level, ['Tinggi', 'Kritis']))->count();
        $completed = $risks->where('status', 'Completed')->count();
        $inProgress = $risks->where('status', 'In-Progress')->count();
        
        // Category counts
        $categoryStats = $risks->groupBy('kategori')->map->count();
        
        return response()->json([
            'total' => $total,
            'highRisk' => $highRisk,
            'completed' => $completed,
            'inProgress' => $inProgress,
            'categoryStats' => $categoryStats,
        ]);
    }
}
