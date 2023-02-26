<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use Exception;

class BrandController extends Controller
{
     /**
     *
     */
    public function showBrand(){
        $brand          = Brand::select('id','title','status','image','description')->get();
        $data           = [
            'brand' => $brand,
        ];
        return view('admin.brand',$data);
    }
     /**
     *
     */
    public function saveBrand(Request $request){
        try{
            $rules = [
                'brand'             => 'required|unique:brands,title,'.$request->brand_id,
            ];
            $messages = [
                'brand'             => 'Brand is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->brand_id!=""){
                $brand               = Brand::where('id',$request->brand_id)->first();
            }else{
                $brand               = new Brand();
            }
            if ($request->hasFile('brand_icon')) {
                $filenameWithExt                = $request->file('brand_icon')->getClientOriginalName();
                $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                      = $request->file('brand_icon')->getClientOriginalExtension();
                $fileNameToStore                = 'brand_'.uniqid().'.'.$extension;
                $path                           = $request->file('brand_icon')->storeAs('public/brand/', $fileNameToStore);
                $brand->image                   = $fileNameToStore;
            }
                $brand->title	                = $request->brand;
                $brand->description             = $request->description;
                $brand->save();
            if($brand){
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
    public function editBrand(Request $request){
        $id             = $request->id;
        $brand          = Brand::findOrFail($id);
        $data           = [
            'brand' => $brand
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeBrandStatus(Request $request){
        $id                 = $request->brand_id;
        $brand              = Brand::where('id',$id)->first();
        $brand->status      = $request->status;
        $brand->save();
        if($brand){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
