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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user1_id');
            $table->unsignedBigInteger('user2_id');
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user1_id', 'user2_id'], 'idx_user1_user2');
            $table->index(['user2_id', 'user1_id'], 'idx_user2_user1');
            $table->unique(['user1_id', 'user2_id'], 'unique_conversation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
