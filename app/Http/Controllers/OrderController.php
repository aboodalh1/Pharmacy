<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller
{

    public function noti($it,$token)
    {
        $SERVER_API_KEY = 'AAAAGyAN5Fk:APA91bGR6Mu_KF7di9a0qEJGN9bfIpEnhTm_5UQTY9jaEP7xRC5Vki5G97LGGex4IokawMHupG1VfkKx2HKCB5r4wa4034eDvft5SwaUxdrdjOg4gFkWHmcKeB4pFnplU4-THIhH3CTe';

    $token_1 =$token;

    $data = [

        "registration_ids" => [
            $token_1
        ],

        "notification" => [

            "title" => 'hey',

            "body" => $it,

            "sound"=> "default" // required for sound on ios

        ],

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);




    }
    public function create_order(Request $request)
    {
    $orders = $request->input('orders');
    $order = Order::create([
        'user_id' => Auth::user()->id,
        'payment_state' => 'unpaid',
    ]);

    $carts = []; // Initialize an empty array to store the created carts

    foreach ($orders as $it) {
        $cart = Cart::create([
            'depotMedicines_id' => $it['id'],
            'order_id' => $order->id,
            'quantity' => $it['quantity']
        ]);

        $carts[] = $cart; // Push the created $cart into the $carts array
    }


    return response()->json(['order' => $order, 'carts' => $carts]);
}
    public function ordersOfDepot()
    {
        $it=auth()->user()->id;
        return response([
            'orders'=>Order::whereHas('carts.depotmedicines', function ($query) use ($it) {
                $query->where('depot_id', $it);
            })->get()
        ],200);
    }

    public function index()
    {
        return response([
            'orders'=>Order::where('user_id',auth()->user()->id)->get()
        ],200);
    }

    public function orderContent($id)
    {
        return response([
            'carts'=>cart::where('order_id',$id)->with("depotmedicines.medicines","depotmedicines.classifications")->get()
        ],200);
    }

    public function deleteOrder($id)
    {
        $eorder = Order::where(['id' => $id, 'state' => 'pending'])->first();

        if (!$eorder) {
            return response()->json([
                'message' => 'You cannot remove the order',
            ],200);
        } else {
            $toRemove = Order::where('id', $id)->delete();
            return response()->json([
                'message' => 'The order was deleted successfully',
                'deletedone' => $toRemove,
            ], 200);
        }
    }


    public function preparingOrder($id)
    {
        $accOrder = Order::where(['id' => $id, 'state' => 'pending'])->first();

        if ($accOrder) {
            $accOrder->update(['state' => 'preparing']);

            $phar_id=$accOrder->user_id;
            $phar=User::find($phar_id);
            $token=$phar->notiToken;
            $this->noti("your order is preparing",$token);
            return response([
                'message' => 'the order accepted',
                'state' => 'preparing'
            ],200);
        }
        else{
            return response()->json(['message' => 'it is not possible to edit due to preparing'], 403);
        }

    }

public function has_been_sentOrder($id)
{
    $accOrder = Order::where([['id' , $id],[ 'state', 'preparing']])->first();

    if ($accOrder) {
        $cartsOrder = Cart::where('order_id', $id)->get();

        foreach ($cartsOrder as $cart)
        {
            // dd($cart);
        //  $currQuantity=Depot::find( $cart->id );
        $currQuantity=Depot::where('id',$cart->depotMedicines_id)->first();
            // dd($currQuantity);
            // return
        if($cart->quantity > $currQuantity->quintity){
            return response()->json([
                'message'=>'the require quantity is larger than currentQuantity'
            ]);}
        else{
            $currQuantity->quintity-=$cart->quantity;
            $currQuantity->save();}

        $accOrder->update(['state' => 'has_been_sent']);
        }

        // $phar_id=$accOrder->user_id;
        //     $phar=User::find($phar_id);
        //     $token=$phar->notiToken;
        //     $this->noti("your order has been sent",$token);

        return response([
            'message' => 'the order accepted',
            'state' => 'has_been_sent'
        ],200);
    }
    else{
        return response()->json(['message' => 'it is not possible to edit due to has_been_sent'], 403);
    }

}

    public function receivedOrder($id)
    {
        $accOrder = Order::where(['id' => $id, 'state' => 'has_been_sent'])->first();

        if ($accOrder) {
            $accOrder->update(['state' => 'received']);

            $phar_id=$accOrder->user_id;
            $phar=User::find($phar_id);
            $token=$phar->notiToken;
            $this->noti("your order received ",$token);

            return response([
                'message' => 'the order accepted',
                'state' => 'received'
            ],200);
        }
        else{
            return response()->json(['message' => 'it is not possible to edit due to received'], 403);
        }

    }


    public function paidOrder($id)
    {
        $accOrder = Order::where([
            ['id' , $id],
            ['payment_state','unpaid']
            ])->first();
        // dd($accOrder);

        if ($accOrder) {
            $accOrder->update(['payment_state' => 'paid']);

            $phar_id=$accOrder->user_id;
            $phar=Order::find($id);
            $user = DB::table('users')->where('id',$phar->user_id)->first();
            $token=$user->notiToken;
            // dd($token);
            $this->noti("your order is paid ",$token);

            return response([
                'message' => 'the order accepted',
                'state' => 'paid'
            ],200);
        }
        else{
            return response()->json(['message' => 'it is not possible to edit due to paid'], 403);
        }
    }

    public function report(Request $request)
        {
            $attrs=$request->validate([
                'first_date'=>'required|date',
                'second_date'=>'required|date',
            ]);
            if($attrs['first_date'] > $attrs['second_date'])
            return response(['message'=>'the first date is greater than the second date']);
            $it=auth()->user()->id;
            $orders=Order::with('users')
            ->whereHas('carts.depotmedicines', function ($query) use ($it) {
                $query->where('depot_id',$it);
            })
            ->whereDate('created_at', '>=',$attrs['first_date'])
            ->whereDate('created_at', '<=',$attrs['second_date'])
            ->get();

            $carts=Depot::with('medicines')
            ->whereDate('created_at', '>=',$attrs['first_date'])
            ->whereDate('created_at', '<=',$attrs['second_date'])
            ->get();

            $medicines=Depot::where('depot_id',$it)
            ->whereDate('date_of_end', '>=',$attrs['first_date'])
            ->whereDate('date_of_end', '<=',$attrs['second_date'])
            ->get();

            return response([
                'message_1'=>'orders during this period',
                'orders'=>$orders,
                'message_2'=>'mmmm',
                'carts'=>$carts,
                'message_3'=>'expired medications during this period',
                'medicines'=>$medicines

            ]);

        }

}
