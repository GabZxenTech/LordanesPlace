<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Profile picture saved in the database (Render deletes uploaded files on restart),
    // and when an account was deleted by its owner.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('avatar_image')->nullable();
            $table->timestamp('deactivated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_image', 'deactivated_at']);
        });
    }
};
