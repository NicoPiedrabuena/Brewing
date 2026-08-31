<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('style')->nullable();
            $table->decimal('batch_size', 6, 1)->default(20); $table->decimal('og', 5, 3)->nullable();
            $table->decimal('fg', 5, 3)->nullable(); $table->decimal('abv', 4, 1)->nullable();
            $table->unsignedSmallInteger('ibu')->nullable(); $table->string('color', 20)->nullable();
            $table->text('ingredients')->nullable(); $table->text('process')->nullable(); $table->text('notes')->nullable(); $table->timestamps();
        });
        Schema::create('batches', function (Blueprint $table) {
            $table->id(); $table->foreignId('recipe_id')->constrained()->cascadeOnDelete(); $table->string('code')->unique();
            $table->date('brewed_at'); $table->string('status')->default('planned'); $table->decimal('volume', 6, 1)->nullable();
            $table->decimal('og', 5, 3)->nullable(); $table->decimal('fg', 5, 3)->nullable(); $table->decimal('temperature', 4, 1)->nullable();
            $table->date('packaged_at')->nullable(); $table->unsignedTinyInteger('rating')->nullable(); $table->text('notes')->nullable(); $table->timestamps();
        });
        Schema::create('readings', function (Blueprint $table) {
            $table->id(); $table->foreignId('batch_id')->constrained()->cascadeOnDelete(); $table->dateTime('measured_at');
            $table->decimal('gravity', 5, 3)->nullable(); $table->decimal('temperature', 4, 1)->nullable();
            $table->decimal('ph', 3, 2)->nullable(); $table->text('notes')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('readings'); Schema::dropIfExists('batches'); Schema::dropIfExists('recipes'); }
};
