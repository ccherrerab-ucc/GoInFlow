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
        /* ═══════════════════════════════════════════════
         | 1. ESTADOS DE DOCUMENTO
         |    Catálogo: Borrador | En revisión | Aprobado | Rechazado
         ═══════════════════════════════════════════════ */
        Schema::create('estado_documento', function (Blueprint $table) {
            $table->id('id_estado');
            $table->string('name');
            $table->timestamps();
        });
 
        /* ═══════════════════════════════════════════════
         | 2. EVIDENCIAS
         |    Almacena el archivo como base64 en la BD
         ═══════════════════════════════════════════════ */
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id('id_evidencia');
 
            // Datos descriptivos
            $table->string('name');
            $table->text('description')->nullable();
 
            // Archivo almacenado como base64
            $table->string('nombre_archivo');
            $table->string('tipo_archivo');        // mime type: application/pdf, etc.
            $table->longText('archivo_base64');          // contenido del archivo en base64
            $table->unsignedBigInteger('tamano_bytes');  // tamaño original en bytes
 
            // Control de versiones
            $table->unsignedInteger('version_actual')->default(1);
 
            // Vigencia del documento
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
 
            // Estado del documento (Borrador / En revisión / Aprobado / Rechazado)
            $table->foreignId('estado_id')
                  ->constrained('estado_documento', 'id_estado')
                  ->restrictOnDelete();
 
            // Estado CNA (tabla ya existente)
            $table->foreignId('status_id')
                  ->constrained('status_cna', 'id_status')
                  ->restrictOnDelete();
 
            // Auditoría — quién creó y quién actualizó
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
 
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
 
            $table->timestamps();
        });
 
        /* ═══════════════════════════════════════════════
         | 3. TABLA PIVOTE: evidencia ↔ aspecto
         |    Una evidencia puede asociarse a N aspectos
         |    Un aspecto puede tener N evidencias
         ═══════════════════════════════════════════════ */
        Schema::create('evidencia_aspecto', function (Blueprint $table) {
            $table->id();
 
            $table->foreignId('evidencia_id')
                  ->constrained('evidencias', 'id_evidencia')
                  ->cascadeOnDelete();  // si se elimina la evidencia, se elimina la asociación
 
            $table->foreignId('aspecto_id')
                  ->constrained('aspectos', 'id_aspecto')
                  ->cascadeOnDelete();  // si se elimina el aspecto, se elimina la asociación
 
            $table->timestamps();
 
            // No permitir duplicados: misma evidencia-aspecto solo una vez
            $table->unique(['evidencia_id', 'aspecto_id']);
        });
 
        /* ═══════════════════════════════════════════════
         | 4. HISTORIAL DE VERSIONES
         |    Cada vez que se sube un nuevo archivo
         |    se registra aquí la versión anterior
         ═══════════════════════════════════════════════ */
        Schema::create('version_documento', function (Blueprint $table) {
            $table->id('id_version');
 
            $table->foreignId('evidencia_id')
                  ->constrained('evidencias', 'id_evidencia')
                  ->cascadeOnDelete();
 
            $table->unsignedInteger('numero_version');
 
            // Copia del archivo en esa versión
            $table->string('nombre_archivo', 255);
            $table->string('tipo_archivo', 100);
            $table->longText('archivo_base64');
            $table->unsignedBigInteger('tamano_bytes');
 
            // Nota del usuario al subir esta versión
            $table->text('comentario')->nullable();
 
            // Quién subió esta versión
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
 
            $table->timestamps();
        });
 
        /* ═══════════════════════════════════════════════
         | 5. RESULTADOS
         |    Asociación polimórfica manual:
         |    tipo_relacion indica a qué tabla apunta id_referencia
         |    'factor' | 'caracteristica' | 'aspecto'
         ═══════════════════════════════════════════════ */
        Schema::create('resultados', function (Blueprint $table) {
            $table->id('id_resultado');
 
            $table->string('name', 255);
            $table->text('description')->nullable();
 
            // Relación polimórfica manual
            $table->enum('tipo_relacion', ['factor', 'caracteristica', 'aspecto']);
            $table->unsignedBigInteger('id_referencia');
            // Nota: no se pone FK en id_referencia porque apunta a 3 tablas distintas.
            // La integridad se valida en el FormRequest (ResultadoRequest).
 
            // Vigencia
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
 
            // Estado CNA
            $table->foreignId('status_id')
                  ->constrained('status_cna', 'id_status')
                  ->restrictOnDelete();
 
            // Auditoría
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
 
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users', 'id')
                  ->nullOnDelete();
 
            $table->timestamps();
 
            // Índice compuesto para búsquedas por tipo + referencia
            $table->index(['tipo_relacion', 'id_referencia'], 'idx_resultado_relacion');
        });
    }
 
    public function down(): void
    {
        // Se eliminan en orden inverso para respetar las FK
        Schema::dropIfExists('resultados');
        Schema::dropIfExists('version_documento');
        Schema::dropIfExists('evidencia_aspecto');
        Schema::dropIfExists('evidencias');
        Schema::dropIfExists('estado_documento');
    }
};
