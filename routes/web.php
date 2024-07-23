<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/home/{to}', [App\Http\Controllers\HomeController::class, 'index1']);
Route::get('/m', function () {


    $SERVER_API_KEY = 'AAAAGyAN5Fk:APA91bGR6Mu_KF7di9a0qEJGN9bfIpEnhTm_5UQTY9jaEP7xRC5Vki5G97LGGex4IokawMHupG1VfkKx2HKCB5r4wa4034eDvft5SwaUxdrdjOg4gFkWHmcKeB4pFnplU4-THIhH3CTe';

    $token_1 ='cmxwohzMQ8SlbJ1ZtYYp7l:APA91bETUttveNHq0PrbCWmdk63uOIUI23HQ-gNfM5AdwjNOOmJVagpapRabsWQUN-NyfL2KfGTvWeWTCDKZkpcgzZl7dF3ppWLb03SBMLSyqT7jpsYAo-16SgnLQMZCUe0QZLClKp-e' ;

    $data = [

        "registration_ids" => [
            $token_1
        ],

        "notification" => [

            "title" => 'aloooo',

            "body" => 'come to univecity ',

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

    dd($response);

});
