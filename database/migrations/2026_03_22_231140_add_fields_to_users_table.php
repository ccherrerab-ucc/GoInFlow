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
            $table->foreignId('id_area')
                ->nullable()
                ->constrained('d_area', 'id_area');
            $table->foreignId('id_departamento')->nullable()->constrained('departamento', 'id_departamento');

            $table->foreignId('id_rol')
                ->nullable()
                ->constrained('rol', 'id_rol');

            $table->foreignId('id_status')
                ->nullable()
                ->constrained('status', 'id_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
