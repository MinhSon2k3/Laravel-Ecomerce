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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('canonical',10)->unique();
            $table->string('image');
            $table->unsignedbigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//cascade :một user xóa->language liên quan xóa->post liên quan xóa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
