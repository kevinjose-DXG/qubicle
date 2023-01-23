<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use Exception;

class SliderController extends Controller
{
     /**
     *
     */
    public function showSlider(){
        $slider         = Slider::select('id','title','slider_image','status','description')->get();
        $data           = [
            'slider'    => $slider
        ];
        return view('admin.sliders',$data);
    }
     /**
     *
     */
    public function saveSlider(Request $request){
        try{
            $rules = [
                'slider'             => 'required'.$request->slider_id,

            ];
            $messages = [
                'slider.required'    => 'Slider is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->slider_id!=""){
                $slider               = Slider::where('id',$request->slider_id)->first();
            }else{
                $slider               = new Slider();
            }
                if ($request->hasFile('slider')) {
                    $filenameWithExt                = $request->file('slider')->getClientOriginalName();
                    $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension                      = $request->file('slider')->getClientOriginalExtension();
                    $fileNameToStore                = 'slider_'.uniqid().'.'.$extension;
                    $path                           = $request->file('slider')->storeAs('public/slider/', $fileNameToStore);
                    $slider->slider_image           = $fileNameToStore;
                }
                $slider->title	        = $request->title;
                $slider->description    = $request->description;
                $slider->save();
            if($slider){
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
    public function editSlider(Request $request){
        $id             = $request->id;
        $slider         = Slider::findOrFail($id);
        $data           = [
            'slider' => $slider
        ];
        return response()->json($data);
    }
    /**
     *
     */
    public function changeSliderStatus(Request $request){
        $id                 = $request->slider_id;
        $state              = Slider::where('id',$id)->first();
        $state->status      = $request->status;
        $state->save();
        if($state){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
