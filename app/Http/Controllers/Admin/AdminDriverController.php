<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Driver;
class AdminDriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
	
	protected function guard() {
		return auth()->guard('admin');
	}
	
    protected function index() {
        $drivers = Driver::all();
        $vehicles =array();
        foreach ($drivers as $driver) {
            $vehicles[] = DB::table('vehicles')->where('driver_id', $driver->driver_id)->select('vehicle_id')->get();
        }
        return view('driver.drivers', ['drivers'=>$drivers, 'vehicles'=>$vehicles]);
    }

    protected function destroy($driver_id) {
        Driver::destroy($driver_id);
        return redirect('drivers')->with('success','Driver is deleted successfully');
    }
}
