<?php

use Illuminate\Support\Facades\Route;

Route::any('/', 'AdminerController@index')->name('adminer.index');

