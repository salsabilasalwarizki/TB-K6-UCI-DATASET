// database/migrations/xxxx_xx_xx_fix_files_file_format_column.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Ubah dari ENUM ke VARCHAR(20) untuk fleksibilitas
            $table->string('file_format', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Kembalikan ke ENUM jika diperlukan
            $table->enum('file_format', ['csv', 'arff', 'txt', 'json', 'xlsx', 'zip', 'tar.gz', 'pdf', 'data', 'names', 'other'])
                ->nullable()
                ->change();
        });
    }
};