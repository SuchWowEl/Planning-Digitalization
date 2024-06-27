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
        Schema::create('aip', function (Blueprint $table) {
            $table->id();
            $table->year('year');
        });
        Schema::create('reference', function (Blueprint $table) {
            $table->id();
            $table->string('value');
        });
        Schema::create('ppa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aip_key')->constrained(
                table: 'aip', column: 'id'
            );
            $table->foreignId('sector')->constrained(
                table: 'reference', column: 'id'
            );
            $table->foreignId('subsector')->constrained(
                table: 'reference', column: 'id'
            );
            $table->foreignId('status')->constrained(
                table: 'reference', column: 'id'
            );
            $table->string('impl_office');
            $table->string('aip_ref_code');
            $table->string('mfo');
            $table->string('proponent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppa');
        Schema::dropIfExists('aip');
        Schema::dropIfExists('reference');
    }
};
