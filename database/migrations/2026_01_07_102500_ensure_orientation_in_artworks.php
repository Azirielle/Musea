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
            if (!Schema::hasColumn('artworks', 'orientation')) {
                $table->string('orientation')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't drop it here because it might have been intended by another migration
        // But for correctness in valid rollback:
        /*
        Schema::table('artworks', function (Blueprint $table) {
             $table->dropColumn('orientation');
        });
        */
    }
};
