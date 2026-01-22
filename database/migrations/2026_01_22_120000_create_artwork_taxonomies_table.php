<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artwork_taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g. Paintings
            $table->string('type')->index();     // Style | Subject | Medium | Method
            $table->string('value');             // e.g. Abstract
            $table->timestamps();

            $table->unique(['category', 'type', 'value'], 'artwork_taxonomies_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_taxonomies');
    }
};

