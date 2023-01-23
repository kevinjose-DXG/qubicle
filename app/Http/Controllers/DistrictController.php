<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\State;
use App\Models\District;
use Exception;

class DistrictController extends Controller
{
     /**
     *
     */
    public function showDistrict(){
        $district       = District::select('id','state_id','title','status','description')->get();
        $state          = State::select('id','title')->get();
        $data           = [
            'district'  => $district,
            'state'     => $state
        ];
        return view('admin.district',$data);
    }
    /**
     *
     */
    public function saveDistrict(Request $request){
        try{
            $rules = [
                'state'             => 'required',
                'district'          => 'required',
            ];
            $messages = [
                'state.required'        => 'State is required',
                'district.required'     => 'District is required',
            ];
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->district_id!=""){
                $district               = District::with('states')->where('id',$request->district_id)->first();
            }else{
                $district               = new District();
            }
                $district->state_id	    = $request->state;
                $district->title	    = $request->district;
                $district->description  = $request->description;
                $district->save();
            if($district){
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
    public function editDistrict(Request $request){
        $id             = $request->id;
        $district       = District::findOrFail($id);
        $data           = [
            'district'  => $district
        ];
        return response()->json($data);
    }
     /**
     *
     */
    public function changeDistrictStatus(Request $request){
        $id                     = $request->district_id;
        $district               = District::where('id',$id)->first();
        $district->status       = $request->status;
        $district->save();
        if($district){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
     /**
     *
     */
    public function fetchDistrict(Request $request){
        $district    = District::select('id','title')->where('state_id',$request->state_id)->get();
        $data           = [
            'district' => $district,
        ];
        return response()->json($data);
    }
}
