<?php

use App\Models\Coupon;
use App\Models\Shop;
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
        Schema::create('admin_coupons', function (Blueprint $table) {
            $table->foreignIdFor(Coupon::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Shop::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_coupons');
    }
};
