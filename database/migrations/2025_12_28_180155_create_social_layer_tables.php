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
        // Tables handled by other migrations
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('social_layer_tables'); // The original one created by command
    }


    /**
     * Reverse the migrations.
     */

};
