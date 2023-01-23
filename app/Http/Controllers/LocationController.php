<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\State;
use App\Models\District;
use App\Models\Location;
use Exception;

class LocationController extends Controller
{
    /**
     *
    */
    public function showLocation(){
        $district       = District::select('id','title')->where('status','active')->get();
        $state          = State::select('id','title')->where('status','active')->get();
        $location       = Location::where('status','active')->get();
        $data           = [
            'district'  => $district,
            'state'     => $state,
            'location'  => $location
        ];
        return view('admin.location.location',$data);
    }
    /**
     *
     */
    public function saveLocation(Request $request){
        try{
            $rules = [
                'state'             => 'required',
                'district'          => 'required',
                'location'          => 'required',
            ];
            $messages = [
                'state.required'        => 'State is required',
                'district.required'     => 'District is required',
                'location.required'     => 'Location is required',
            ];
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->location_id!=""){
                $location               = Location::where('id',$request->location_id)->first();
            }else{
                $location               = new Location();
            }
                $location->district_id	= $request->district;
                $location->state_id	    = $request->state;
                $location->title	    = $request->location;
                $location->description  = $request->description;
                $location->save();
            if($location){
                return response()->json(['status'=>true,'message'=>'Success']);
            }else{
                return response()->json(['status'=>false,'message'=>'Error']);
            }
        } catch (Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     *
     */
    public function editLocation($id){
        $id             = Crypt::decrypt($id);
        $state          = State::select('id','title')->where('status','active')->get();
        $location       = Location::findOrFail($id);
        $data           = [
            'location'  => $location,
            'state'     => $state
        ];
        return view('admin.location.editLocation',$data);
    }
     /**
     *
     */
    public function fetchLocation(Request $request){
        $location    = Location::select('id','title')->where('district_id',$request->district_id)->get();
        $data           = [
            'location' => $location,
        ];
        return response()->json($data);
    }
}
