<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SampleProduct;
use Exception;

class SampleProductController extends Controller
{
    /**
     *
     */
    public function showSampleProduct(){
        $sample_products        = SampleProduct::get();
        $category               = Category::select('id','title')->where('status','active')->get();
        $data                   = [
            'category'          => $category,
            'sample_products'   => $sample_products
        ];
        return view('admin.productSample',$data);
    }
     /**
     *
     */
    public function saveSampleProduct(Request $request){
        try{
            $rules = [
                'category_id'              => 'required'
            ];
            $messages = [
                'category_id'              => 'Catgeory Required'
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->sample_product_id!=""){
                $category               = SampleProduct::where('id',$request->sample_product_id)->first();
            }else{
                $category               = new SampleProduct();
            }
            if ($request->hasFile('sample_product_icon')) {
                $filenameWithExt                = $request->file('sample_product_icon')->getClientOriginalName();
                $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                      = $request->file('sample_product_icon')->getClientOriginalExtension();
                $fileNameToStore                = 'sample_'.uniqid().'.'.$extension;
                $path                           = $request->file('sample_product_icon')->storeAs('public/sample_products/', $fileNameToStore);
                $category->image                = $fileNameToStore;
            }
                $category->category_id	        = $request->category_id;
                $category->title	            = $request->title;
                $category->description          = $request->description;
                $category->save();
            if($category){
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
    public function editSampleProduct(Request $request){
        $id             = $request->id;
        $sample_products    = SampleProduct::findOrFail($id);
        $data           = [
            'sample_products' => $sample_products
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeSampleProductStatus(Request $request){
        $id                         = $request->sample_product_id;
        $sample_products            = SampleProduct::where('id',$id)->first();
        $sample_products->status    = $request->status;
        $sample_products->save();
        if($sample_products){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
