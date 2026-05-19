<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('papers', function (Blueprint $table) {
            $table->id('paper_id');
            $table->string('title');
            $table->text('authors');
            $table->year('publication_year');
            $table->string('venue')->nullable();
            $table->string('paper_doi')->nullable();
            $table->string('paper_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('papers'); }
};