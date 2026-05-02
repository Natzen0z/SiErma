<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\User;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $totalRisks = Risk::count();
        $highRisks = Risk::all()->filter(fn($r) => in_array($r->awal_level, ['Tinggi', 'Kritis']))->count();
        $completedRisks = Risk::where('status', 'Completed')->count();
        $recentRisks = Risk::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalRisks', 'highRisks', 'completedRisks', 'recentRisks'
        ));
    }

    /**
     * Users management page
     */
    public function users(): View
    {
        $users = User::withCount('risks')->orderBy('created_at', 'desc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        return view('admin.users', compact('users', 'units'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user,auditor',
            'unit' => 'nullable|string|max:255',
            'sub_unit' => 'nullable|string|max:255',
            'bidang' => 'nullable|string|max:255',
            'features' => 'nullable|array',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'password_plain' => $validated['password'],
            'role' => $validated['role'],
            'unit' => $validated['unit'] ?? null,
            'sub_unit' => $validated['sub_unit'] ?? null,
            'bidang' => $validated['bidang'] ?? null,
            'features' => $validated['features'] ?? ['dashboard', 'register', 'matrix', 'controls', 'assessment'],
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Update existing user
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,user,auditor',
            'unit' => 'nullable|string|max:255',
            'sub_unit' => 'nullable|string|max:255',
            'bidang' => 'nullable|string|max:255',
            'features' => 'nullable|array',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'unit' => $validated['unit'] ?? null,
            'sub_unit' => $validated['sub_unit'] ?? null,
            'bidang' => $validated['bidang'] ?? null,
            'features' => $validated['features'] ?? [],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
            $data['password_plain'] = $validated['password'];
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * All risks page
     */
    public function risks(): View
    {
        $risks = Risk::with('user')->orderBy('created_at', 'desc')->get();
        $units = Risk::distinct()->pluck('unit');
        return view('admin.risks', compact('risks', 'units'));
    }

    // ========================================
    // Unit Management
    // ========================================

    /**
     * Units management page
     */
    public function units(): View
    {
        $units = Unit::orderBy('name', 'asc')->get();
        return view('admin.units', compact('units'));
    }

    /**
     * Store new unit
     */
    public function storeUnit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'bidang' => 'required|string|max:255',
        ]);

        Unit::create($validated);

        return back()->with('success', 'Unit berhasil ditambahkan.');
    }

    /**
     * Delete unit
     */
    public function destroyUnit(Unit $unit): RedirectResponse
    {
        $unit->delete();
        return back()->with('success', 'Unit berhasil dihapus.');
    }

    // ========================================
    // Sub-Unit Management
    // ========================================

    /**
     * Sub-units management page
     */
    public function subUnits(): View
    {
        $subUnits = \App\Models\SubUnit::orderBy('unit_name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        return view('admin.sub_units', compact('subUnits', 'units'));
    }

    /**
     * Store new sub-unit
     */
    public function storeSubUnit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit_name' => 'required|string|max:255',
        ]);

        \App\Models\SubUnit::create($validated);

        return back()->with('success', 'Sub-Unit berhasil ditambahkan.');
    }

    /**
     * Delete sub-unit
     */
    public function destroySubUnit($id): RedirectResponse
    {
        \App\Models\SubUnit::destroy($id);
        return back()->with('success', 'Sub-Unit berhasil dihapus.');
    }

    // ========================================
    // Category Management
    // ========================================

    /**
     * Categories management page
     */
    public function categories(): View
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Delete category
     */
    public function destroyCategory(Category $category): RedirectResponse
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Annual Recap Dashboard
     */
    public function annualRecap(): View
    {
        $years = Risk::distinct()->pluck('period_year')->sort()->values();
        $risksByYear = Risk::all()->groupBy('period_year');

        $recapData = [];
        foreach ($years as $year) {
            $yearRisks = $risksByYear->get($year, collect());
            
            $recapData[$year] = [
                'total' => $yearRisks->count(),
                'levels' => [
                    'Kritis' => $yearRisks->filter(fn($r) => $r->awal_level === 'Kritis')->count(),
                    'Tinggi' => $yearRisks->filter(fn($r) => $r->awal_level === 'Tinggi')->count(),
                    'Sedang' => $yearRisks->filter(fn($r) => $r->awal_level === 'Sedang')->count(),
                    'Rendah' => $yearRisks->filter(fn($r) => $r->awal_level === 'Rendah')->count(),
                ],
                'statuses' => [
                    'Completed' => $yearRisks->where('status', 'Completed')->count(),
                    'In-Progress' => $yearRisks->where('status', 'In-Progress')->count(),
                    'Not Started' => $yearRisks->where('status', 'Not Started')->count(),
                ],
                'categories' => $yearRisks->groupBy('kategori')->map->count(),
            ];
        }

        return view('admin.annual-recap', compact('recapData', 'years'));
    }
}
