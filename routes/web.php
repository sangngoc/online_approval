<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemOwnerController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/new', 'App\Http\Controllers\RequestsController@index')
    ->middleware(['auth', 'verified'])->name('new');

Route::get('/req_detail', function () {
    return view('request_approve.detail');
})->middleware(['auth', 'verified'])->name('req_detail');
Route::post('/req_detail', function () {
    return view('request_approve.detail');
})->middleware(['auth', 'verified'])->name('req_detail');

Route::get('/requests', 'App\Http\Controllers\RequestsController@index')
    ->middleware(['auth', 'verified'])->name('requests');
Route::post('/requests', 'App\Http\Controllers\RequestsController@store')
    ->middleware(['auth', 'verified'])->name('requests');
Route::get('/check_requests', 'App\Http\Controllers\RequestApproveController@index')
    ->middleware(['auth', 'verified'])->name('check');
Route::post('/check_requests', 'App\Http\Controllers\RequestApproveController@store')
    ->middleware(['auth', 'verified'])->name('check');
    

Route::get('/redo_detail', function () {
    return view('requests.detail');
})->middleware(['auth', 'verified'])->name('redo_detail');
Route::post('/redo_detail', function () {
    return view('requests.detail');
})->middleware(['auth', 'verified'])->name('redo_detail');    

Route::get('/revise_requests', 'App\Http\Controllers\ReviseRequestController@index')
    ->middleware(['auth', 'verified'])->name('revise_req');
Route::post('/revise_requests', 'App\Http\Controllers\ReviseRequestController@store')
    ->middleware(['auth', 'verified'])->name('revise_req');

Route::get('/down/{req_id}/{fname}', 'App\Http\Controllers\FileController@Download')
->middleware(['auth', 'verified'])->name('down');;
    // Route::post('/down', 'FileController@Download')->name('down');

Route::get('/req_history', function(){
    return view('requests.history');
})->middleware(['auth', 'verified'])->name('req_history');
Route::get('/history_detail', function(){
    return view('requests.h_detail',['from_id'=>$_GET['from_id'] ]);
})->middleware(['auth', 'verified'])->name('history_detail');

Route::post('/getSysID', 'App\Http\Controllers\SystemOwnerController@getSysID')
    ->middleware(['auth', 'verified'])->name('getSysID');
Route::post('/getTypeID', 'App\Http\Controllers\RequestTypeController@getTypeID')
    ->middleware(['auth', 'verified'])->name('getTypeID');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





    //ADMIN route
Route::get('/setup', function(){
    return view('setup.setup');
})->middleware(['auth', 'verified'])->name('setup');

Route::get('/setSys', function(){
    return view('setup.addSys');
})->middleware(['auth', 'verified'])->name('setSys');
//luu system owner moi
Route::post('/setSys', 'App\Http\Controllers\SystemOwnerController@store')
 ->middleware(['auth', 'verified'])->name('sys_store');
//hien thi admin cua sys
Route::get('/setSysEmp', function(){
    return view('setup.addSys');
})->middleware(['auth', 'verified'])->name('sys_store_emp');
Route::get('/addSysEmp', 'App\Http\Controllers\SystemOwnerController@store_emp')
 ->middleware(['auth', 'verified'])->name('sys_add_emp');

//cap nhat ad
Route::post('/setSysEmp', 'App\Http\Controllers\SystemOwnerController@update_emp')
 ->middleware(['auth', 'verified'])->name('sys_store_emp');
//them admin
Route::post('/addSysEmp', 'App\Http\Controllers\SystemOwnerController@store_emp')
 ->middleware(['auth', 'verified'])->name('sys_add_emp');

 
 //them local admin (quyen tao nguoi dung tai local)
Route::get('/add_local',function(){
    return view('setup.addLocalAdmin');
})
->middleware(['auth', 'verified'])->name('add_local');
Route::post('/add_local','App\Http\Controllers\EmployeeController@store_admin')
->middleware(['auth', 'verified'])->name('add_local');


Route::get('/setType', function(){
    return view('setup.addType');
})->middleware(['auth', 'verified'])->name('setType');
Route::get('/getType', function(){
    return view('setup.addType');
})->middleware(['auth', 'verified'])->name('getType');

Route::post('/system_owners', 'App\Http\Controllers\SystemOwnerController@store_type')
 ->middleware(['auth', 'verified'])->name('request_type');
//sua type name

Route::get('/edit_route/{type_id}', 'App\Http\Controllers\RequestRouteController@index')
->middleware(['auth', 'verified'])->name('edit');
Route::get('/edit_type', function(){
    return view('setup.addType');
})
->middleware(['auth', 'verified'])->name('edit_p');

Route::post('/edit_type', 'App\Http\Controllers\RequestTypeController@store')
->middleware(['auth', 'verified'])->name('edit_type');




Route::get('/get_route', function(){
    return view('request_type.addRoute');
})
->middleware(['auth', 'verified'])->name('get_route');
//them route
Route::post('/edit_route', 'App\Http\Controllers\RequestRouteController@store')
->middleware(['auth', 'verified'])->name('edit_p');

Route::post('/getRouteID', 'App\Http\Controllers\RequestRouteController@getRouteID')
    ->middleware(['auth', 'verified'])->name('getRouteID');


Route::get('/importCSV', function(){
    return view('setup.importEmpRoute');
})
->middleware(['auth', 'verified'])->name('importCSV');
Route::post('/importCSV', 'App\Http\Controllers\FileController@upCSV')
->middleware(['auth', 'verified'])->name('importCSV');

Route::get('/export', 'App\Http\Controllers\FileController@export')
->middleware(['auth', 'verified'])->name('export');
Route::get('/exportcsv', 'App\Http\Controllers\FileController@exportCSV')
->middleware(['auth', 'verified'])->name('exportCSV');


//them local emp
Route::get('/add_emp', 'App\Http\Controllers\EmployeeController@index')
    ->middleware(['auth', 'verified'])->name('add_emp');
Route::post('/add_emp', 'App\Http\Controllers\EmployeeController@store')
    ->middleware(['auth', 'verified'])->name('add_emp');

Route::post('/getSec', 'App\Http\Controllers\EmployeeController@getSecID')
    ->middleware(['auth', 'verified'])->name('getSec');
Route::post('/getPos', 'App\Http\Controllers\EmployeeController@getPos')
    ->middleware(['auth', 'verified'])->name('getPos');

Route::get('/update_emp/{user_id}', 'App\Http\Controllers\EmployeeController@update')
    ->middleware(['auth', 'verified'])->name('update_emp');
Route::post('/update_emp', 'App\Http\Controllers\EmployeeController@up_emp')
    ->middleware(['auth', 'verified'])->name('up_emp');

Route::get('/t','App\Http\Controllers\t@index' );

require __DIR__.'/auth.php';
