<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('files', function (Blueprint $table) {
            $table->id('file_id');
            $table->unsignedBigInteger('dataset_id');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_format');
            $table->string('file_size');
            $table->bigInteger('file_size_bytes')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('download_url')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
            
            $table->foreign('dataset_id')->references('dataset_id')->on('datasets')->onDelete('cascade');
            $table->index('dataset_id');
        });
    }
    public function down(): void { Schema::dropIfExists('files'); }
};