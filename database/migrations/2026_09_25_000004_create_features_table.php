<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Homepage "Why Choose Us" cards (group = why_choose_us) and
        // key-figure highlights (group = highlight, which also uses `value`).
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('group', 30)->default('why_choose_us');
            $table->string('title');
            $table->string('value', 30)->nullable();
            $table->text('description')->nullable();
            $table->string('icon', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
