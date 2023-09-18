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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users');
            $table->string('id_pesanan')->unique();
            $table->string('name');
            $table->string('email');
            $table->enum('payment_method', ['m_banking', 'e_wallet'])->nullable();
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['Process', 'Approved', 'Rejected'])->default('Process');
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
        Schema::dropIfExists('orders');
    }
};
