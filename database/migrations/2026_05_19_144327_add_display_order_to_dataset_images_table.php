// database/migrations/xxxx_xx_xx_add_display_order_to_dataset_images_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dataset_images', function (Blueprint $table) {
            // Tambahkan kolom display_order jika belum ada
            if (!Schema::hasColumn('dataset_images', 'display_order')) {
                $table->unsignedInteger('display_order')->default(0)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dataset_images', function (Blueprint $table) {
            $table->dropColumn('display_order');
        });
    }
};