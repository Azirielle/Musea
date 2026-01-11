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
        Schema::table('artworks', function (Blueprint $table) {
            // SQLite allows arbitrary strings in 'enum' columns usually, so we skip change()
            // to avoid complex table recreation issues.

            // Add subcategory column
            if (!Schema::hasColumn('artworks', 'subcategory')) {
                $table->string('subcategory')->nullable()->after('category');
                $table->index('subcategory');
            }

            // Ensure category is indexed if not already
            // $table->index('category'); // Skipped to avoid duplicate index errors if it exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            if (Schema::hasColumn('artworks', 'subcategory')) {
                $table->dropIndex(['subcategory']);
                $table->dropColumn('subcategory');
            }
        });
    }
};
