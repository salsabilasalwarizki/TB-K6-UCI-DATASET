<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dataset_paper', function (Blueprint $table) {
            $table->unsignedBigInteger('dataset_id');
            $table->unsignedBigInteger('paper_id');
            $table->text('citation_context')->nullable();
            $table->primary(['dataset_id', 'paper_id']);
            
            $table->foreign('dataset_id')->references('dataset_id')->on('datasets')->onDelete('cascade');
            $table->foreign('paper_id')->references('paper_id')->on('papers')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('dataset_paper'); }
};