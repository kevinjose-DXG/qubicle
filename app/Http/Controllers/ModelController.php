<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BrandModel;
use Exception;

class ModelController extends Controller
{
     /**
     *
     */
    public function showModel(){
        $brand          = Brand::select('id','title')->where('status','active')->get();
        $model          = BrandModel::with('brands')->select('id','title','status','image','description','brand_id')->get();
        $data           = [
            'brand' => $brand,
            'model' => $model
        ];
        return view('admin.model',$data);
    }
     /**
     *
     */
    public function saveModel(Request $request){
        try{
            $rules = [
                'model'             => 'required|unique:brand_models,title,'.$request->model_id,
                'brand_id'          => 'required'
            ];
            $messages = [
                'model'             => 'Model is required',
                'brand_id'          => 'Brand Is Required'
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->model_id!=""){
                $model               = BrandModel::where('id',$request->model_id)->first();
            }else{
                $model               = new BrandModel();
            }
            if ($request->hasFile('model_icon')) {
                $filenameWithExt                = $request->file('model_icon')->getClientOriginalName();
                $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                      = $request->file('model_icon')->getClientOriginalExtension();
                $fileNameToStore                = 'model_'.uniqid().'.'.$extension;
                $path                           = $request->file('model_icon')->storeAs('public/model/', $fileNameToStore);
                $model->image                   = $fileNameToStore;
            }
                $model->title	                = $request->model;
                $model->brand_id	            = $request->brand_id;
                $model->description             = $request->description;
                $model->save();
            if($model){
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
    public function editModel(Request $request){
        $id             = $request->id;
        $model          = BrandModel::findOrFail($id);
        $data           = [
            'model' => $model
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeModelStatus(Request $request){
        $id                 = $request->model_id;
        $model              = BrandModel::where('id',$id)->first();
        $model->status      = $request->status;
        $model->save();
        if($model){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
