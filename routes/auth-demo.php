<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Route};

/*
|--------------------------------------------------------------------------
| Demo Auth Routes
|--------------------------------------------------------------------------
|
| These are not representative of how routes & logic would be represented 
| in a real-world application
|
| Please do not touch these as part of the technical test!
|
*/

Route::get('/whoami', [
    'as' => 'whoami',
    function (Request $request) {
        if ($request->user() === null)
            return response("Not logged in", 401);

        return $request->user();
    }
]);

Route::get('/logout', [
    'as' => 'logout',
    function (Request $request) {
        return Auth::logout();
    }
]);

Route::post('/login', [
    'as' => 'login',
    'middleware' => [\App\Http\Middleware\JsonContentType::class],
    function (Request $request) {
        $authBody = $request->only('email', 'password');

        if (Auth::attempt($authBody)) {
            return response("Authenticated as {$request->user()->email}");
        }

        return response("Invalid credentials: {$authBody['email']} / {$authBody['password']}", 401);
    }
]);
