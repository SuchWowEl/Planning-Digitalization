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
        Schema::create('ppa_component', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppa_id')->constrained(
                table: 'ppa', column: 'id'
            );
            $table->foreignId('ext_comp')->unique()->constrained(
                table: 'ext_fund', column: 'id'
            );
            $table->foreignId('cc_comp')->constrained(
                table: 'cc', column: 'id'
            );
            $table->foreignId('extra_gad')->constrained(
                table: 'extras', column: 'id'
            );
            $table->string('name');
        });
        Schema::create('ppa_output', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppa_comp')->constrained(
                table: 'ppa_component', column: 'id'
            );
            $table->foreignId('oe_acode')->constrained(
                table: 'reference', column: 'id'
            );
            $table->foreignId('source_of_fund')->constrained(
                table: 'reference', column: 'id'
            );
            $table->string('res_inputs');
            $table->string('location');
            $table->decimal('ps', total: 11, places: 2);
            $table->decimal('mooe', total: 11, places: 2);
            $table->decimal('co', total: 11, places: 2);
            $table->string('output');
            $table->string('r_office');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppa_output');
        Schema::dropIfExists('ppa_component');
    }
};
