<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id('dataset_id');
            $table->string('name');
            $table->text('description');
            $table->date('donated_date')->nullable();
            $table->date('last_updated')->nullable();
            $table->string('characteristics')->nullable();
            $table->string('feature_type')->nullable();
            $table->integer('num_instances')->nullable();
            $table->integer('num_features')->nullable();
            $table->boolean('has_missing_values')->default(false);
            $table->text('additional_info')->nullable();
            $table->json('attribute_info')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('citation_count')->default(0);
            
            // Foreign keys sebagai kolom biasa (constraint dipisah)
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('subject_area_id')->nullable();
            $table->unsignedBigInteger('license_id')->nullable();
            $table->unsignedBigInteger('doi_id')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('donated_date');
            $table->index('task_id');
            $table->index('subject_area_id');
            $table->fullText(['name', 'description', 'characteristics']);
        });
    }
    public function down(): void { Schema::dropIfExists('datasets'); }
};