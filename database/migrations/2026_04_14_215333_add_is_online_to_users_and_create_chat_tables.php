<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_online to users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('role');
        });

        // Create conversations table
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // For guests (no account).
            $table->timestamp('last_message_at')->useCurrent();
            $table->timestamps();
        });

        // Create messages table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('role', ['user', 'admin']);
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_online')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_online');
            });
        }
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
