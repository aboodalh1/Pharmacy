<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{

    protected $fillable = [
        'name',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function depots(){
        return $this->hasMany(Depot::class);
    }
    use HasFactory;
}
