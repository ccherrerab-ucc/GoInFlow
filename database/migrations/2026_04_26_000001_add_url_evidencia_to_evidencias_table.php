<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->string('url_evidencia', 2048)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropColumn('url_evidencia');
        });
    }
};
