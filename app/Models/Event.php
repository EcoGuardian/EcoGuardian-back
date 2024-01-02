<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'latitude', 'longitude'];


    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }

    public function likeCount()
    {
        return $this->userEvents()->count();
    }
}
