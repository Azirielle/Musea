<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['Painting', 'Canvas', 'Drawing', 'Sculpture', 'Vase', 'Basket', 'Other'])->default('Other')->index();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->unsigned()->default(0);
            $table->enum('status', ['active', 'archived', 'pending', 'declined'])->default('pending');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
