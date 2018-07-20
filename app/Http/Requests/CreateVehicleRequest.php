<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //$vehicle_id = $this->route('vehicle');
        //return auth()->check() && Vehicle::where('vehicle_id', $vehicle_id)->where('user_id', auth()->user()->user_id)->exists();
        //authorize must be updated ***
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehicle_name' => 'required|string|max:50',
            'vehicle_number' => 'required|string|unique:vehicles|max:50',
            'driver_id' => 'nullable|unique:vehicles',
            'gps_model' => 'required|string',
            'sim_number' => 'required|unique:vehicles',
            'imei' => 'required|unique:vehicles',
            'description' => 'text|nullable|max:150',

        ];
    }
}
