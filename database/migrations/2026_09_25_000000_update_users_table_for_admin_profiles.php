<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 80)->after('id');
            $table->string('last_name', 80)->after('first_name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('status', 20)->default('active')->index()->after('password');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['status']);
            $table->dropColumn(['first_name', 'last_name', 'phone', 'status', 'last_login_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });
    }
};
