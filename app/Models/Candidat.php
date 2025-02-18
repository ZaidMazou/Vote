<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birthday',
        'phoneNumber',
        'sexe',
        'profile',
        'photo',
        'idEvent'
    ];

    public function event() {
        return $this->belongsTo(Event::class , 'idEvent');
    }

    public function votes() {
        return $this->hasMany(Vote::class,'idCandidat');
  }
}
