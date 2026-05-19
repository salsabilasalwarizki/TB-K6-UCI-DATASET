<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dois', function (Blueprint $table) {
            $table->id('doi_id');
            $table->string('doi_string')->unique();
            $table->string('resolution_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dois'); }
};