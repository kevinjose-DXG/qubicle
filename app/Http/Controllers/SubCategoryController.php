<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubCategory;
use App\Models\Category;
use Exception;

class SubCategoryController extends Controller
{
    /**
     *
     */
    public function showSubCategory(){
        $subcategory    = SubCategory::select('id','category_id','title','status','description','image')->get();
        $category       = Category::select('id','title')->get();
        $data           = [
            'subcategory' => $subcategory,
            'category'      => $category
        ];
        return view('admin.subCategory',$data);
    }
     /**
     *
     */
    public function saveSubCategory(Request $request){
        try{
            $rules = [
                'sub_category'             => 'required|unique:sub_categories,title,'.$request->subcategory_id,
                'category_id'              => 'required'
            ];
            $messages = [
                'sub_category.required'    => 'Sub Category is required',
                'category_id'              => 'Catgeory Required'
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->subcategory_id!=""){
                $category               = SubCategory::where('id',$request->subcategory_id)->first();
            }else{
                $category               = new SubCategory();
            }
            if ($request->hasFile('subcategory_icon')) {
                $filenameWithExt                = $request->file('subcategory_icon')->getClientOriginalName();
                $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                      = $request->file('subcategory_icon')->getClientOriginalExtension();
                $fileNameToStore                = 'subcat_'.uniqid().'.'.$extension;
                $path                           = $request->file('subcategory_icon')->storeAs('public/subcategory/', $fileNameToStore);
                $category->image                = $fileNameToStore;
            }
                $category->category_id	        = $request->category_id;
                $category->title	            = $request->sub_category;
                if(empty($request->phone)){
                    $phone	            = '0';
                }else{
                    $phone	            = '1';
                }
                $category->phone                = $phone;
                if(empty($request->customizable)){
                    $customizable		        = '0';
                }else{
                    $customizable		        = '1';
                }
                $category->customizable         = $customizable;
                $category->price	            = $request->price;
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
    public function editSubCategory(Request $request){
        $id             = $request->id;
        $subcategory    = SubCategory::findOrFail($id);
        $data           = [
            'subcategory' => $subcategory
        ];
        return response()->json($data);
    }
        /**
     *
     */
    public function changeSubCategoryStatus(Request $request){
        $id                     = $request->subcategory_id;
        $subcategory            = SubCategory::where('id',$id)->first();
        $subcategory->status    = $request->status;
        $subcategory->save();
        if($subcategory){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}