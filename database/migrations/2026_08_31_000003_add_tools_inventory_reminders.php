<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('reminders',function(Blueprint $t){$t->id();$t->foreignId('batch_id')->nullable()->constrained()->cascadeOnDelete();$t->string('title');$t->dateTime('due_at');$t->boolean('completed')->default(false);$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('inventory_items',function(Blueprint $t){$t->id();$t->string('name');$t->string('category',30);$t->decimal('quantity',10,2)->default(0);$t->string('unit',20);$t->string('lot')->nullable();$t->date('expires_at')->nullable();$t->decimal('minimum_stock',10,2)->nullable();$t->text('notes')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('inventory_items');Schema::dropIfExists('reminders');}
};
