<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $fillable = [
        'medicine_id',
        'depot_id',
        'classification_id',
        'quintity',
        'date_of_end',
        'price',
        'image'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    use HasFactory;

    public function medicines()
    {
        return $this->belongsTo(Medicine::class,'medicine_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function classifications()
    {
        return $this->belongsTo(Classification::class,'classification_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class,"depotMedicines_id");
    }

    public function carts()
    {
        return $this->hasMany(Cart::class,"depotMedicines_id");
    }
}
