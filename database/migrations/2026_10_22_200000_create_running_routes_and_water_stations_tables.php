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
        // Add planned_distance_km to workouts if not exists
        if (Schema::hasTable('workouts') && ! Schema::hasColumn('workouts', 'planned_distance_km')) {
            Schema::table('workouts', function (Blueprint $table) {
                $table->decimal('planned_distance_km', 8, 2)->nullable()->after('status');
            });
        }

        // Create running_routes table
        if (! Schema::hasTable('running_routes')) {
            Schema::create('running_routes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workout_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('planned_distance_km', 8, 2);
                $table->unsignedSmallInteger('order')->default(1);
                $table->string('terrain_type')->nullable(); // e.g., Road, Trail, Track, Mixed
                $table->unsignedSmallInteger('elevation_gain_m')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['workout_id', 'order'], 'running_routes_workout_order_idx');
                $table->index('user_id', 'running_routes_user_idx');
            });
        }

        // Create water_stations table
        if (! Schema::hasTable('water_stations')) {
            Schema::create('water_stations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('running_route_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('type')->default('water_fountain'); // water_fountain, convenience_store, vending_machine, aid_station, hydration_point
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->text('notes')->nullable();
                $table->unsignedSmallInteger('order')->default(1);
                $table->timestamps();

                $table->index(['running_route_id', 'order'], 'water_stations_route_order_idx');
                $table->index('user_id', 'water_stations_user_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_stations');
        Schema::dropIfExists('running_routes');

        if (Schema::hasTable('workouts') && Schema::hasColumn('workouts', 'planned_distance_km')) {
            Schema::table('workouts', function (Blueprint $table) {
                $table->dropColumn('planned_distance_km');
            });
        }
    }
};
