<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $table = 'resultados';
    protected $primaryKey = 'id_resultado';
    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'tipo_relacion', 'id_referencia',
        'fecha_inicio', 'fecha_fin', 'created_by', 'updated_by', 'status_id',
    ];

    protected $casts = [
        'tipo_relacion' => 'string',
    ];

    public function status()
    {
        return $this->belongsTo(StatusCna::class, 'status_id', 'id_status');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function evidencias()
    {
        return $this->belongsToMany(
            Evidencia::class,
            'resultado_evidencia',
            'resultado_id',
            'evidencia_id'
        )->withPivot('anexado_por')->withTimestamps();
    }
}
