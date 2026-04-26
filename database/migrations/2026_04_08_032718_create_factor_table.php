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
        Schema::create('status_cna', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('factors', function (Blueprint $table) {
            $table->id('id_factor');
            $table->string('name', 500);
            $table->text('description')->nullable();
            $table->foreignId('responsable')->nullable()->constrained('users', 'id');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->foreignId('status_id')->constrained('status_cna', 'id_status');
            $table->timestamps();
        });

        Schema::create('caracteristicas', function (Blueprint $table) {
            $table->id('id_caracteristica');
            $table->string('name', 500);
            $table->text('description')->nullable();
            $table->foreignId('factor_id')->constrained('factors', 'id_factor');
            $table->foreignId('responsable')->nullable()->constrained('users', 'id');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->foreignId('status_id')->constrained('status_cna', 'id_status');
            $table->timestamps();
        });

        Schema::create('aspectos', function (Blueprint $table) {
            $table->id('id_aspecto');
            $table->string('name', 500);
            $table->text('description')->nullable();
            $table->foreignId('caracteristica_id')->constrained('caracteristicas', 'id_caracteristica');
            $table->foreignId('responsable')->nullable()->constrained('users', 'id');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->foreignId('status_id')->constrained('status_cna', 'id_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspectos');
        Schema::dropIfExists('caracteristicas');
        Schema::dropIfExists('factors');
        Schema::dropIfExists('status_cna');
    }
};
