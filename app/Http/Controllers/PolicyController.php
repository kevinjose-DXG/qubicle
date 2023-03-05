<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Policy;
use App\Models\SampleProduct;
use Exception;

class PolicyController extends Controller
{
     /**
     *
     */
    public function showPolicy(){
        $policy            = Policy::get();
        $data               = [
            'policy'       => $policy
        ];
        return view('admin.policy',$data);
    }
      /**
     *
     */
    public function savePolicy(Request $request){
        try{
            $rules = [
                'title'             => 'required',
                'description'       => 'required',
            ];
            $messages = [
                'title.required'    => 'title is required',
                'description.required'    => 'description is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->policy_id!=""){
                $support                = Policy::where('id',$request->policy_id)->first();
            }else{
                $support                = new Policy();
            }
                $support->title	        = $request->title;
                $support->content	    = $request->description;
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
    public function editPolicy(Request $request){
        $id                = $request->id;
        $policy            = Policy::findOrFail($id);
        $data              = [
            'policy'       => $policy
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changePolicyStatus(Request $request){
        $id                     = $request->policy_id;
        $policy                 = Policy::where('id',$id)->first();
        $policy->status         = $request->status;
        $policy->save();
        if($policy){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
