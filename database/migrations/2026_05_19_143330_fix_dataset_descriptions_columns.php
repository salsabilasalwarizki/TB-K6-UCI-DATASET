// database/migrations/xxxx_xx_xx_fix_dataset_descriptions_columns.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dataset_descriptions', function (Blueprint $table) {
            // Pastikan semua kolom adalah TEXT/VARCHAR, bukan JSON
            $table->text('purpose')->nullable()->change();
            $table->text('funding')->nullable()->change();
            $table->text('instances_represent')->nullable()->change();
            $table->text('data_splits')->nullable()->change();
            $table->text('sensitive_data')->nullable()->change();
            $table->text('preprocessing')->nullable()->change();
            $table->text('additional_info')->nullable()->change();
            $table->text('citation_requests')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dataset_descriptions', function (Blueprint $table) {
            // Rollback ke ukuran sebelumnya jika perlu
            $table->string('purpose', 1000)->nullable()->change();
            $table->string('funding', 1000)->nullable()->change();
            $table->string('instances_represent', 1000)->nullable()->change();
            $table->string('data_splits', 1000)->nullable()->change();
            $table->string('sensitive_data', 1000)->nullable()->change();
            $table->string('preprocessing', 1000)->nullable()->change();
            $table->string('additional_info', 1000)->nullable()->change();
            $table->string('citation_requests', 1000)->nullable()->change();
        });
    }
};