<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
          
            $table->string('profile_photo')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamp('last_login_at')->nullable();
     
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
