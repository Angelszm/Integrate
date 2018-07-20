<?php

namespace App\Http\Controllers\Driver\DriverAuth;

use App\Http\Requests\CreateDriverRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Driver;

class DriverLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/drivers/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function guard() {
        return auth()->guard('driver');
    }

    protected function showLoginForm() {
        return view('driver.driver-auth.driver-login');
    }

    protected function credentials(CreateDriverRequest $request) {
        //$email = $this->email();
        return [
            'email' => $request->email,
            'password' => $request->password,
            'activation_code' => null,
        ];
    }
}
