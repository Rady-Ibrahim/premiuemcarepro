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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, email, number, boolean
            $table->string('group')->default('general');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'admin_chat_email',
                'value' => 'admin@example.com',
                'type' => 'email',
                'group' => 'chat',
                'description' => 'Email for unread chat notifications',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_chat_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'chat',
                'description' => 'Enable email notifications for unread chat messages',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
