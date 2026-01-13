<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Changing ENUM to STRING to support all categories
        // Using raw statement for maximum compatibility across drivers if DBAL missing

        // For MySQL/MariaDB (most likely target)
        DB::statement("ALTER TABLE artworks MODIFY COLUMN category VARCHAR(255) NOT NULL DEFAULT 'Other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back to original ENUM (optional, but good practice)
        // Note: Data might be lost if it doesn't match the enum
        DB::statement("ALTER TABLE artworks MODIFY COLUMN category ENUM('Painting', 'Canvas', 'Drawing', 'Sculpture', 'Vase', 'Basket', 'Other') NOT NULL DEFAULT 'Other'");
    }
};
