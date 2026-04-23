<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* ══════════════════════════════════════════════════════
         | 1. ESTADOS DE DOCUMENTO
         |    Catálogo: Borrador=1 | En revisión=2 | Aprobado=3 | Rechazado=4
         ══════════════════════════════════════════════════════ */
        Schema::create('estado_documento', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('name');
            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 2. EVIDENCIAS
         |    Archivo almacenado como base64. Los campos de archivo
         |    son nullable: se llenan al subir el primer archivo.
         ══════════════════════════════════════════════════════ */
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id('id_evidencia');

            // Datos descriptivos — alineados con el modelo Evidencia
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            // Aspecto al que pertenece esta evidencia
            $table->unsignedBigInteger('id_aspecto');
            $table->foreign('id_aspecto')
                  ->references('id_aspecto')->on('aspectos')
                  ->noActionOnDelete();

            // Archivo (nullable: se sube después de crear la evidencia)
            $table->string('nombre_archivo')->nullable();
            $table->string('tipo_archivo')->nullable();        // mime type
            $table->longText('archivo_base64')->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();
            $table->unsignedInteger('version_actual')->default(0);

            // Vigencia del documento
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            // Estado del documento (Borrador/En revisión/Aprobado/Rechazado)
            // Nullable: se asigna al iniciar el flujo; el servicio lo fuerza a 1 (Borrador) al crear.
            $table->unsignedBigInteger('estado_actual')->nullable();
            $table->foreign('estado_actual')
                  ->references('id_estado')->on('estado_documento')
                  ->noActionOnDelete();

            // Estado CNA (Activo/Inactivo/Suprimido)
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')
                  ->references('id_status')->on('status_cna')
                  ->noActionOnDelete();

            // Auditoría
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 3. PIVOTE: evidencia ↔ aspecto (referencias cruzadas)
         |    Una evidencia puede asociarse a N aspectos adicionales
         ══════════════════════════════════════════════════════ */
        Schema::create('evidencia_aspecto', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evidencia_id');
            $table->foreign('evidencia_id')
                  ->references('id_evidencia')->on('evidencias')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('aspecto_id');
            $table->foreign('aspecto_id')
                  ->references('id_aspecto')->on('aspectos')
                  ->noActionOnDelete();

            $table->timestamps();

            $table->unique(['evidencia_id', 'aspecto_id']);
        });

        /* ══════════════════════════════════════════════════════
         | 4. HISTORIAL DE VERSIONES
         |    Cada carga de archivo genera un registro aquí.
         ══════════════════════════════════════════════════════ */
        Schema::create('version_documento', function (Blueprint $table) {
            $table->id('id_version');

            // Alineado con el modelo VersionDocumento (usa id_evidencia, no evidencia_id)
            $table->unsignedBigInteger('id_evidencia');
            $table->foreign('id_evidencia')
                  ->references('id_evidencia')->on('evidencias')
                  ->noActionOnDelete();

            $table->unsignedInteger('numero_version');
            $table->string('nombre_archivo', 255);
            $table->string('tipo_archivo', 100);      // mime type
            $table->longText('archivo_base64');
            $table->unsignedBigInteger('tamano_bytes');

            // Estado del documento en esta versión
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->foreign('id_estado')
                  ->references('id_estado')->on('estado_documento')
                  ->noActionOnDelete();

            $table->text('comentario')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 5. FLUJOS DE APROBACIÓN
         |    Un aspecto puede tener un flujo activo configurado
         |    por su responsable.
         ══════════════════════════════════════════════════════ */
        Schema::create('flujo', function (Blueprint $table) {
            $table->id('id_flujo');
            $table->string('nombre');

            $table->unsignedBigInteger('id_aspecto')->nullable();
            $table->foreign('id_aspecto')
                  ->references('id_aspecto')->on('aspectos')
                  ->nullOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 6. PASOS DEL FLUJO
         |    Secuencia de aprobadores para cada flujo.
         ══════════════════════════════════════════════════════ */
        Schema::create('flujo_paso', function (Blueprint $table) {
            $table->id('id_paso');

            $table->unsignedBigInteger('id_flujo');
            $table->foreign('id_flujo')
                  ->references('id_flujo')->on('flujo')
                  ->noActionOnDelete();

            $table->unsignedSmallInteger('orden');

            // Rol requerido para aprobar este paso
            $table->unsignedBigInteger('rol_requerido');
            $table->foreign('rol_requerido')
                  ->references('id_rol')->on('rol')
                  ->noActionOnDelete();

            $table->unsignedSmallInteger('cantidad_aprobadores')->default(1);

            // Estado que se asigna a la evidencia al completar este paso
            $table->unsignedBigInteger('estado_salida')->nullable();
            $table->foreign('estado_salida')
                  ->references('id_estado')->on('estado_documento')
                  ->noActionOnDelete();

            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 7. EJECUCIONES DEL FLUJO
         |    Una por evidencia: rastrea en qué paso va y su estado.
         ══════════════════════════════════════════════════════ */
        Schema::create('flujo_ejecucion', function (Blueprint $table) {
            $table->id('id_ejecucion');

            $table->unsignedBigInteger('id_evidencia');
            $table->foreign('id_evidencia')
                  ->references('id_evidencia')->on('evidencias')
                  ->noActionOnDelete();

            // Versión del documento que se está revisando
            $table->unsignedBigInteger('id_version')->nullable();
            $table->foreign('id_version')
                  ->references('id_version')->on('version_documento')
                  ->nullOnDelete();

            $table->unsignedBigInteger('id_flujo');
            $table->foreign('id_flujo')
                  ->references('id_flujo')->on('flujo')
                  ->noActionOnDelete();

            // Paso actual en el flujo (null = flujo finalizado o aún no iniciado)
            $table->unsignedBigInteger('paso_actual')->nullable();
            $table->foreign('paso_actual')
                  ->references('id_paso')->on('flujo_paso')
                  ->nullOnDelete();

            // Estado actual del documento según el flujo
            $table->unsignedBigInteger('estado_actual');
            $table->foreign('estado_actual')
                  ->references('id_estado')->on('estado_documento')
                  ->noActionOnDelete();

            $table->timestamp('iniciado_at')->nullable();
            $table->timestamp('finalizado_at')->nullable();
            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 8. HISTORIAL DEL FLUJO
         |    Registro de cada decisión tomada sobre una evidencia.
         ══════════════════════════════════════════════════════ */
        Schema::create('flujo_historial', function (Blueprint $table) {
            $table->id('id_historial');

            $table->unsignedBigInteger('id_ejecucion');
            $table->foreign('id_ejecucion')
                  ->references('id_ejecucion')->on('flujo_ejecucion')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('id_paso')->nullable();
            $table->foreign('id_paso')
                  ->references('id_paso')->on('flujo_paso')
                  ->nullOnDelete();

            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->enum('decision', ['iniciado', 'aprobado', 'rechazado', 'avanzado', 'reiniciado']);
            $table->text('comentario')->nullable();
            $table->timestamp('fecha');
            $table->timestamps();
        });

        /* ══════════════════════════════════════════════════════
         | 9. RESULTADOS
         |    Relación polimórfica manual: apunta a factor,
         |    característica o aspecto según tipo_relacion.
         ══════════════════════════════════════════════════════ */
        Schema::create('resultados', function (Blueprint $table) {
            $table->id('id_resultado');

            $table->string('name', 255);
            $table->text('description')->nullable();

            $table->enum('tipo_relacion', ['factor', 'caracteristica', 'aspecto']);
            $table->unsignedBigInteger('id_referencia');
            // Sin FK: id_referencia puede apuntar a 3 tablas distintas.
            // La integridad se valida en ResultadoRequest.

            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')
                  ->references('id_status')->on('status_cna')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
                  ->references('id')->on('users')
                  ->noActionOnDelete();

            $table->timestamps();

            $table->index(['tipo_relacion', 'id_referencia'], 'idx_resultado_relacion');
        });

        /* ══════════════════════════════════════════════════════
         | 10. PIVOTE: resultado ↔ evidencia
         |     Solo evidencias con estado Aprobado (id_estado=3)
         |     pueden anexarse a un resultado.
         ══════════════════════════════════════════════════════ */
        Schema::create('resultado_evidencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('resultado_id');
            $table->foreign('resultado_id')
                  ->references('id_resultado')->on('resultados')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('evidencia_id');
            $table->foreign('evidencia_id')
                  ->references('id_evidencia')->on('evidencias')
                  ->noActionOnDelete();

            $table->unsignedBigInteger('anexado_por')->nullable();
            $table->foreign('anexado_por')
                  ->references('id')->on('users')
                  ->nullOnDelete();

            $table->timestamps();

            $table->unique(['resultado_id', 'evidencia_id']);
        });
    }

    public function down(): void
    {
        // Orden inverso al de creación para respetar las FK
        Schema::dropIfExists('resultado_evidencia');
        Schema::dropIfExists('resultados');
        Schema::dropIfExists('flujo_historial');
        Schema::dropIfExists('flujo_ejecucion');
        Schema::dropIfExists('flujo_paso');
        Schema::dropIfExists('flujo');
        Schema::dropIfExists('version_documento');
        Schema::dropIfExists('evidencia_aspecto');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('estado_documento');
    }
};
