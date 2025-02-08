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
        Schema::table('generate_settings', function (Blueprint $table) {
            $table->string('currency')->nullable();
            $table->string('currency_position')->nullable();
            $table->string('direction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_settings', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('currency_position');
            $table->dropColumn('direction');
        });
    }
};
