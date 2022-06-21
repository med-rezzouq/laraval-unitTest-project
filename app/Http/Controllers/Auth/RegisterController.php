<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;


class RegisterController extends Controller
{


    public function __invoke(RegisterRequest $request)
    {
        $user =  User::create($request->validated());

        //when we create a record it return automaticcaly an object with status and everything
        return $user;
    }
}
