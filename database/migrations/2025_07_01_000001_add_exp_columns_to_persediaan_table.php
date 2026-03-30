<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->date('tanggal_exp')->nullable()->after('jumlah');
            $table->date('tanggal_masuk')->default(now()->toDateString())->after('tanggal_exp');
        });
    }

    public function down(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_exp', 'tanggal_masuk']);
        });
    }
};
