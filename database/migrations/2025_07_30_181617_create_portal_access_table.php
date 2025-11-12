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
        Schema::create('portal_access', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique();
            $table->integer('user_type')->default(1); // e.g., 1=Admin, 2=Incharge
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone_no')->nullable();
            $table->string('outlet_code');
            $table->string('gender')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('device_token')->nullable();
            $table->string('reset_code')->nullable();
            $table->string('status')->default('1');
            $table->timestamp('created_date')->nullable();
            $table->timestamp('modify_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_access');
    }
};
