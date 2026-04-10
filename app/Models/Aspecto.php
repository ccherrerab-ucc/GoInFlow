<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Aspecto extends Model
{
    protected $table      = 'aspectos';
    protected $primaryKey = 'id_aspecto';
 
    protected $fillable = [
        'name',
        'description',
        'caracteristica_id',
        'responsable',
        'fecha_inicio',
        'fecha_fin',
        'created_by',
        'updated_by',
        'status_id',
    ];
 
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
    ];
 
    /* ── Relaciones ── */
 
    public function caracteristica(): BelongsTo
    {
        return $this->belongsTo(Caracteristica::class, 'caracteristica_id', 'id_caracteristica');
    }
 
    public function responsableUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable', 'id');
    }
 
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
 
    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
 
    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusCna::class, 'status_id', 'id_status');
    }
}
 