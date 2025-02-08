<?php

use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new DriverOrder())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Driver::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(0);
            $table->string('assign_for')->nullable();
            $table->boolean('is_accept')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new DriverOrder())->getTable());
    }
};
