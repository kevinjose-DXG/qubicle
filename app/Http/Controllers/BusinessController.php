<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessCategory;
use Exception;

class BusinessController extends Controller
{
     /**
     *
     */
    public function showBusinessCategory(){
        $BusinessCategory   = BusinessCategory::select('id','title','status','description')->get();
        $data       = [
            'category' => $BusinessCategory
        ];
        return view('admin.businessCategory',$data);
    }
    /**
     *
     */
    public function saveBusinessCategory(Request $request){
        try{
            $rules = [
                'category'             => 'required|unique:categories,title,'.$request->business_category_id,

            ];
            $messages = [
                'category.required'    => 'Business Category is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->business_category_id!=""){
                $BusinessCategory               = BusinessCategory::where('id',$request->business_category_id)->first();
            }else{
                $BusinessCategory               = new BusinessCategory();
            }

                $BusinessCategory->title	    = $request->category;
                $BusinessCategory->description  = $request->description;
                $BusinessCategory->save();
            if($BusinessCategory){
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
    public function editBusinessCategory(Request $request){
        $id                 = $request->id;
        $BusinessCategory   = BusinessCategory::findOrFail($id);
        $data               = [
            'category' => $BusinessCategory
        ];
        return response()->json($data);
    }

    /**
     *
     */
    public function changeBusinessCategoryStatus(Request $request){
        $id                         = $request->business_category_id;
        $BusinessCategory           = BusinessCategory::where('id',$id)->first();
        $BusinessCategory->status   = $request->status;
        $BusinessCategory->save();
        if($BusinessCategory){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
