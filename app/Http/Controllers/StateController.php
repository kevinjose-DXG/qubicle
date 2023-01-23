<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\State;
use Exception;

class StateController extends Controller
{
    /**
     *
     */
    public function showState(){
        $state      = State::select('id','title','status','description')->get();
        $data       = [
            'state' => $state
        ];
        return view('admin.state',$data);
    }
    /**
     *
     */
    public function saveState(Request $request){
        try{
            $rules = [
                'state'             => 'required|unique:states,title,'.$request->state_id,

            ];
            $messages = [
                'state.required'    => 'State is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->state_id!=""){
                $state               = State::where('id',$request->state_id)->first();
            }else{
                $state               = new State();
            }

                $state->title	    = $request->state;
                $state->description  = $request->description;
                $state->save();
            if($state){
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
    public function editState(Request $request){
        $id         = $request->id;
        $state      = State::findOrFail($id);
        $data       = [
            'state' => $state
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeStateStatus(Request $request){
        $id                 = $request->state_id;
        $state              = State::where('id',$id)->first();
        $state->status      = $request->status;
        $state->save();
        if($state){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
