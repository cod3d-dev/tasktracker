<?php

use Illuminate\Support\Facades\Route;
use App\Models\Mail;

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

Route::get('/mails', function () {
    $new_mails = Mail::where('processed', false)->get();
    return view('mail', ['mails' => $new_mails]);
});

Route::get('/timer', function () {
    return view('timer');
});
