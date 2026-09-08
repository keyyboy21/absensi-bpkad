<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('nip')->nullable()->unique()->after('id');
        $table->string('jabatan')->nullable()->after('email');
        $table->string('bidang')->nullable()->after('jabatan');

        $table->enum('role', [
            'admin',
            'pegawai'
        ])->default('pegawai')->after('bidang');

        $table->enum('status', [
            'aktif',
            'nonaktif'
        ])->default('aktif')->after('role');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropUnique(['nip']);

        $table->dropColumn([
            'nip',
            'jabatan',
            'bidang',
            'role',
            'status'
        ]);
    });
}
};
