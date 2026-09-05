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
            $table->string('type')->default('OFFLINE'); // ONLINE, OFFLINE, HYBRID
            $table->string('location')->nullable();      // e.g. Zoom Meeting, Aula UMS, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_events', function (Blueprint $table) {
            $table->dropColumn(['type', 'location']);
        });
    }
};
