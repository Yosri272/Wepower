<?php

use App\Models\User;
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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignId('logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('banner_id')->nullable()->constrained('media')->nullOnDelete();
            $table->float('delivery_charge')->nullable()->default(0);
            $table->float('min_order_amount')->default(0);
            $table->string('prefix')->default('RC');
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->integer('estimated_delivery_time')->nullable();
            $table->boolean('status')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
