<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 50);
            $table->string('username', 50)->unique();
            $table->string('email', 50)->unique();
            $table->enum('gender', ['L', 'P', 'Other'])->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->char('phone_number', 13)->unique()->nullable();
            $table->enum('role', ['admin', 'penulis', 'pembaca'])->default('pembaca');
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->enum('status', ['process', 'approved', 'rejected'])->default('process');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
