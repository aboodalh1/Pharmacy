<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
    'scientific_name',
    'trade_name',
    'company',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function depotMedicines()
    {
        return $this->hasMany(Depot::class,'depotMedicines_id');
    }



    // public function carts()
    // {
    //     return $this->hasMany(Cart::class);
    // }



}
