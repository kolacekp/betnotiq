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
        Schema::table('bets', function (Blueprint $table) {
            $table->string('type', 1)->default('B')->after('user_id');
            $table->string('cashout_ticket', 100)->nullable()->default(null)->after('fixed_value');
            $table->string('cashout_reason', 100)->nullable()->default(null)->after('cashout_ticket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('cashout_ticket');
            $table->dropColumn('cashout_reason');
        });
    }
};
