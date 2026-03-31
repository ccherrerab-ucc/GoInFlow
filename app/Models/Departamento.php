<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento';

    protected $primaryKey = 'id_departamento';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'area_id',
        'status_id',
        'created_by',
        'updated_by',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id_area');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_area', 'area_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id_status');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    PS C:\GoInFlow> php artisan tinker
    Psy Shell v0.12.21 (PHP 8.2.0 — cli) by Justin Hileman
    New PHP manual is available (latest: 3.0.5). Update with `doc --update-manual`
    > \App\Models\Departamento::with('area')->get()
    */
}
