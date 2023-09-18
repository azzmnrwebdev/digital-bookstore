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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 13)->unique()->nullable();
            $table->string('title')->unique();
            $table->string('slug');
            $table->enum('status', ['free', 'paid']);
            $table->string('price')->nullable();
            $table->text('description');
            $table->string('pdf');
            $table->text('password')->nullable();
            $table->string('thumbnail');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ebooks');
    }
};
