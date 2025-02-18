<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'numbreOfVote',
        'amount',
        'idCandidat'
    ];

    public function candidat() {
        return $this->belongsTo(Candidat::class , 'idCandidat');
    }

}
