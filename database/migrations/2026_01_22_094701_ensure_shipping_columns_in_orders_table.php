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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                if (Schema::hasColumn('orders', 'status')) {
                    $table->string('tracking_number')->nullable()->after('status');
                } else {
                    $table->string('tracking_number')->nullable();
                }
            }
            if (!Schema::hasColumn('orders', 'courier')) {
                if (Schema::hasColumn('orders', 'tracking_number')) {
                    $table->string('courier')->nullable()->after('tracking_number');
                } else {
                    $table->string('courier')->nullable();
                }
            }
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                if (Schema::hasColumn('orders', 'courier')) {
                    $table->timestamp('shipped_at')->nullable()->after('courier');
                } else {
                    $table->timestamp('shipped_at')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'tracking_number')) {
                $table->dropColumn('tracking_number');
            }
            if (Schema::hasColumn('orders', 'courier')) {
                $table->dropColumn('courier');
            }
            if (Schema::hasColumn('orders', 'shipped_at')) {
                $table->dropColumn('shipped_at');
            }
        });
    }
};
