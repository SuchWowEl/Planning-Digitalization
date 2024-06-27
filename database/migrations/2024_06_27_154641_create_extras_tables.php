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
        Schema::create('extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppa')->constrained(
                table: 'ppa', column: 'id'
            );
            $table->string('risks');
            $table->string('support');
            $table->string('response');
        });
        Schema::create('ext_fund', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra')->unique()->constrained(
                table: 'extras', column: 'id'
            );
            $table->string('donor');
            $table->string('response');
        });
        Schema::create('cc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra')->unique()->constrained(
                table: 'extras', column: 'id'
            );
            $table->string('obj');
            $table->string('info');
            $table->string('topology');
            $table->string('risks');
            $table->string('expenditure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc');
        Schema::dropIfExists('ext_fund');
        Schema::dropIfExists('extras');
    }
};
