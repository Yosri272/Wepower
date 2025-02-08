<?php

use App\Models\Address;
use App\Models\Gift;
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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignIdFor(Gift::class)->nullable()->after('unit')->constrained()->nullOnDelete();
            $table->foreignIdFor(Address::class)->nullable()->after('gift_id')->constrained()->nullOnDelete();
            $table->string('gift_sender_name')->nullable()->after('address_id');
            $table->string('gift_receiver_name')->nullable()->after('gift_sender_name');
            $table->string('gift_note')->nullable()->after('gift_receiver_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeignIdFor(Gift::class);
            $table->dropForeignIdFor(Address::class);
            $table->dropColumn('gift_sender_name');
            $table->dropColumn('gift_receiver_name');
            $table->dropColumn('gift_note');
        });
    }
};
