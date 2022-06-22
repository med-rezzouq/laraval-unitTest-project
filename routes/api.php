<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {


    Route::apiResource('todo-list', TodoListController::class);
    //the api ressource will replace all crud operations below thats why i commented them
    //but you need to writ ein terminal list routes command to show the name of passed attributs and params
    //php artisan route:list
    // Route::get('todo-list', [TodoListController::class, 'index'])->name('todo-list.index');
    // Route::get('todo-list/{list}', [TodoListController::class, 'show'])->name('todo-list.show');

    // Route::post('todo-list', [TodoListController::class, 'store'])->name('todo-list.store');

    // Route::delete('todo-list/{list}', [TodoListController::class, 'destroy'])->name('todo-list.destroy');


    // Route::patch('todo-list/{list}', [TodoListController::class, 'update'])->name('todo-list.update');

    //////////////TASKS

    Route::apiResource('todo-list.task', TaskController::class)->except('show')->shallow();

    Route::post('task/completed', [TaskController::class, 'store'])->name('task.store');
    // Route::get('task', [TaskController::class, 'index'])->name('task.index');
    // Route::post('task', [TaskController::class, 'store'])->name('task.store');
    // Route::delete('task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');


});




///////////////register routes

Route::post('/register', RegisterController::class)->name('user.register');

Route::post('/login', LoginController::class)->name('user.login');
