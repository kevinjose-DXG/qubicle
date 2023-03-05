<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class SupportController extends Controller
{
     /**
     *
     */
    public function showSupport(){
        $support            = Support::get();
        $data               = [
            'support'       => $support
        ];
        return view('admin.support',$data);
    }
     /**
     *
     */
    public function saveSupport(Request $request){
        try{
            $rules = [
                'email'             => 'required|unique:supports,email,'.$request->support_id,
                'mobile'            =>  'required|unique:supports,mobile,'.$request->support_id,
            ];
            $messages = [
                'email.required'    => 'Email is required',
                'mobile.required'    => 'Mobile is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->support_id!=""){
                $support                 = Support::where('id',$request->support_id)->first();
            }else{
                $support                 = new Support();
            }
                $support->email	        = $request->email;
                $support->mobile	        = $request->mobile;
                $support->address	    = $request->address;
                $support->description    = $request->description;
                $support->save();
            if($support){
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
    public function editSupport(Request $request){
        $id                 = $request->id;
        $support            = Support::findOrFail($id);
        $data               = [
            'support'       => $support
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeSupportStatus(Request $request){
        $id                 = $request->slider_id;
        $state              = Support::where('id',$id)->first();
        $state->status      = $request->status;
        $state->save();
        if($state){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
