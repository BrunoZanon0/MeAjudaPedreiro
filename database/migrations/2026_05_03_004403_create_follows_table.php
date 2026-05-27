<?php
// database/migrations/YYYY_MM_DD_create_follows_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint para evitar duplicação
            $table->unique(['follower_id', 'following_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
}