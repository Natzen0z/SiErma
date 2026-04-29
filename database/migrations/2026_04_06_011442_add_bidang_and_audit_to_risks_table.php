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
        Schema::table('risks', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('kategori');
            $table->string('created_by_name')->nullable()->after('period_year');
            $table->string('updated_by_name')->nullable()->after('created_by_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn(['bidang', 'created_by_name', 'updated_by_name']);
        });
    }
};
