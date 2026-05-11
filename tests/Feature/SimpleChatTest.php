<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SimpleChatTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;
    protected $chatService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::factory()->create([
            'type' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        // Create regular user
        $this->regularUser = User::factory()->create([
            'type' => 'customer',
            'name' => 'Customer User',
            'email' => 'customer@test.com',
        ]);

        $this->chatService = app(ChatService::class);
    }

    public function test_user_can_create_conversation_with_admin()
    {
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
        ]);

        $this->assertEquals($this->regularUser->id, $conversation->user1_id);
        $this->assertEquals($this->adminUser->id, $conversation->user2_id);
    }

    public function test_user_can_send_message_to_admin()
    {
        // Create conversation
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Send message
        $message = $this->chatService->sendMessage(
            $conversation->id,
            $this->regularUser->id,
            'Hello Admin, this is a test message'
        );

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'conversation_id' => $conversation->id,
            'sender_id' => $this->regularUser->id,
            'content' => 'Hello Admin, this is a test message',
            'type' => 'text',
        ]);

        $this->assertFalse($message->is_read);
    }

    public function test_admin_can_reply_to_user()
    {
        // Create conversation
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // User sends message
        $this->chatService->sendMessage(
            $conversation->id,
            $this->regularUser->id,
            'Hello Admin'
        );

        // Admin replies
        $reply = $this->chatService->sendMessage(
            $conversation->id,
            $this->adminUser->id,
            'Hello, how can I help you?'
        );

        $this->assertDatabaseHas('messages', [
            'id' => $reply->id,
            'conversation_id' => $conversation->id,
            'sender_id' => $this->adminUser->id,
            'content' => 'Hello, how can I help you?',
        ]);

        // Check conversation has 2 messages
        $messages = $conversation->messages()->get();
        $this->assertCount(2, $messages);
    }

    public function test_user_can_send_file_to_admin()
    {
        // Create conversation
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Create fake file
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        // Send file
        $message = $this->chatService->sendMessage(
            $conversation->id,
            $this->regularUser->id,
            null,
            $file
        );

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'conversation_id' => $conversation->id,
            'sender_id' => $this->regularUser->id,
            'type' => 'file',
        ]);

        $this->assertNotNull($message->file_path);
        $this->assertNotNull($message->file_name);
        $this->assertEquals('document.pdf', $message->file_name);
    }

    public function test_user_can_mark_message_as_read()
    {
        // Create conversation
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Admin sends message
        $message = $this->chatService->sendMessage(
            $conversation->id,
            $this->adminUser->id,
            'Hello User'
        );

        $this->assertFalse($message->is_read);

        // User marks as read
        $this->chatService->markMessageAsRead($message->id);

        $message->refresh();
        $this->assertTrue($message->is_read);
        $this->assertNotNull($message->read_at);
    }

    public function test_conversation_is_unique_between_two_users()
    {
        // Create conversation first time
        $conversation1 = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Try to create same conversation again
        $conversation2 = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Should return same conversation
        $this->assertEquals($conversation1->id, $conversation2->id);

        // Should only have one conversation in database
        $this->assertDatabaseCount('conversations', 1);
    }

    public function test_user_is_part_of_conversation()
    {
        // Create conversation
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );

        // Check if user is part of conversation
        $this->assertTrue($this->regularUser->isPartOf($conversation->id));
        $this->assertTrue($this->adminUser->isPartOf($conversation->id));

        // Create another user
        $otherUser = User::factory()->create();
        $this->assertFalse($otherUser->isPartOf($conversation->id));
    }

    public function test_complete_chat_flow()
    {
        // Step 1: User creates conversation with admin
        $conversation = $this->chatService->createOrGetConversation(
            $this->regularUser->id,
            $this->adminUser->id
        );
        $this->assertNotNull($conversation->id);

        // Step 2: User sends text message
        $msg1 = $this->chatService->sendMessage(
            $conversation->id,
            $this->regularUser->id,
            'Hello Admin, I need help'
        );
        $this->assertEquals('Hello Admin, I need help', $msg1->content);

        // Step 3: User sends file
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        $msg2 = $this->chatService->sendMessage(
            $conversation->id,
            $this->regularUser->id,
            null,
            $file
        );
        $this->assertEquals('file', $msg2->type);

        // Step 4: Admin replies
        $msg3 = $this->chatService->sendMessage(
            $conversation->id,
            $this->adminUser->id,
            'I can help you. What do you need?'
        );
        $this->assertEquals('I can help you. What do you need?', $msg3->content);

        // Step 5: User marks admin's message as read
        $this->chatService->markMessageAsRead($msg3->id);
        $msg3->refresh();
        $this->assertTrue($msg3->is_read);

        // Step 6: Check conversation count
        $this->assertDatabaseCount('messages', 3);
        $this->assertDatabaseCount('conversations', 1);
    }
}
