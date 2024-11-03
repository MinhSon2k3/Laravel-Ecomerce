<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('post_catalouge_translates', function (Blueprint $table) {
            $table->unsignedbigInteger('post_catalouge_id');
            $table->unsignedbigInteger('language_id');
            $table->foreign('post_catalouge_id')->references('id')->on('post_catalouges')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->longText('content');
            $table->string('meta_title');
            $table->string('meta_keyword');
            $table->text('meta_description');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('product_catalouges');
    }
};