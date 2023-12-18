<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'manager_id'];

    protected $fillable = ['name', 'start_year', 'manager_id'];

    public function manager()
    {
        return $this->belongsTo(FundManager::class, 'manager_id');
    }

    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }
}
