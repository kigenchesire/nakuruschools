<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "Why Nakuru Schools" feature cards + "School Life" preview cards.
        Schema::create('feature_highlights', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['why_us', 'school_life'])->index();
            $table->string('title');
            $table->string('icon')->nullable(); // heroicon / svg key
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_highlights');
    }
};
