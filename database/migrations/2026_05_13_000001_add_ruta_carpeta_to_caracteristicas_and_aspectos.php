<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->string('ruta_carpeta', 500)->nullable()->after('description');
        });

        Schema::table('aspectos', function (Blueprint $table) {
            $table->string('ruta_carpeta', 500)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->dropColumn('ruta_carpeta');
        });

        Schema::table('aspectos', function (Blueprint $table) {
            $table->dropColumn('ruta_carpeta');
        });
    }
};
