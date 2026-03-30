<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mutasi', function (Blueprint $table) {
            $table->foreignId('id_persediaan')->nullable()->after('id_produk')->constrained('persediaan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mutasi', function (Blueprint $table) {
            $table->dropForeign(['id_persediaan']);
            $table->dropColumn('id_persediaan');
        });
    }
};
