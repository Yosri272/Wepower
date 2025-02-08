<?php

use App\Models\Brand;
use App\Models\Media;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Shop::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Media::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();
            $table->float('price');
            $table->integer('quantity')->default(0);
            $table->float('discount_price')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
