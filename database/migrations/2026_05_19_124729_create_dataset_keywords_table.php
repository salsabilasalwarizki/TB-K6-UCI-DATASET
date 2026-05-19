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
        // database/migrations/xxxx_create_dataset_keywords_table.php
Schema::create('dataset_keywords', function (Blueprint $table) {
    $table->foreignId('dataset_id')->constrained('datasets', 'dataset_id')->onDelete('cascade');
    $table->foreignId('keyword_id')->constrained('keywords', 'keyword_id')->onDelete('cascade');
    $table->primary(['dataset_id', 'keyword_id']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset_keywords');
    }
};
