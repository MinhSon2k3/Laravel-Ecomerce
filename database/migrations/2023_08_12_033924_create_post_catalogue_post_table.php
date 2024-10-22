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
        Schema::create('post_catalouge_post', function (Blueprint $table) {
            $table->unsignedBigInteger('post_catalouge_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_catalouge_id')->references('id')->on('post_catalouges')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_catalogue_post');
    }
};
