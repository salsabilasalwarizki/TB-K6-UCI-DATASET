<?php

// database/migrations/xxxx_xx_xx_increase_keywords_category_length.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('keywords', function (Blueprint $table) {
            // Ubah dari VARCHAR(50) atau ENUM ke VARCHAR(100)
            $table->string('category', 100)->nullable()->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->change();
        });
    }
};