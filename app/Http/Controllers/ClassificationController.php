<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classification;

class ClassificationController extends Controller
{
        public function addClassf(Request $request){
            $attrs= $request->validate([
            'name'=> 'required|string|unique:classifications',
        ]);

        $classification = Classification::create([
            'name'=>$attrs['name'],
        ]);
        return response()->json(['The classification '=>$classification]);
    }


    public function index()
    {
        return response([
            'classifications'=>Classification::get()

        ],200);
    }

    public function searchC($name)
    {
        return response([
            "classifications"=>Classification::where('name','like','%' . $name . '%')->get()
        ],200);
    }

}
