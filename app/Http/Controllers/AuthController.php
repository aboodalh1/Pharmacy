<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function regester(Request $request){
        $attrs= $request->validate([
        'name'=> 'required|string',
        'phone'=>'required|unique:users|digits:10|regex:/^[0-9]+$/',
        'email'=>'required|unique:users|email',
        'password'=>'required|min:6|confirmed',
        'notiToken'=>'required|string'
    ]);



    $user = User::create([
        'name'=>$attrs['name'],
        'phone'=>$attrs['phone'],
        'email'=>$attrs['email'],
        'password'=>bcrypt($attrs['password']),
        'notiToken'=>$attrs['notiToken'],

        //'remember_token'=>

    ]);
        $token=$user->createToken('secret')->plainTextToken;

    return response()->json([
        'message'=>'succesfully registed',
        'user'=>$user,
        'token'=>$token
    ],200);
}

public function depotRegester(Request $request){
    $attrs= $request->validate([
    'name'=> 'required|string',
    'phone'=>'required|unique:users|digits:10|regex:/^[0-9]+$/',
    'email'=>'required|unique:users|email',
    'password'=>'required|min:6|confirmed',
    'notiToken'=>'required|string',
]);



$user = User::create([
    'name'=>$attrs['name'],
    'phone'=>$attrs['phone'],
    'email'=>$attrs['email'],
    'password'=>bcrypt($attrs['password']),
    'role'=>'1',
    'notiToken'=>$attrs['notiToken'],


    //'remember_token'=>

]);
    $token=$user->createToken('secret')->plainTextToken;

return response()->json([
    'message'=>'succesfully registed',
    'user'=>$user,
    'token'=>$token
],200);
}

public function xlogin(Request $request){
    $attrs= $request->validate([
        'email' => 'required_without:phone',
        'phone' => 'required_without:email',
        'password' => 'required',

]);
    if(!Auth::attempt($attrs))
        {
            return response([
                'message'=>'the email or phone or password is wronge'
            ],403);
        }
        if(Auth::user()->role=='1')
        {
            return response([
                'message'=>'no accses'
            ],403);
        }
        $attrs1= $request->validate([
            'notiToken'=>'required|string'
    ]);

    auth()->user()->update([
            'notiToken'=>$attrs1['notiToken']
        ]);
return response()->json([
    'message'=>'succesfully logged',
    'user'=>auth()->user(),
    'token'=>auth()->user()->createToken('secret')->plainTextToken,
],200);

}

public function ylogin(Request $request){
    $attrs= $request->validate([
        'email' => 'required_without:phone',
        'phone' => 'required_without:email',
        'password' => 'required',

]);
    if(!Auth::attempt($attrs))
        {
            return response([
                'message'=>'the email or phone or password is wronge'
            ],403);
        }
        if(Auth::user()->role=='0')
        {
            return response([
                'message'=>'no accses'
            ],403);
        }
        $attrs1= $request->validate([
            'notiToken'=>'required|string'
    ]);

    auth()->user()->update([
            'notiToken'=>$attrs1['notiToken']
        ]);
return response()->json([
    'message'=>'succesfully logged',
    'user'=>auth()->user(),
    'token'=>auth()->user()->createToken('secret')->plainTextToken,
],200);

}

public function logout() {
    auth()->user()->tokens()->delete();
    return response()->json([
        'message'=>'logout succssesful'
    ]);}

public function depotLogout() {
    auth()->user()->tokens()->delete();
    return response()->json([
        'message'=>'logout succssesful'
    ]);}



public function update(Request $request)
{
    $attrs=$request->validate([
        'name' => 'required|string',
        'phone' => 'required|digits:10|regex:/^[0-9]+$/',
        'password' => 'required',
    ]);


 //   $image =$this->saveImage($request->image,'profiles');

    if (Hash::check($attrs['password'], auth()->user()->password))
    {
    auth()->user()->update([
        'name' => $attrs['name'],
        'phone' => $attrs['phone'],
    ]);

    return response()->json([
        'message' => 'User updated',
        'user' =>auth()->user()
    ],200);}

    return response([
        'message'=>'the password is wronge'
    ],403);

    }

public function updatePassword(Request $request){
    $attrs=$request->validate([
        'password'=>'required|min:6|confirmed',
        'oldPassword' => 'required',
    ]);

    if (Hash::check($attrs['oldPassword'], auth()->user()->password))
    {
    auth()->user()->update([
        'password' =>bcrypt($attrs['password']),
    ]);

    return response()->json([
        'message' => 'password updated',
        'user' =>auth()->user()
    ],200);}

    return response([
        'message'=>'the password is wronge'
    ],403);

    }

    public function updateEmail(Request $request){
        $attrs=$request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if (Hash::check($attrs['password'], auth()->user()->password))
        {
        auth()->user()->update([
            'email' => $attrs['email'],
        ]);

        return response()->json([
            'message' => 'email updated',
            'user' =>auth()->user()
        ],200);}

        return response([
            'message'=>'the password is wronge'
        ],403);

        }

}

