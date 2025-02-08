<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\SupportTicket\App\Models\SupportTicket;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SupportTicket::class)->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->boolean('is_highlighted')->default(0);
            $table->longText('message');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_messages');
    }
};
