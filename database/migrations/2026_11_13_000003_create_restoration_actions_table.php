<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restoration_actions', function (Blueprint $table) {
            $table->id();
            // ONE TO MANY: satu koleksi punya banyak tindakan
            $table->foreignId('tape_collection_id')
                  ->constrained('tape_collections')->cascadeOnDelete();
            $table->string('action_name', 150);
            $table->string('priority', 10)->default('sedang');
            $table->string('status', 10)->default('rencana')->index();
            $table->date('planned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restoration_actions');
    }
};
