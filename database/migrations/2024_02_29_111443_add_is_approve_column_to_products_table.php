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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_approve')->default(0)->after('is_active');
            $table->boolean('is_featured')->default(0)->after('is_active');
            $table->boolean('is_new')->default(1)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_approve');
            $table->dropColumn('is_featured');
            $table->dropColumn('is_new');
        });
    }
};
