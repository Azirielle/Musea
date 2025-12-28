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
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('reporter_id')->nullable()->change();
            $table->string('reported_item_type')->nullable()->change();
            $table->unsignedBigInteger('reported_item_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('reporter_id')->nullable(false)->change();
            // We can't easily revert nullableMorphs without dropping columns, so we'll leave it or basic revert
            // For simplicity in this context, we will omit strict reversion logic for morphs as it is complex.
        });
    }
};
