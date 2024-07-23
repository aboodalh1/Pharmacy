<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\FavoriteController;
use App\Models\Depot;
use Illuminate\Support\Facades\Auth;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/reg',[AuthController::class,'regester']);  //signUp Pharmacy
Route::post('/login',[AuthController::class,'xlogin']);  //login Pharmacy

Route::post('/depot/regester',[AuthController::class,'depotRegester']);
Route::post('/depot/login',[AuthController::class,'ylogin']);  //login Depote

Route::group(['middleware'=>'auth:sanctum'],function(){

    // for Pharmacy {role=0}
    Route::middleware(['auth','isph'])->group(function(){
        Route::post('/updatePassword',[AuthController::class,'updatePassword']);
        Route::post('/updateEmail',[AuthController::class,'updateEmail']);
        Route::post('/update',[AuthController::class,'update']);
        Route::post('/logout',[AuthController::class,'logout']);

        Route::post('/medicine/{id}/favorites',[FavoriteController::class,'likeOrUnlike']);//like or dislike
        Route::get('/medicines/favorites',[FavoriteController::class,'favorite']);

        Route::post('/createOrder',[OrderController::class,'create_order']);

        Route::get('/indexAllOrder',[OrderController::class,'index']);
    });

    // for Depote {role=1}
    Route::middleware(['auth','isde'])->group(function(){
        Route::post('/depot/logout',[AuthController::class,'depotLogout']);
        Route::post('/store',[MedicineController::class,'add_medicine']);
        Route::post('/deleteMedicine/{med_Id}',[MedicineController::class,'deleteMedicine']);
        Route::get('/depotMedicineOfClass/{class_id}',[MedicineController::class,'indexByClassDe']);
        Route::get('/indexAllOrder/depot',[OrderController::class,'ordersOfDepot']);

        Route::post('/accepetOrder/{id}/to_preparing',[OrderController::class,'preparingOrder']);
        Route::post('/accepetOrder/{id}/to_has_been_sent',[OrderController::class,'has_been_sentOrder']);
        Route::post('/accepetOrder/{id}/to_received',[OrderController::class,'receivedOrder']);
        Route::post('/accepetOrder/{id}/paid',[OrderController::class,'paidOrder']);

        Route::post('/report',[OrderController::class,'report']);
    });

});


    Route::get('/Depotes',[DepotController::class,'index']);  //index all depotes
    Route::get('Depote/{depotId}',[DepotController::class,'indexMedOfDep']);  //index medicine of depote
    Route::get('Depote/{depotId}/Class/{classId}',
        [DepotController::class,'indexMedOfDepByClass']);  //index medicine of depote by classification


    Route::get('/Classifications',[ClassificationController::class,'index']);  //index all Classifications
    Route::get('/searchClass/{nameOfClass}',[ClassificationController::class,'searchC']);

    Route::get('/medicineOfClass/{class_id}',[MedicineController::class,'indexByClass']);
    Route::get('/search1/{dId}/{cId}/{name}',[MedicineController::class,'searchDC']);


    Route::get('/orderContent/{id}',[OrderController::class,'orderContent']);//get carts in order
    Route::delete('/deleteOrder/{id}',[OrderController::class,'deleteOrder']);


    Route::post('/edateCart/{cart_id}',[CartController::class,'editCart']);
    Route::delete('/deleteCart/{cart_id}',[CartController::class,'deleteCart']);


