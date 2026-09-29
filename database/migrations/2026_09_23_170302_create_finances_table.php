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
        if (!Schema::hasTable('finances')) {
            Schema::create('finances', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['in', 'out'])->default('in');
                $table->decimal('amount', 15, 2);
                $table->string('description', 255)->nullable();
                $table->date('date');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
