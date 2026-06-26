<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
            $table->string('snomed_concept_id')->nullable();
            $table->string('snomed_term')->nullable();
            $table->string('icd10_mapped_code')->nullable()->index();
            $table->boolean('is_primary_diagnosis')->default(false)->index();
            $table->string('miscoding_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codings');
    }
};
