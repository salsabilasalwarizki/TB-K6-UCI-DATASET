<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variables', function (Blueprint $table) {
            $table->id('variable_id');
            $table->unsignedBigInteger('dataset_id');
            $table->string('variable_name');
            $table->string('role')->default('Feature');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('units')->nullable();
            $table->boolean('missing_values')->default(false);
            $table->json('possible_values')->nullable();
            $table->integer('order_index')->nullable();
            $table->timestamps();
            
            $table->foreign('dataset_id')->references('dataset_id')->on('datasets')->onDelete('cascade');
            $table->index(['dataset_id', 'role']);
        });
    }
    public function down(): void { Schema::dropIfExists('variables'); }
};