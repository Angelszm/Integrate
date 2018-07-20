<?php

namespace App\Http\Controllers\Driver\DriverAuth;

use App\Http\Requests\CreateDriverRequest;
use App\Http\Controllers\Controller;
use App\Notifications\DriverRegisteredSuccessfully;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class DriverRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    protected function showRegistrationForm() {
        return view('driver.driver-auth.driver-register');
    }

    protected function store(CreateDriverRequest $request) {
        $driver = Driver::create([
            'driver_name' => $request->driver_name,
            'user_id' => auth()->admin()->admin_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'activatiion_code' => str_random(30).time(),
        ]);
        //$driver->notify(new DriverRegisteredSuccessfully($driver));
        $driver->save();
        return redirect()->route('/drivers')->with(['success'=>'New driver account is registered. The driver will receive an email to activate the account.']);
    }
}
