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
        // Migrate existing admins
        if (Schema::hasColumn('users', 'is_admin')) {
            $admins = DB::table('users')->where('is_admin', 1)->get();
            foreach ($admins as $admin) {
                // Check if admin already exists to avoid duplicates if re-running
                if (!DB::table('admins')->where('email', $admin->email)->exists()) {
                    DB::table('admins')->insert([
                        'first_name' => $admin->first_name,
                        'last_name' => $admin->last_name,
                        'email' => $admin->email,
                        'password' => $admin->password,
                        'created_at' => $admin->created_at,
                        'updated_at' => $admin->updated_at,
                    ]);
                }
            }

            // Drop the column
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });
    }
};
