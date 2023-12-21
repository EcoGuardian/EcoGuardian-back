<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude', 'type_id'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
