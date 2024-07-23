<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'depotMedicines_id',
        'order_id',
        'quantity',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function depotmedicines(){
        return $this->belongsTo(Depot::class,"depotMedicines_id");
    }

    public function orders(){
        return $this->belongsTo(Order::class);
    }

}
