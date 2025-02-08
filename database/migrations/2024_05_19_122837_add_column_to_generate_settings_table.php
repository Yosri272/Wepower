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
            $table->string('footer_text')->nullable();
            $table->string('footer_description')->nullable();
            $table->foreignId('footer_logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('footer_qrcode_id')->nullable()->constrained('media')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_text', 'footer_description', 'footer_logo', 'footer_qrcode']);
        });
    }
};
