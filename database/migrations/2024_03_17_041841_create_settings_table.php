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
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('messenger')->nullable();
            $table->string('play_store')->nullable();
            $table->string('app_store')->nullable();
            $table->string('instagram')->nullable();
            $table->string('image')->nullable();###main image
            $table->string('logo')->nullable();###main logo
            $table->integer('code_private')->nullable();###main logo
            $table->boolean('prayer_timings')->default(1);###main image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
