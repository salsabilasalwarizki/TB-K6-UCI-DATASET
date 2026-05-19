<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dataset_creator', function (Blueprint $table) {
            if (!Schema::hasColumn('dataset_creator', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dataset_creator', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};