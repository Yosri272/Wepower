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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('vehicle_type')->nullable()->after('remember_token');
            $table->string('driving_lience')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new User())->getTable(), function (Blueprint $table) {
            $table->dropColumn('vehicle_type', 'driving_lience', 'last_name');
        });
    }
};
