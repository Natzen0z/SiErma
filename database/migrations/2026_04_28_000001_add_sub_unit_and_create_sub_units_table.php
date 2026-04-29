<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sub_unit')->nullable()->after('unit');
        });

        Schema::table('risks', function (Blueprint $table) {
            $table->string('sub_unit')->nullable()->after('unit');
        });

        Schema::create('sub_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit_name'); // We link by name as requested by the current system pattern
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_units');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sub_unit');
        });

        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn('sub_unit');
        });
    }
};
