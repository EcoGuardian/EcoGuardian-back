<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'type_id', 'city'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
