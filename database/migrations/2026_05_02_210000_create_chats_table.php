<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultor_id');
            $table->unsignedBigInteger('pedreiro_id');
            $table->timestamps();

            $table->foreign('consultor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pedreiro_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['consultor_id', 'pedreiro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
