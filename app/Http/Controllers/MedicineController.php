<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Depot;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\DB;
class MedicineController extends Controller
{
    public function add_medicine(Request $request)
    {
        $attrs= $request->validate([
            'scientific_name'=>'required|string',
            'trade_name'=>'required|string',
            'company'=>'required|string',
            'classification_id'=>'required',
            'quintity'=>'required|integer',
            'date_of_end'=>'required|date',
            'price'=>'required|numeric',
        ]);

        $medicine =
        Medicine::where('scientific_name',$attrs['scientific_name'])
        ->where('trade_name',$attrs['trade_name'])
        ->where('company',$attrs['company'])
        ->first();

        if($medicine){$medicineId=$medicine->id;}

        else{
            $add=Medicine::Create([
            'scientific_name'=>$attrs['scientific_name'],
            'trade_name'=>$attrs['trade_name'],
            'company'=>$attrs['company'],
            ]);
            $medicineId=$add->id;
        }

        $class=Classification::find($attrs['classification_id']);

        if(!$class)
        {
            return response([
                'message'=>'classification not found'
            ],403);
        }

        if($request->hasFile('image'))
        {

            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:2048'
            ]);

        $ext = $request->file('image')->extension();
        $final_name = date('YmdHis').'.'.$ext;
        $request->file('image')->move(public_path('uploads/'),$final_name);
        }
        else{ $final_name =null;}

        $add=Depot::Create([
            'medicine_id'=>$medicineId,
            'depot_id'=>Auth::user()->id,
            'classification_id'=>$attrs['classification_id'],
            'quintity'=>$attrs['quintity'],
            'date_of_end'=>$attrs['date_of_end'],
            'price'=>$attrs['price'],
            'image'=>$final_name,
        ]);

        return response([
            'message'=>'medicine added succses',
            'medicine'=>$add
        ],200);
    }

    public function deleteMedicine($id)
    {
        $medicine =Depot::where(['id' => $id])->first();

        if (!$medicine) {
            return response()->json([
                'message' => 'the medicine not found',
            ], 200);
        } else {
            $toRemove = Depot::where('id', $id)->delete();
            return response()->json([
                'message' => 'The medicine was deleted successfully',
                'deletedone' => $toRemove,
            ], 200);
        }
    }

    public function indexByClass($id)
    {
        $post=Classification::find($id);

        if(!$post)
        {
            return response([
                'message'=>'class not found'
            ],403);
        }

        return response([
            'the_medicines'=>Depot::where('classification_id',$id)
            ->with("medicines")->get()
        ],200);
    }

    public function indexByClassDe($id)
    {
        $post=Classification::find($id);

        if(!$post)
        {
            return response([
                'message'=>'class not found'
            ],403);
        }

        return response([
            'the_medicines'=>Depot::where('classification_id',$id)
            ->where('depot_id',auth()->user()->id)
            ->with("medicines")->get()
        ],200);
    }

    // public function index($id)
    // {
    //     $post=Medicine::find($id);

    //     if(!$post)
    //     {
    //         return response([
    //             'message'=>'Medicine not found'
    //         ],403);
    //     }

    //     return response([
    //         // 'post'=>Medicine::where('id',$id)->select('scientific_name',
    //         // 'trade_name','company','date_of_end','price')->get(),
    //         "medicine"=>Medicine::where('id',$id)->select('scientific_name',
    //             'trade_name','company','classification_id','date_of_end','price')
    //             ->with('classification:id,name')->get(),
    //     ],200);
    // }
    public function search($x)
    {
        return response([
            //DB::table('medicines')->where('scientific_name',$x)->orwhere('trade_name',$x)->get()
            Medicine::where('scientific_name',$x)->orwhere('trade_name',$x)->get()
        ],200);
    }

    public function searchDC($depot_id,$class_id,$NM)
    {
    //     $med=Medicine::where('scientific_name','like','%' . $NM . '%')
    //     ->orwhere('trade_name','like','%' . $NM . '%')->pluck('id')->toArray();
    //    // dd($med);

    //     $medicine=[];
    //     foreach ($med as $it) {
        $it1 = Depot::where('depot_id', $depot_id)
        ->where('classification_id', $class_id)
        ->whereHas('medicines', function ($query) use ($NM) {
            $query->where('scientific_name', 'like', '%' . $NM . '%');
        })
        ->with('medicines')
        ->get();
                 // ->where('medicines.scientific_name', 'like', '%' . $NM . '%')
                // ->medicines()->select('medicines.scientific_name', 'like', '%' . $NM . '%')
                // ->get();
            // $medicine = $it1;
        // }

       // echo $medicine;

        return response([
            'message'=>$it1

        ],200);
    }

}

