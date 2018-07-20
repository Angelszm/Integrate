<?php

namespace App\Http\Controllers\Driver\DriverAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverActivationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function guard() {
        return auth()->guard('driver');
    }

    public function activateDriver(Request $request, $code) {
        try {
            $driver = Driver::where('activation_code', $request->activation_code)->first();
            if(!$driver) {
                return redirect()->to('/')->with(['error'=>'Activation code is expired or invalid']);
            }
            $driver->activation_code = null;
            $driver->save();
            //auth()->login($user);
        }catch(\Exception $exception) {
            logger()->error($exception);
            return "Activation not successful.";
        }
        return redirect()->to('driver.login')->with(['success'=>'Congratulations! Your account is activated.']);;
    }
}
