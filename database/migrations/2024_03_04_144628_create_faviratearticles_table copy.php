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
        Schema::create('faviratearticles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('articles_id')->references('id')->on('articles')->onDelete('cascade'); // تغيير 'audio_id' إلى 'articles_id'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faviratearticles');
    }

};
