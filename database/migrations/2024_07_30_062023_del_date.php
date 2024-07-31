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
        Schema::table(
            'details', function (Blueprint $table) {
                $table->after(
                    'desc', function (Blueprint $table) {
                        $table->date('start');
                        $table->date('end');
                    }
                );
            }
        );
        Schema::dropIfExists('date');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'details', function (Blueprint $table) {
                $table->dropColumn('start');
                $table->dropColumn('end');
            }
        );
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
    }
};
