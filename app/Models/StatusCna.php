<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class StatusCna extends Model
{
    protected $table      = 'status_cna';
    protected $primaryKey = 'id_status';
 
    protected $fillable = ['name'];
 
    public function factores(): HasMany
    {
        return $this->hasMany(Factor::class, 'status_id', 'id_status');
    }
 
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(Caracteristica::class, 'status_id', 'id_status');
    }
 
    public function aspectos(): HasMany
    {
        return $this->hasMany(Aspecto::class, 'status_id', 'id_status');
    }
}
 