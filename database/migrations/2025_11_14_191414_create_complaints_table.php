<?php

use App\Enums\ComplaintStatusEnum;
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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('complaint_type_id')->constrained('complaint_types')->cascadeOnDelete();
            $table->foreignId('government_entity_id')->constrained('government_entities')->cascadeOnDelete();
            $table->text('location_description');
            $table->text('problem_description');
            $table->string('status')->default(ComplaintStatusEnum::PENDING->value);
            $table->string('reference_number')->unique();
            $table->index('status');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
