<?php

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Customer;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Shop::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->string('order_code');
            $table->string('prefix')->nullable();
            $table->foreignIdFor(Coupon::class)->nullable()->constrained()->nullOnDelete();
            $table->float('coupon_discount')->nullable();
            $table->timestamp('pick_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->float('payable_amount');
            $table->float('total_amount');
            $table->float('discount')->nullable()->default(0);
            $table->float('delivery_charge')->default(0);
            $table->string('payment_status');
            $table->string('order_status');
            $table->string('payment_method')->nullable();
            $table->foreignIdFor(Address::class)->nullable()->constrained()->nullOnDelete();
            $table->longText('instruction')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
