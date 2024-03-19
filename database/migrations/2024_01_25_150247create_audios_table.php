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
        Schema::create('audios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('random_id');
            $table->string('title');
            $table->string('image')->nullable(); // Assuming image is a file path
            $table->string('audio'); // Assuming audio is a file path
            $table->enum('status', ['public', 'private'])->default('public');
            $table->foreignId('elder_id')->constrained()->onDelete('cascade');
            $table->foreignId('audios_categories_id')->constrained()->onDelete('cascade');
            $table->bigInteger('visits_count')->default(0); // افتراضياً تكون القيمة صفر
            $table->longText('tag_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audios');
    }
};
