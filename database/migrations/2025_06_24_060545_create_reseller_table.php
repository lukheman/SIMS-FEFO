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
        Schema::create('reseller', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->unique();
            $table->string('name', 100);
            $table->string('password');
            $table->enum('role', ['reseller']);
            $table->string('foto')->nullable();
            $table->string('alamat')->nullable();
            $table->string('phone', 15)->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller');
    }
};
