<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;

class ValidateLoginRequest
{
    public function __invoke(Request $request, $next)
    {
        $formRequest = app(LoginRequest::class);

        Validator::make(
            $request->all(),
            $formRequest->rules(),
            $formRequest->messages()
        )->validate();

        return $next($request);
    }
}