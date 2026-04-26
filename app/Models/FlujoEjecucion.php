<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlujoEjecucion extends Model
{
    protected $table = 'flujo_ejecucion';
    protected $primaryKey = 'id_ejecucion';
    public $timestamps = true;

    protected $fillable = [
        'id_evidencia', 'id_version', 'id_flujo', 'paso_actual', 'estado_actual', 'iniciado_at', 'finalizado_at'
    ];

    protected $casts = [
        'estado_actual' => 'integer',
        'iniciado_at'   => 'datetime',
        'finalizado_at' => 'datetime',
    ];

    public function evidencia()
    {
        return $this->belongsTo(Evidencia::class, 'id_evidencia', 'id_evidencia');
    }

    public function versionDocumento()
    {
        return $this->belongsTo(VersionDocumento::class, 'id_version', 'id_version');
    }

    public function flujo()
    {
        return $this->belongsTo(Flujo::class, 'id_flujo', 'id_flujo');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoDocumento::class, 'estado_actual', 'id_estado');
    }

    public function pasoActual()
    {
        return $this->belongsTo(FlujoPaso::class, 'paso_actual', 'id_paso');
    }

    public function historial()
    {
        return $this->hasMany(FlujoHistorial::class, 'id_ejecucion', 'id_ejecucion');
    }
}