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
        Schema::table('sub_events', function (Blueprint $table) {
            if (!Schema::hasColumn('sub_events', 'is_registration_open')) {
                $table->boolean('is_registration_open')->default(true)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_events', function (Blueprint $table) {
            if (Schema::hasColumn('sub_events', 'is_registration_open')) {
                $table->dropColumn('is_registration_open');
            }
        });
    }
};
