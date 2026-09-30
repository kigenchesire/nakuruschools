<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Powers the "Academic Journey" pathway (PP1 -> Grade 12) so grades
        // can be activated/relabeled without touching Blade.
        Schema::create('academic_stages', function (Blueprint $table) {
            $table->id();
            $table->string('phase'); // Early Years, Primary School, Junior School, Senior School
            $table->string('label'); // PP1, Grade 1, Grade 11...
            $table->string('status')->default('active'); // active, launching_soon
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_stages');
    }
};
