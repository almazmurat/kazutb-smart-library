<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalog', function () {
    return view('catalog');
});

Route::get('/book/{isbn}', function () {
    return view('book');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/login', function () {
    return view('auth');
});

Route::get('/book/{isbn}/read', function ($isbn) {
    return view('reader', ['isbn' => $isbn]);
});
