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
        Schema::create('user_catalouge_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_catalouge_id');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('user_catalouge_id')->references('id')->on('user_catalouges')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_catalouge_permissions');
    }
};
