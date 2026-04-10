<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Caracteristica extends Model
{
    protected $table      = 'caracteristicas';
    protected $primaryKey = 'id_caracteristica';
 
    protected $fillable = [
        'name',
        'description',
        'factor_id',
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
 
    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class, 'factor_id', 'id_factor');
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
 
    public function aspectos(): HasMany
    {
        return $this->hasMany(Aspecto::class, 'caracteristica_id', 'id_caracteristica');
    }
}