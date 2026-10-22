<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('running_routes') && ! Schema::hasColumn('running_routes', 'path_coordinates')) {
            Schema::table('running_routes', function (Blueprint $table) {
                $table->json('path_coordinates')->nullable()->after('planned_distance_km');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('running_routes') && Schema::hasColumn('running_routes', 'path_coordinates')) {
            Schema::table('running_routes', function (Blueprint $table) {
                $table->dropColumn('path_coordinates');
            });
        }
    }
};
