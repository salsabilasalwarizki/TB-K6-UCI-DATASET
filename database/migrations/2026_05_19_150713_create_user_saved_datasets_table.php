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
        // database/migrations/xxxx_xx_xx_create_user_saved_datasets_table.php
Schema::create('user_saved_datasets', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('dataset_id')->constrained('datasets', 'dataset_id')->onDelete('cascade');
    $table->timestamp('saved_at')->useCurrent();
    $table->primary(['user_id', 'dataset_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_saved_datasets');
    }
};
