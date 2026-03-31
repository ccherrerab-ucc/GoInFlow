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
        Schema::create('status', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('name');
        });
        Schema::create('rol', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('name');

            $table->timestamps();

            $table->foreignId('created_by')
                ->nullable()          // en caso de que sea null
                ->constrained('users', 'id');

            $table->foreignId('updated_by')
                ->nullable()          // en caso de que sea null
                ->constrained('users', 'id');

            $table->foreignId('status_id')
                ->constrained('status', 'id_status');
        });
        Schema::create('d_area', function (Blueprint $table) {
            $table->id('id_area');
            $table->string('name');

            $table->timestamps();

            $table->foreignId('status_id')
                ->constrained('status', 'id_status');

            $table->foreignId('created_by')
                ->nullable()          // en caso de que sea null
                ->constrained('users', 'id');

            $table->foreignId('updated_by')
                ->nullable()          // en caso de que sea null
                ->constrained('users', 'id');
        });

        Schema::create('departamento', function (Blueprint $table) {
            $table->id('id_departamento');
            $table->string('name');

            $table->timestamps();

            $table->foreignId('status_id')
                ->constrained('status', 'id_status');

            $table->foreignId('area_id')
                ->constrained('d_area', 'id_area');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol');
        Schema::dropIfExists('d_area');
        Schema::dropIfExists('status');
    }
};
