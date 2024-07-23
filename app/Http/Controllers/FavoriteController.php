<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Favorite;
use App\Models\Medicine;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function likeOrUnlike($id)
    {
        $medicine=Depot::find($id);

        if(!$medicine)
        {
            return response([
                'message'=>'medicine not found'
            ],403);
        }

        $favorite=$medicine->favorites()->where('user_id',auth()->user()->id)->first();

        //if not like then like
        if(!$favorite)
        {
        Favorite::create([
            'depotMedicines_id'=>$id,
            'user_id'=>auth()->user()->id
        ]);

        return response([
            'message'=>'liked'
        ],403);
        }

        //else unlike
        $favorite->delete();

        return response([
            'message'=>'unliked'
        ],403);
    }

    public function favorite()
    {
    return response([
        'your favorite'
            => Favorite::select('*')
            ->with('depotMedicines', 'depotMedicines.medicines')
            ->where('user_id',auth()->user()->id)
            ->get()
    ], 200);
    }
}
