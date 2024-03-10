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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('random_id');
            $table->string('name');
            $table->string('file');
            $table->string('image');
            $table->enum('status', ['public','private'])->default('public');
            $table->longText('tag_name')->nullable();
            $table->timestamps();
            $table->foreignId('books_categories_id')->constrained()->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
