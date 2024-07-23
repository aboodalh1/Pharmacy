<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class DepotController extends Controller
{
    public function index()
    {
        return response([
            'Depots'=>User::where('role','1')->select('id','name')->get()

        ],200);
    }

    public function indexMedOfDep($depotId)
    {
        $Depote=User::find($depotId);

        if(!$Depote||$Depote->role=="0")
        {
            return response([
                'message'=>'Depote not found'
            ],403);
        }

        $currentDate = Carbon::now();
        $endDate = $currentDate->copy()->addDays(5)->toDateString();

        return response([
            'Depots'
                => Depot::with('medicines')
                ->where('depot_id', $depotId)
                ->whereDate('date_of_end', '>=', $endDate)//$medicines = Medicine::where('end_date', '>=', now())->get();
                ->get()
        ], 200);
    }

    public function indexMedOfDepByClass($depotId,$classId)
    {
        $Depote=User::find($depotId);

        if(!$Depote||$Depote->role=="0")
        {
            return response([
                'message'=>'Depote not found'
            ],403);
        }

        $currentDate = Carbon::now();
        $endDate = $currentDate->copy()->addDays(5)->toDateString();

        return response([
            'depot_medicines'
                => Depot::with('medicines')
                ->where('classification_id',$classId)
                ->where('depot_id', $depotId)
                ->whereDate('date_of_end', '>=', $endDate)//$medicines = Medicine::where('end_date', '>=', now())->get();
                ->get()
        ], 200);
    }

}
