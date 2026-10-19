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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['Wisata', 'UMKM']);
            $table->float('rating')->default(0);
            $table->integer('review_count')->default(0);
            $table->string('location');
            $table->string('district');
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->integer('weekday_price')->nullable();
            $table->integer('weekend_price')->nullable();
            $table->string('open_hours')->nullable();
            $table->enum('status', ['Buka', 'Tutup', 'Musiman'])->default('Buka');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->float('map_x')->nullable();
            $table->float('map_y')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
