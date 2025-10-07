<?php

use App\Enums\Role;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id (primary key)
            $table->string('email', 100)->unique();
            $table->string('name', 100);
            $table->string('password');
            $table->enum('role', Role::values());
            $table->string('phone', 15)->nullable()->unique();
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
