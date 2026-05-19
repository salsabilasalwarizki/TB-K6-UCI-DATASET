<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('creators', function (Blueprint $table) {
            $table->id('creator_id');
            $table->string('name');
            $table->string('affiliation')->nullable();
            $table->string('email')->nullable();
            $table->string('orcid')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('creators'); }
};