<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\ComboOffer;
use App\Models\Category;
use App\Models\ComboOfferDetail;
use Exception;

class ComboController extends Controller
{
     /**
     *
     */
    public function showCombo(){
        $combo          = ComboOffer::get();
        $category       = Category::select('id','title')->where('status','active')->get();
        $data           = [
            'combo'     => $combo,
            'category'  => $category
        ];
        return view('admin.combo',$data);
    }
     /**
     *
     */
    public function savecombo(Request $request){
        try{
            
            $rules = [
                'title'                    => 'required|unique:combo_offers,title,'.$request->combo_id,
                "category"                 => "required|array|min:1",
                "category.*"               => "required|min:1",
            ];
            $messages = [
                'title.required'           => 'Name is required',
                'category.required'        => 'Atleast One category is required',
            ];
            
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            if($request->combo_id!=""){
                $combo                              = ComboOffer::where('id',$request->combo_id)->first();
            }else{
                $combo                              = new ComboOffer();
            }
            $combo->title                           = $request->title;
            $combo->description                     = $request->description;
            $combo->price                           = $request->price;
            if ($request->hasFile('combo_image')) {
                $filenameWithExt                    = $request->file('combo_image')->getClientOriginalName();
                $filename                           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension                          = $request->file('combo_image')->getClientOriginalExtension();
                $fileNameToStore                    = 'combo_'.uniqid().'.'.$extension;
                $path                               = $request->file('combo_image')->storeAs('public/combo/', $fileNameToStore);
                $combo->image                       = $fileNameToStore;
            }
            $combo->save();
            $count_category                         = sizeof($request->category);
            if($request->combo_id!=""){
                $delete_combo_category              = ComboOfferDetail::where('combo_id',$request->combo_id)->delete();
            }
            for($i=0;$i<$count_category;$i++){
                $combo_category                     = new ComboOfferDetail();
                $combo_category->combo_id           = $combo->id;
                $combo_category->category_id        = $request->category[$i];
                $combo_category->save();
            }
            if($combo){
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
    public function editCombo($combo_id){
        $combo_id         = Crypt::decrypt($combo_id);
        $combo            = ComboOffer::with('comboDetails')->where('id',$combo_id)->first();
        $data             = [
            'combo'       => $combo,
        ];

        return response()->json($data);
    }
}
