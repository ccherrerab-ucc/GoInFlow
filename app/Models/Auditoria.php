<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table      = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    public    $timestamps = true;

    protected $fillable = [
        'objeto', 'registro', 'atributo', 'operacion',
        'valor_antiguo', 'valor_nuevo',
        'modificado_por', 'fecha_modificacion',
        'created_by', 'updated_by', 'status_id',
    ];

    protected $casts = [
        'fecha_modificacion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'modificado_por', 'id');
    }
}
