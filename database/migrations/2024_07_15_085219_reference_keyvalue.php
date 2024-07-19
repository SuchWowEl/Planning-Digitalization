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
            'reference', function (Blueprint $table) {
                $table->after(
                    'id', function (Blueprint $table) {
                        $table->string('key');
                    }
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'reference', function (Blueprint $table) {
                $table->dropColumn('key');
            }
        );
    }
};
