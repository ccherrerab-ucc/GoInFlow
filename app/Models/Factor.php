<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class Factor extends Model
{
    protected $table      = 'factors';
    protected $primaryKey = 'id_factor';
 
    protected $fillable = [
        'name',
        'description',
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
 
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(Caracteristica::class, 'factor_id', 'id_factor');
    }
}