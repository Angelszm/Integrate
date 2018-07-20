<?php

namespace App\Http\Controllers\Vehicle;

use App\Events\Vehicle\VehicleAdded;
use App\Events\Vehicle\VehicleRemoved;
use App\Events\Vehicle\VehicleUpdated;
use App\Http\Requests\CreateVehicleRequest;
//use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
        $this->authorizeResource(Vehicle::class);
        //$user_id = auth()->user()->user_id;
    }

    public function guard() {
        return auth()->guard('admin');
    }

    protected function create() {
        //if (!empty(auth()->user()->user_id)) {
        //$user_id = auth()->user()->user_id;
        //}
        $admin_id = auth()->admin()->admin_id;
        $drivers = DB::table('drivers')->where('admin_id',$admin_id)->where('vehicle_id',null)->select('driver_id','driver_name')->get();
        //raw('select driver_id, driver_name from drivers where drivers.user_id ='.$user_id.'and drivers.vehicle_id=null');
        return view('vehicle.vehicle-create', compact('drivers'));
    }

    protected function store(CreateVehicleRequest $request) {
        //$user_id = auth()->user()->user_id;
        $admin_id = auth()->admin()->admin_id;
        $vehicle = new Vehicle([
            "vehicle_name" => $request->get('vehicle_name'),
            "vehicle_number" => $request->get('vehicle_number'),
            "driver_id" => $request->get('driver_id'),
            "admin_id" => $admin_id,
            "sim_number" => $request->get('sim_number'),
            "imei"=>$request->get('imei'),
            "gps_model" => $request->get('gps_model'),
            "description" => $request->get('description')
        ]);
        $vehicle->save();
        //if(!($vehicle->driver_id === null)) {
        //DB::table('drivers')->where('driver_id', $vehicle->driver_id)->update('vehicle_id', $vehicle->vehicle_id);
        //to be confirmed!
        //}
        //return back();
        event(new VehicleAdded($vehicle));
        return redirect('vehicles')->with('success', 'New vehicle is added successfully');
    }

    protected function index() {
        $vehicles = Vehicle::all();
        //return view('vehicle.vehicles', compact('vehicles'));
        //$drivers = [];
        $drivers=array();
        foreach ($vehicles as $vehicle) {
            //$drivers = DB::table('drivers')->where('driver_id', $vehicle->driver_id)->select('driver_name')->get();
            $drivers[] = DB::table('drivers')->where('driver_id', $vehicle->driver_id)->select('driver_name')->get();
        }
        return view('vehicle.vehicles', ['vehicles'=>$vehicles, 'drivers'=>$drivers]);
    }

    protected function edit($vehicle_id) {
        //if (!empty(auth()->user()->user_id)) {
        //$admin_id = auth()->user()->user_id;
        //}
        $admin_id = auth()->admin()->admin_id;
        $vehicle = Vehicle::find($vehicle_id);
        $drivers = DB::table('drivers')->where('admin_id',$admin_id)->where('vehicle_id',null)->orWhere('vehicle_id',$vehicle_id)->select('driver_id','driver_name')->get();
        //return view('vehicle.edit', compact('vehicle','vehicle_id'));
        return view('vehicle.vehicle-edit', ['vehicle'=>$vehicle, 'drivers'=>$drivers]);
    }

    protected function update(CreateVehicleRequest $request, $vehicle_id) {
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $vehicle->vehicle_name = $request->get('vehicle_name');
        $vehicle->vehicle_number = $request->get('vehicle_number');
        $vehicle->driver_id = $request->get('driver_id');
        $vehicle->sim_number= $request->get('sim_number');
        $vehicle->imei= $request->get('imei');
        $vehicle->gps_model = $request->get('gps_model');
        if($request->has('description')) {
            $vehicle->description= $request->get('description');
        }
        $vehicle->save();
        //if(!($vehicle->driver_id === null)) {
        //DB::table('drivers')->where('driver_id', $vehicle->driver_id)->update('vehicle_id', $vehicle->vehicle_id);
        //to be confirmed!
        //}
        event(new VehicleUpdated($vehicle));
        return redirect('vehicles')->with('success','Vehicle information is updated successfully');
    }

    protected function destroy($vehicle_id) {
        $vehicle = Vehicle::findOrFail($vehicle_id);
        event(new VehicleRemoved($vehicle));
        Vehicle::destroy($vehicle_id);
        return redirect('vehicles')->with('success','Vehicle is deleted successfully');
    }
}
