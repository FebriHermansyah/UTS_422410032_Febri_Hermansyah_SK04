<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initial_conditions', function (Blueprint $table) {
            $table->id();
            // unique() => menjamin relasi ONE TO ONE di level database
            $table->foreignId('tape_collection_id')->unique()
                  ->constrained('tape_collections')->cascadeOnDelete();
            $table->string('physical_condition', 20)->index();
            $table->text('damage_description')->nullable();
            $table->boolean('has_mold')->default(false);
            $table->boolean('has_sticky_shed')->default(false);
            $table->date('assessed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initial_conditions');
    }
};
