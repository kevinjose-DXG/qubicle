<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DesignedBy;
use Exception;

class DesignedController extends Controller
{
     /**
     *
     */
    public function showDesigned(){
        $designed_by        = DesignedBy::select('id','name','mobile','email','status','description')->get();
        $data               = [
            'designed_by' => $designed_by
        ];
        return view('admin.designedBy',$data);
    }
    /**
     *
     */
    public function saveDesigned(Request $request){
        try{
            $rules = [
                'designed'             => 'required',

            ];
            $messages = [
                'designed.required'    => 'designed is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->designed_id!=""){
                $designed               = DesignedBy::where('id',$request->designed_id)->first();
            }else{
                $designed               = new DesignedBy();
            }

                $designed->name	        = $request->designed;
                $designed->mobile	    = $request->mobile;
                $designed->email	    = $request->email;
                $designed->description  = $request->description;
                $designed->save();
            if($designed){
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
    public function editDesigned(Request $request){
        $id             = $request->id;
        $designed       = DesignedBy::findOrFail($id);
        $data           = [
            'designed' => $designed
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeDesignedStatus(Request $request){
        $id                     = $request->designed_id;
        $designed               = DesignedBy::where('id',$id)->first();
        $designed->status       = $request->status;
        $designed->save();
        if($designed){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
