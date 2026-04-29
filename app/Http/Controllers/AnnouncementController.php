<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isUnitAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'bidang' => 'nullable|string',
            'target_units' => 'nullable|array',
            'target_users' => 'nullable|array',
            'duration' => 'required|string|in:1_week,1_month,always',
        ]);

        // Restriction: can only target their own bidang if not super admin
        if (!$user->isAdmin() && $user->isUnitAdmin()) {
             $validated['bidang'] = $user->bidang;
        }

        $expiresAt = null;
        if ($validated['duration'] === '1_week') {
            $expiresAt = now()->addWeek();
        } elseif ($validated['duration'] === '1_month') {
            $expiresAt = now()->addMonth();
        }

        $announcement = Announcement::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'bidang' => $validated['bidang'] ?? null,
            'target_units' => $validated['target_units'] ?? null,
            'target_users' => $validated['target_users'] ?? null,
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat.',
            'announcement' => $announcement->load('user'),
        ]);
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $user = Auth::user();

        if ($announcement->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $announcement->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diperbarui.',
            'announcement' => $announcement->fresh('user'),
        ]);
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $user = Auth::user();

        if ($announcement->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Get active announcements for the current user.
     */
    public function active()
    {
        $user = Auth::user();
        
        $query = Announcement::with('user')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });

        // Filtering logic based on user's bidang/unit
        $query->where(function ($q) use ($user) {
            // Global announcements (no target bidang, units, or users)
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
            // Targeted to user's bidang
            ->orWhere('bidang', $user->bidang)
            // Targeted to user's unit (SQLite-friendly LIKE check)
            ->orWhere('target_units', 'LIKE', '%"' . $user->unit . '"%')
            // Targeted specifically to this user
            ->orWhere('target_users', 'LIKE', '%"' . $user->name . '"%');
        });

        return response()->json($query->latest()->get());
    }
}
