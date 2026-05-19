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
        // In up():
Schema::table('datasets', function(Blueprint $table) {
    // Hapus constraint lama jika ada
    // Tambahkan foreign keys
    $table->foreignId('task_id')->nullable()->constrained('tasks', 'task_id')->nullOnDelete();
    $table->foreignId('subject_area_id')->nullable()->constrained('subject_areas', 'area_id')->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
            //
        });
    }
};
