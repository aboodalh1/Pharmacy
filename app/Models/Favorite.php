<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'depotMedicines_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function depotMedicines()
    {
        return $this->belongsTo(Depot::class,'depotMedicines_id');
    }
    use HasFactory;
}
