<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action')->index();
            $table->string('table_name')->index();
            $table->string('search_query_logged')->nullable();
            $table->timestamp('created_at', 6)->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
