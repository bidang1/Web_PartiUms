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
        Schema::create('sub_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('name');
            $table->string('slug');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->json('pj_names')->nullable();          // stored as JSON ["Rizqi", "Tasya"]
            $table->json('htm_tiers')->nullable();          // stored as JSON [{"label":"Umum","price":50000}]
            $table->string('gform_link')->nullable();
            $table->foreignId('gform_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('gform_updated_at')->nullable();
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'CLOSED'])->default('DRAFT');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->unique(['year', 'slug']);
            $table->index(['year', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_events');
    }
};
