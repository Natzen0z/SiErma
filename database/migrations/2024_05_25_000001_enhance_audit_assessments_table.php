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
        Schema::table('audit_assessments', function (Blueprint $table) {
            $table->json('self_answers')->nullable()->after('answers');
            $table->string('self_status')->default('Draft')->after('self_answers'); // Draft, Submitted
            $table->string('triwulan')->nullable()->after('period_year');
            $table->text('auditor_notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_assessments', function (Blueprint $table) {
            $table->dropColumn(['self_answers', 'self_status', 'triwulan', 'auditor_notes']);
        });
    }
};
