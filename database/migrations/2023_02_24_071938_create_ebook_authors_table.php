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
        Schema::create('ebook_authors', function (Blueprint $table) {
            $table->foreignId('ebooks_id')->constrained('ebooks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('authors_id')->constrained('authors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('uploaded_by')->nullable();
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
        Schema::dropIfExists('ebook_authors');
    }
};
