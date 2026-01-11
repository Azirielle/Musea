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
            $table->decimal('width', 10, 2)->nullable()->after('price');
            $table->decimal('height', 10, 2)->nullable()->after('width');
            $table->decimal('depth', 10, 2)->nullable()->after('height');
            $table->string('unit')->default('cm')->nullable()->after('depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['width', 'height', 'depth', 'unit']);
        });
    }
};
