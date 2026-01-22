<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'reported_artist_id')) {
                $table->foreignId('reported_artist_id')
                    ->nullable()
                    ->after('reporter_id')
                    ->constrained('users')
                    ->nullOnDelete()
                    ->index();
            }

            if (!Schema::hasColumn('reports', 'proof')) {
                $table->string('proof')->nullable()->after('details');
            }

            if (!Schema::hasColumn('reports', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
        });

        // Expand status enum to include 'rejected' (and keep legacy 'dismissed' for compatibility).
        // SQLite stores enums as strings, so this is mainly for MySQL.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `reports` MODIFY `status` ENUM('pending','resolved','rejected','dismissed') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        // Best-effort rollback: drop added columns; keep status enum as-is.
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'reported_artist_id')) {
                $table->dropConstrainedForeignId('reported_artist_id');
            }
            if (Schema::hasColumn('reports', 'proof')) {
                $table->dropColumn('proof');
            }
            if (Schema::hasColumn('reports', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
        });
    }
};

