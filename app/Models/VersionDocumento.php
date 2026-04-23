<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersionDocumento extends Model
{
    protected $table = 'version_documento';
    protected $primaryKey = 'id_version';
    public $timestamps = true;

    protected $fillable = [
        'id_evidencia', 'numero_version', 'nombre_archivo', 'ruta_archivo',
        'mime_type', 'tamano_bytes', 'comentario', 'id_estado', 'created_at', 'created_by',
    ];

    public function evidencia()
    {
        return $this->belongsTo(Evidencia::class, 'id_evidencia', 'id_evidencia');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoDocumento::class, 'id_estado', 'id_estado');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function flujoEjecuciones()
    {
        return $this->hasMany(FlujoEjecucion::class, 'id_version', 'id_version');
    }

    public function versionHistorial()
    {
        return $this->hasMany(FlujoHistorial::class, 'id_version', 'id_version');
    }

    public function scopeUniqueVersion($query, $idEvidencia, $numeroVersion)
    {
        return $query->where('id_evidencia', $idEvidencia)
                     ->where('numero_version', $numeroVersion);
    }
}