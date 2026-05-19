<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('institution')->nullable()->after('email');
            $table->text('institution_address')->nullable()->after('institution');
        });
    }
    
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['institution', 'institution_address']);
        });
    }
};