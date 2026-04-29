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
        Schema::create('mitigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_id')->constrained()->onDelete('cascade');
            $table->text('treatment');
            $table->enum('status', ['Not Started', 'In-Progress', 'Completed'])->default('Not Started');
            $table->timestamps();
        });

        // Data migration: move existing 'pengendalian' to 'mitigations'
        $risks = DB::table('risks')->whereNotNull('pengendalian')->get();
        foreach ($risks as $risk) {
            DB::table('mitigations')->insert([
                'risk_id' => $risk->id,
                'treatment' => $risk->pengendalian,
                'status' => $risk->status, // Use overall risk status for initial migration
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitigations');
    }
};
