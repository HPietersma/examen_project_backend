<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            http_response_code(401);
            die('{ "message": "Please add [Accept: application/json] to your request headers" }');
        }
    }
}
