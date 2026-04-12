<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('max_guests');
            $table->string('duration')->nullable(); // e.g. "4 hours", "Full day"
            $table->string('description')->nullable(); 
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
