<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('license_id'); // pending, approved, rejected
            $table->text('admin_notes')->nullable()->after('status');
            $table->boolean('is_public')->default(false)->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_notes', 'is_public']);
        });
    }
};