<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AppContext;

class AppContextController extends Controller
{
    /**
     * Store or update the app context for a year.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isAdmin() && !$user->isWadir()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'year' => 'required|integer',
            'bidang' => 'nullable|string',
            'urusan' => 'required|string',
            'opd' => 'required|string',
            'sasaran' => 'required|string',
            'indikator' => 'required|string',
            'notify_days' => 'nullable|integer',
            'notify_targets' => 'nullable|array',
        ]);

        if ($user->isWadir() && !$user->isAdmin()) {
            $validated['bidang'] = $user->bidang; // Enforce their own bidang
        }

        $data = $validated;
        unset($data['notify_days']);

        if (!empty($request->notify_days)) {
            $days = (int) $request->notify_days;
            if ($days === -1) {
                $data['notify_until'] = now()->addYears(100);
            } else {
                $data['notify_until'] = now()->addDays($days);
            }
        }
        
        if (isset($validated['notify_targets'])) {
            $data['notify_targets'] = $validated['notify_targets'];
        }

        $context = AppContext::updateOrCreate(
            ['year' => $validated['year'], 'bidang' => $validated['bidang'] ?? null],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Konteks dan Ruang Lingkup berhasil disimpan.',
            'context' => $context,
        ]);
    }

    /**
     * Get the app context for a year.
     */
    public function show($year)
    {
        $context = AppContext::where('year', $year)->first();
        return response()->json($context);
    }
}
