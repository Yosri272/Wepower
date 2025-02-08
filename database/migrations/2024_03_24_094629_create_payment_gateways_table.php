<?php

use App\Models\Media;
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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->foreignIdFor(Media::class)->nullable()->constrained()->nullOnDelete();
            $table->json('config')->nullable();
            $table->string('mode')->default('test')->comment('test or live');
            $table->boolean('is_active')->default(false);
            $table->string('alias')->nullable()->comment('controller nameSpace');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
