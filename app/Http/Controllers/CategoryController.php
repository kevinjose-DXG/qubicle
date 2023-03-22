<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    /**
     *
     */
    public function showCategory(){
        $category   = Category::select('id','title','status','description','image')->get();
        $data       = [
            'category' => $category
        ];
        return view('admin.category',$data);
    }
    /**
     *
     */
    public function saveCategory(Request $request){
        try{
            $rules = [
                'category'             => 'required|unique:categories,title,'.$request->category_id,

            ];
            $messages = [
                'category.required'    => 'Category is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            if($request->category_id!=""){
                $category               = Category::where('id',$request->category_id)->first();
            }else{
                $category               = new Category();
            }
            if ($request->hasFile('category_icon')) {
                $filenameWithExt                = $request->file('category_icon')->getClientOriginalName();
                $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                      = $request->file('category_icon')->getClientOriginalExtension();
                $fileNameToStore                = 'category_'.uniqid().'.'.$extension;
                $path                           = $request->file('category_icon')->storeAs('public/category/', $fileNameToStore);
                $category->image                = $fileNameToStore;
            }
                $category->title	            = $request->category;
                $category->delivery_charge	    = $request->delivery_charge;
                $category->description          = $request->description;
                if(empty($request->phone)){
                    $phone	            = '0';
                }else{
                    $phone	            = '1';
                }
                $category->phone          = $phone;
                if(empty($request->customizable)){
                    $customizable		            = '0';
                }else{
                    $customizable		            = '1';
                }
                $category->customizable             = $customizable;
                $category->price	                = $request->price;
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
    public function editCategory(Request $request){
        $id         = $request->id;
        $category   = Category::findOrFail($id);
        $data       = [
            'category' => $category
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeCategoryStatus(Request $request){
        $id                 = $request->category_id;
        $category           = Category::where('id',$id)->first();
        $category->status   = $request->status;
        $category->save();
        if($category){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}