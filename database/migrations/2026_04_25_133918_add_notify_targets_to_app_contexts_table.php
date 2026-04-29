<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('app_contexts', function (Blueprint $table) {
            $table->json('notify_targets')->nullable()->after('notify_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_contexts', function (Blueprint $table) {
            $table->dropColumn('notify_targets');
        });
    }
};
