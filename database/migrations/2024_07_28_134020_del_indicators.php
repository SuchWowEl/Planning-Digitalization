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
                        $table->json('indicators');
                    }
                );
            }
        );
        Schema::dropIfExists('indicators');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'details', function (Blueprint $table) {
                $table->dropColumn('indicators');
            }
        );
        Schema::create('indicators', function (Blueprint $table) {
            $table->foreignId('details_id')->constrained(
                table: 'details', column: 'id'
            );
            $table->string('goal');

            $table->primary(['details_id','goal']);
        });
    }
};
