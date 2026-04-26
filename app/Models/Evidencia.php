<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    protected $primaryKey = 'id_evidencia';
    public $timestamps = true;

    protected $fillable = [
        'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin',
        'estado_actual', 'id_aspecto',
        'created_by', 'updated_by', 'status_id',
    ];

    protected $casts = [
        'fecha_inicio'  => 'datetime',
        'fecha_fin'     => 'datetime',
        'estado_actual' => 'integer',
    ];

    public function estadoActual()
    {
        return $this->belongsTo(EstadoDocumento::class, 'estado_actual', 'id_estado');
    }

    public function aspecto()
    {
        return $this->belongsTo(Aspecto::class, 'id_aspecto', 'id_aspecto');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id_status');
    }

    public function versionDocumentos()
    {
        return $this->hasMany(VersionDocumento::class, 'id_evidencia', 'id_evidencia');
    }

    public function flujoEjecuciones()
    {
        return $this->hasMany(FlujoEjecucion::class, 'id_evidencia', 'id_evidencia');
    }

    public function resultados()
    {
        return $this->belongsToMany(
            \App\Models\Resultado::class,
            'resultado_evidencia',
            'evidencia_id',
            'resultado_id'
        )->withPivot('anexado_por');
    }
}