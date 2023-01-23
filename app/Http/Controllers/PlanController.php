<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\PlanMaster;
use App\Models\PlanMasterHighlight;
use Exception;

class PlanController extends Controller
{
     /**
     *
     */
    public function showPlan(){
        $plan       = PlanMaster::select('id','name','mrp','duration','no_of_flyers','status')->get();
        $data       = [
            'plan' => $plan
        ];
        return view('admin.plans.index',$data);
    }
     /**
     *
     */
    public function createPlan(){
      
        return view('admin.plans.create');
    }
     /**
     *
     */
    public function savePlan(Request $request){
        try{
            if($request->plan_edit_id!=""){
                $rules = [
                    'plan'                      => 'required|unique:plan_masters,name,'.$request->plan_edit_id,
                ];
                $messages = [
                    'plan.required'             => 'Plan Name is required',
                ];
            }else{
                $rules = [
                    'plan'                      => 'required|unique:plan_masters,name',
                    "highlight"                 => "required|array|min:1",
                    "highlight.*"               => "required|min:1",
                ];
                $messages = [
                    'plan.required'             => 'Plan Name is required',
                    'highlight.required'        => 'Atleast One Highlight is required',
                ];
            }
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            if($request->plan_edit_id!=""){
                $plan                           = PlanMaster::where('id',$request->plan_edit_id)->first();

            }else{
                $plan                           = new PlanMaster();
            }
            $plan->name                         = $request->plan;
            $plan->mrp                          = $request->mrp;
            $plan->duration                     = $request->duration;
            $plan->no_of_flyers                 = $request->no_of_flyers;
            $plan->no_of_images_per_flyers      = $request->no_of_images_per_flyers;
            $plan->description                  = $request->description;
            $plan->save();
            $count_highlight                    = sizeof($request->highlight);
            if($request->plan_edit_id!=""){
                $delete_plan_highlight          = PlanMasterHighlight::where('plan_id',$request->plan_edit_id)->delete();
            }
            for($i=0;$i<$count_highlight;$i++){
                $plan_highlight                 = new PlanMasterHighlight();
                $plan_highlight->plan_id        = $plan->id;
                $plan_highlight->highlights     = $request->highlight[$i];
                $plan_highlight->save();
            }
            if($plan){
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
    public function editPlan($plan_id){
        $plan_id         = Crypt::decrypt($plan_id);
        $plan            = PlanMaster::with('highlights')->where('id',$plan_id)->first();
        $data            = [
            'plan'       => $plan,
        ];
        return view('admin.plans.edit',$data);
    }
     /***
     *
     */
    public function changePlanStatus(Request $request){
        $plan_id            = $request->plan_id;
        $status             = $request->status;
        $product            = PlanMaster::where('id',$plan_id)->first();
        $product->status    = $status;
        $product->save();
        if($product){
            return response()->json(['status' => true, 'message' =>'success']);
        }else{
            return response()->json(['status' => false, 'message' => 'Error']);
        }
    }
}
