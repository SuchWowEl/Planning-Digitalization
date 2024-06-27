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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppa')->unique()->constrained(
                table: 'ppa', column: 'id'
            );
            $table->string('title');
            $table->string('location');
            $table->string('justification');
            $table->string('objective');
            $table->string('desc');
        });
        Schema::create('date', function (Blueprint $table) {
            $table->foreignId('details_id')->constrained(
                table: 'details', column: 'id'
            );
            $table->boolean('ends');
            $table->year('year');
            $table->smallInteger('month');
            $table->smallInteger('day');

            $table->primary(['details_id','ends']);
        });
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('details_id')->constrained(
                table: 'details', column: 'id'
            );
            $table->integer('male');
            $table->integer('female');
            $table->string('type');
        });
        Schema::create('indicators', function (Blueprint $table) {
            $table->foreignId('details_id')->constrained(
                table: 'details', column: 'id'
            );
            $table->string('goal');

            $table->primary(['details_id','goal']);
        });
        Schema::create('details_funds', function (Blueprint $table) {
            $table->foreignId('details_id')->constrained(
                table: 'details', column: 'id'
            );
            $table->foreignId('fund_id')->constrained(
                table: 'reference', column: 'id'
            );

            $table->primary(['details_id','fund_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_funds');
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('date');
        Schema::dropIfExists('details');
    }
};
