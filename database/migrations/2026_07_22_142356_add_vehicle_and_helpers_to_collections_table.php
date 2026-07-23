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
        Schema::table('collections', function (Blueprint $table) {
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('helper_id')->nullable()->constrained('helpers')->nullOnDelete();
            $table->foreignId('helper_2_id')->nullable()->constrained('helpers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign(['driver_user_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['helper_id']);
            $table->dropForeign(['helper_2_id']);
            
            $table->dropColumn(['driver_user_id', 'vehicle_id', 'helper_id', 'helper_2_id']);
        });
    }
};
