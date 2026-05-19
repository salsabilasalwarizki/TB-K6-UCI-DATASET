<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dataset_creator', function (Blueprint $table) {
            $table->unsignedBigInteger('dataset_id');
            $table->unsignedBigInteger('creator_id');
            $table->string('contribution_role')->nullable();
            $table->primary(['dataset_id', 'creator_id']);
            
            // Foreign keys eksplisit (hindari constrained() untuk custom PK names)
            $table->foreign('dataset_id')
                  ->references('dataset_id')
                  ->on('datasets')
                  ->onDelete('cascade');
            $table->foreign('creator_id')
                  ->references('creator_id')
                  ->on('creators')
                  ->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('dataset_creator'); }
};