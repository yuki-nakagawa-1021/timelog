<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::authenticateUsing(function (Request $request) {

            $formRequest = app(\App\Http\Requests\LoginRequest::class);

            Validator::make(
                $request->all(),
                $formRequest->rules(),
                $formRequest->messages()
            )->validate();

            $user = User::where('email', $request->email)->first();
                if (! $user || ! Hash::check($request->password, $user->password)) {
                    return null;
                }
                if ($request->is('admin/login')) {
                    if ($user->role !== 'admin') {
                        return null;
                    }
                    session(['is_admin_login' => true]);

                    return $user;
                }

                session()->forget('is_admin_login');
                return $user;
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::redirects('login', function () {
            if (session('is_admin_login')) {
                return '/admin/attendance/list';
            }

            return '/attendance';
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                if ($request->user() && $request->user()->role === 'admin') {
                    return redirect('/admin/login');
                }

                return redirect('/login');
            }
        });
    }
}
