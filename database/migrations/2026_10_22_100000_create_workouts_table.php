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
        if (! Schema::hasTable('workouts')) {
            Schema::create('workouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('date');
                $table->enum('type', ['running', 'strength']);
                $table->string('title');
                $table->enum('status', ['planned', 'completed', 'skipped'])->default('planned');

                // Running metrics
                $table->decimal('distance_km', 8, 2)->nullable();
                $table->unsignedInteger('duration_minutes')->nullable();
                $table->string('pace', 20)->nullable();

                // Strength metrics
                $table->unsignedSmallInteger('exercises_count')->default(0);
                $table->unsignedSmallInteger('sets_completed')->default(0);
                $table->unsignedSmallInteger('total_sets')->default(0);

                $table->text('notes')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                // Composite index for fast monthly calendar queries filtered by user and date range
                $table->index(['user_id', 'date'], 'workouts_user_date_idx');
                $table->index(['date', 'type', 'status'], 'workouts_date_type_status_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
