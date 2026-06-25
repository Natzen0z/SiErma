<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Unit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = User::whereNotNull('unit')->where('unit', '!=', '')->get();

        foreach ($users as $user) {
            $unitModel = Unit::where('name', $user->unit)->first();
            if ($unitModel) {
                // Attach without detaching to avoid wiping existing pivot data (if any)
                $user->units()->syncWithoutDetaching([$unitModel->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, we could clear the pivot table, but we don't want to lose new data.
        // It's safer to leave this empty or explicitly detach units that match the user's legacy unit.
    }
};
