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
        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');

            $table->string('objeto')->nullable();
            $table->string('registro')->nullable();
            $table->string('atributo')->nullable();
            $table->string('operacion')->nullable();

            $table->text('valor_antiguo')->nullable();
            $table->text('valor_nuevo')->nullable();

            $table->integer('modificado_por')
                ->nullable()          // en caso de que sea null
                ->constrained('users', 'id');
                
            $table->dateTime('fecha_modificacion')->nullable();

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
