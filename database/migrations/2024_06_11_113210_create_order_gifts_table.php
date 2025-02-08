<?php

use App\Models\Address;
use App\Models\Gift;
use App\Models\Order;
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
        Schema::create('order_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Gift::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Address::class)->nullable()->constrained()->nullOnDelete();
            $table->string('sender_name')->nullable();
            $table->string('receiver_name')->nullable();
            $table->float('price')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_orders');
    }
};
