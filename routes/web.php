<?php

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
    return view('dashboard');
});
Route::get('/login', 'GuestController@loginUsers')->middleware('guest');
Route::get('/users', function () {
    return view('admin.admin');
});
Route::get('/new-order', function () {
    return view('waiter.new-order');
});
Route::post('/new-order/send', 'WaiterController@sendOrder');

Route::get('/orders', function () {
    return view('waiter.orders');
});
Route::get('/kitchen', function () {
    return view('kitchen.kitchen');
});

//API
//guest
Route::post('/login/check', 'GuestController@login');

//auth
Route::post('/logout', 'AuthController@logout');
Route::post('/login/user', 'AuthController@user');

//admin
Route::get('/users/get', 'UserController@users');
Route::get('/users/roles', 'UserController@userRoles');
Route::post('/users/delete', 'UserController@deleteUser');
Route::post('/users/edit', 'UserController@editUser');

//kitchen
Route::get('/orders/kitchen', 'KitchenController@getOrdersKitchen');
Route::post('/orders/kitchen/prepare', 'KitchenController@doPrepare');
Route::post('/orders/kitchen/ready', 'KitchenController@doReady');

//waiter
Route::get('/orders/waiter/menu', 'WaiterController@menu');
Route::post('/orders/waiter', 'WaiterController@getOrdersWaiter');
Route::post('/orders/waiter/close', 'WaiterController@orderItemClose');
Route::post('/orders/waiter/pay', 'WaiterController@orderPay');

