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
        Schema::create('attribute_catalouge_attribute', function (Blueprint $table) {
    $table->unsignedBigInteger('attribute_catalouge_id');
    $table->unsignedBigInteger('attribute_id');
    $table->foreign('attribute_catalouge_id')->references('id')->on('attribute_catalouges')->onDelete('cascade');
    $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('attribute_catalouge_attribute');
    }
};