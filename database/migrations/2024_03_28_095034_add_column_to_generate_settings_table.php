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
            $table->string('primary_color')->default('#8b5cf6');
            $table->string('secondary_color')->default('#ede9fe');
            $table->float('commission')->default(10);
            $table->string('commission_type')->nullable()->default('percentage');
            $table->string('commission_charge')->default('per_order');
            $table->boolean('shop_pos')->default(1);
            $table->boolean('shop_register')->default(1);
            $table->boolean('new_product_approval')->default(1);
            $table->boolean('update_product_approval')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_settings', function (Blueprint $table) {
            $table->dropColumn('primary_color');
            $table->dropColumn('secondary_color');
            $table->dropColumn('commission');
            $table->dropColumn('commission_type');
            $table->dropColumn('commission_charge');
            $table->dropColumn('shop_pos');
            $table->dropColumn('shop_register');
            $table->dropColumn('new_product_approval');
            $table->dropColumn('update_product_approval');
        });
    }
};
