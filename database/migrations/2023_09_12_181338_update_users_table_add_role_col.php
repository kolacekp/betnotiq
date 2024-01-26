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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [\App\Models\Enums\Roles::Admin->value, \App\Models\Enums\Roles::Manager->value, \App\Models\Enums\Roles::User->value])->default(\App\Models\Enums\Roles::User->value)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
