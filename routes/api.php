<?php

use Illuminate\Http\Request;


Route::resource('taks', 'TasksController', [
    'except' => ['create']
]);

