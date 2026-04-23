<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlujoHistorial extends Model
{
    protected $table = 'flujo_historial';
    protected $primaryKey = 'id_historial';
    public $timestamps = true;

    protected $fillable = [
        'id_ejecucion', 
        'id_paso', 
        'usuario_id', 
        'decision', 
        'comentario', 
        'fecha'
    ];

    public function ejecucion()
    {
        return $this->belongsTo(FlujoEjecucion::class, 'id_ejecucion', 'id_ejecucion');
    }

    public function paso()
    {
        return $this->belongsTo(FlujoPaso::class, 'id_paso', 'id_paso');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id_usuario');
    }
}