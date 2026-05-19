<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dataset_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('dataset_id');
            $table->unsignedBigInteger('keyword_id');
            $table->primary(['dataset_id', 'keyword_id']);
            
            $table->foreign('dataset_id')->references('dataset_id')->on('datasets')->onDelete('cascade');
            $table->foreign('keyword_id')->references('keyword_id')->on('keywords')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('dataset_keyword'); }
};