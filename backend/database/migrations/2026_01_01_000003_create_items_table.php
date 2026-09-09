<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->enum('type', ['lost', 'found']);
            $table->string('title', 150);
            $table->text('description');
            $table->text('secret_details')->nullable();
            $table->dateTime('incident_date');
            $table->string('location_name', 150);
            $table->string('district', 60);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('primary_photo_url', 255)->nullable();
            $table->string('reward_offered', 100)->nullable();
            $table->enum('status', ['open', 'claimed', 'resolved', 'cancelled'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
