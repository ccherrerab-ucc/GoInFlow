<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';
    public $timestamps = true;

    protected $fillable = [
        'tabla', 
        'id_registro', 
        'atributo', 
        'operacion', 
        'valor_antiguo', 
        'valor_nuevo',
        'modificado_por', 
        'ip_address', 
        'fecha_modificacion',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'modificado_por', 'id_usuario');
    }
}