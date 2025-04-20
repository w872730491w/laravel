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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->comment('头像');
            $table->string('nickname')->comment('昵称');
            $table->string('username')->unique()->comment('用户名');
            $table->string('password')->comment('密码');
            $table->timestamps();
            $table->comment('管理员');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
