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
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('name');
            $table->string('logo_path')->nullable();            // path to logo file in storage
            $table->string('website_url')->nullable();
            $table->enum('tier', ['PLATINUM', 'GOLD', 'SILVER', 'BRONZE'])->default('SILVER');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
