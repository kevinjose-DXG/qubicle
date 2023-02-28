<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;

class ApiController extends BaseController
{
    /**
     *
     */
    public function customerLogin(Request $request){
        $rules = [
            'mobile'                 => 'required'
        ];
        $messages = [
            'mobile.required'        => 'Mobile is required',
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $mobile         = $request->mobile;
        $customer       = User::where('status','active')->where('mobile',$mobile)->first();
        $random         = random_int(100000, 999999);
        if($customer){
            if($customer->user_type=='3'){
                return $this->sendError('UnAuthorized User','',200);
            }
            if($customer->user_type=='0'){
                if($request->otp!=""){
                    if($request->otp==$customer->otp){
                        $customer->otp      = '0';
                        $customer->save();
                        Auth::login($customer);
                        $user               = Auth::user(); 
                        $success['token']   =  $user->createToken('MyApp')-> accessToken; 
                        return $this->sendResponse($success, 'User login successfully.');
                    }else{
                        return $this->sendError('OTP Miss Match','',200);
                    }
                }else{
                        $customer->otp        = $random;
                        $customer->save();
                        //$this->sendSms($customer->mobile,$random);
                        $data               = [
                            'otp'           => $random,
                            'verified'      => 0,
                        ];
                        return $this->sendResponse($data, 'false');
                    }
            }
        }else{
            $customer                     = new User();
            $customer->mobile             = $request->mobile;
            $customer->otp                = $random;
            $customer->user_type          = '0';
            $customer->save();
            //$this->sendSms($customer->mobile,$random);
            $data = [
                'otp'           => $random,
                'verified'      => 0,
                'customer_id'     => $customer->id,
            ];
            return $this->sendResponse($data, 'false');
        }
    }
    /**
     *
     */
    public function customerRegistration(Request $request){
        $rules = [
            'email'                 => 'required|unique:users,email'
        ];
        $messages = [
            'email.required'        => 'Email is required',
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $mobile             = $request->mobile;
        $name               = $request->name;
        $otp                = $request->otp;
        $password           = $request->password;
        $email              = $request->email;
        $customer           = User::where('mobile',$mobile)->where('status','active')->where('user_type','0')->first();
        if($customer){
            if($customer->otp==$otp){
                $customer->name                   = $name;
                $customer->email                  = $email;
                $customer->password               = Hash::make($password);
                $customer->otp                    = '0';
                $customer->verified               = '1';
                $customer->save();
                Auth::login($customer);
                $user                           = Auth::user(); 
                $success['token']               = $user->createToken('MyApp')-> accessToken; 
                return $this->sendResponse($success, 'customer Registered Successfully');
            }else{
                return $this->sendError('OTP Miss Match','',200);
            }
        }else{
            return $this->sendError('No customer Found','',200);
        }
    }
    /**
     * 
     */
    public function getCategoryList(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No customer Found','',200);
            }
            else{
                if($request->category_id!=""){
                    $category = Category::select('id','title','description','image','phone','customizable','price','status')->where('id',$request->category_id)->where('status','active')->first();
                }else{
                    $category = Category::select('id','title','description','image','phone','customizable','price','status')->where('status','active')->get();
                }
                if($category){
                    return $this->sendResponse($category, 'Success');
                }else{
                    return $this->sendError('No Category Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getSubCategoryLisByCatid(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No customer Found','',200);
            }
            else{
                if($request->category_id!=""){
                    $category = Category::with('subcategory')->where('id',$request->category_id)->where('status','active')->first();
                }
                if($category){
                    return $this->sendResponse($category, 'Success');
                }else{
                    return $this->sendError('No Category Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getBrandList(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No customer Found','',200);
            }
            else{
                if($request->brand_id!=""){
                    $brand = Brand::select('id','title','description','image','status')->where('id',$request->brand_id)->where('status','active')->first();
                }else{
                    $brand = Brand::select('id','title','description','image','status')->where('status','active')->get();
                }
                if($brand){
                    return $this->sendResponse($brand, 'Success');
                }else{
                    return $this->sendError('No Category Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getModelListByBrandId(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No customer Found','',200);
            }
            else{
                if($request->brand_id!=""){
                    $brand = Brand::with('brandmodel')->where('id',$request->brand_id)->where('status','active')->first();
                }
                if($brand){
                    return $this->sendResponse($brand, 'Success');
                }else{
                    return $this->sendError('No Brand Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function addToCart(Request $request){ 
        $rules = [
            'category_id'               => 'required',
            'price'                     => 'required',
            'quantity'                  => 'required'
        ];
        $messages = [
            'category_id'      => 'category is required',
            'price'            => 'price is required',
            'quantity'        => "Quantity Required"
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $cart                       = new Cart();
        $cart->customer_id          =   $user->id;
        $cart->category_id          =   $request->category_id;
        if($request->sub_category_id!=""){
            $cart->sub_category_id  =   $user->sub_category_id;
        }
        if($request->brand_id!=""){
            $cart->brand_id         =   $request->brand_id;
        }
        if($request->model_id!=""){
            $cart->model_id         =   $request->model_id;
        }
        if($request->customize_text!=""){
            $cart->customize_text   =   $request->customize_text;
        }
        if ($request->hasFile('customize_image')) {
            $filenameWithExt        = $request->file('customize_image')->getClientOriginalName();
            $filename               = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension              = $request->file('customize_image')->getClientOriginalExtension();
            $fileNameToStore        = 'customize_image_'.uniqid().'.'.$extension;
            $path                   = $request->file('customize_image')->storeAs('public/customizeimage/', $fileNameToStore);
            $cart->customize_image  = $fileNameToStore;
        }
        $cart->price                =   $request->price;
        $cart->quantity             =   $request->quantity;
        $cart->save();
        if($cart){
            return $this->sendResponse($cart, 'Success');
        }else{
            return $this->sendError('Cart Empty','',200);
        }
    }
    /**
     * 
     */
    public function getCartList(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $cart       = Cart::where('customer_id',$user->id)->get();
        if($cart){
            return $this->sendResponse($cart, 'Success');
        }else{
            return $this->sendError('Cart Empty','',200);
        }
    }
     /**
     *
     */
    public function createOrder(Request $request){
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No customer Found','',200);
            }
                $maxValue                       = Order::max('order_no');
                $order                          = new Order();
                if($maxValue){
                    $maxValue                   = $maxValue+1;
                    $order_no                   = str_pad($maxValue, 7, '0', STR_PAD_LEFT);
                }else{
                    $order_no                   = str_pad('1', 7, '0', STR_PAD_LEFT);
                }
                $sub_total          = 0;
                $category_id        = [];
                $sub_category_id    = [];
                $brand_id           = [];
                $modal_id           = [];
                $image_customize    = [];
                $text_customize     = [];
                $price              = [];
                $quantity           = [];
                $cart               = Cart::where('customer_id',$user->id)->get();
                foreach($cart as $row){
                    $sub_total                          += $row->price;
                    $order_details                      = new OrderDetail();
                    $order_details->order_id            = $order_no;
                    $order_details->customer_id         = $user->id;
                    $order_details->category_id         = $row->category_id;
                    $order_details->sub_category_id     = $row->sub_category_id;
                    $order_details->brand_id            = $row->brand_id;
                    $order_details->modal_id            = $row->modal_id;
                    $order_details->image_customize     = $row->customize_image;
                    $order_details->text_customize      = $row->customize_text;
                    $order_details->quantity            = $row->quantity;
                    $order_details->price               = $row->price;
                    $order_details->order_date	        = date('Y-m-d');
                    $order_details->save();
                }
                $order->address_id                = '1';
                $order->customer_id               = $user->id;
                $order->order_no                  = $order_no;
                $order->order_date	              = date('Y-m-d');
                $order->sub_total                 = $sub_total;
                $order->discount_amount           = '0.00';
                $order->tax                       = '0.00';
                $order->delivery_charge           = '0.00';
                $order->grand_total	              = $sub_total;
                $order->save();
                if($order){
                    $clearCart = Cart::where('customer_id',$user->id)->delete();
                    return response()->json(['status'=>true,'message'=>'Success']);
                }else{
                    return response()->json(['status'=>false,'message'=>'Error']);
                }
        } catch (Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
}