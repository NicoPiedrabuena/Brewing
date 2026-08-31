<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('recipes', function (Blueprint $table) {
            $table->decimal('efficiency', 5, 1)->nullable(); $table->decimal('mash_ph', 3, 2)->nullable();
            $table->text('water_profile')->nullable(); $table->text('mash_schedule')->nullable(); $table->text('boil_plan')->nullable();
            $table->text('fermentation_plan')->nullable(); $table->text('clarification_plan')->nullable(); $table->text('packaging_plan')->nullable();
        });
        Schema::table('batches', function (Blueprint $table) {
            $table->decimal('brewhouse_efficiency', 5, 1)->nullable(); $table->decimal('pre_boil_volume', 6, 1)->nullable();
            $table->decimal('pre_boil_gravity', 5, 3)->nullable(); $table->decimal('post_boil_volume', 6, 1)->nullable();
            $table->decimal('pitch_temperature', 4, 1)->nullable(); $table->string('yeast')->nullable();
            $table->decimal('packaged_volume', 6, 1)->nullable(); $table->decimal('carbonation', 4, 2)->nullable();
        });
        Schema::create('brew_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('batch_id')->constrained()->cascadeOnDelete(); $table->string('stage', 30)->index();
            $table->dateTime('occurred_at'); $table->string('title'); $table->decimal('value', 8, 2)->nullable(); $table->string('unit', 20)->nullable();
            $table->decimal('temperature', 4, 1)->nullable(); $table->decimal('gravity', 5, 3)->nullable(); $table->decimal('ph', 3, 2)->nullable();
            $table->unsignedSmallInteger('duration')->nullable(); $table->text('notes')->nullable(); $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('brew_logs');
        Schema::table('batches', fn (Blueprint $t) => $t->dropColumn(['brewhouse_efficiency','pre_boil_volume','pre_boil_gravity','post_boil_volume','pitch_temperature','yeast','packaged_volume','carbonation']));
        Schema::table('recipes', fn (Blueprint $t) => $t->dropColumn(['efficiency','mash_ph','water_profile','mash_schedule','boil_plan','fermentation_plan','clarification_plan','packaging_plan']));
    }
};
