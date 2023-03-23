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
use App\Models\Profile;
use App\Models\SampleProduct;
use App\Models\Support;
use App\Models\Policy;
use App\Models\Transaction;
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
            'category_id.required'      => 'category is required',
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
            $cart->sub_category_id  =   $request->sub_category_id;
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
        $user_id                                = Auth::user()->id;
        $user                                   = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $cart                                   = Cart::where('customer_id',$user->id)->get();
        $arr_delivery_charge                    = array();
        $sum_quantity                           = 0;
        $dc_per_quantity                        = 10;
        $sub_total                              = 0;
        foreach($cart as $key=> $row){
            if($row->category!=""){
                $category                       = Category::select('delivery_charge')->where('id',$row->category_id)->first();
                $arr_delivery_charge[]          = array_push($arr_delivery_charge, $category->delivery_charge);
                $sub_total                      += $row->price;
                $sum_quantity                   += $row->quantity;
                $cart[$key]['category_id']            = $row->category->title;
            }
            if($row->subcategory!=""){
                $cart[$key]['sub_category_id']        = $row->subcategory->title;
            }
            if($row->brands!=""){
                $cart[$key]['brand_id']               = $row->brands->title;
            }
            if($row->brandmodel!=""){
                $cart[$key]['model_id']               = $row->brandmodel->title;
            }
        }
        if(count($arr_delivery_charge)>0){
            $delivery_charge                        = max($arr_delivery_charge);
            $total_quantity_charge                  = $sum_quantity * $dc_per_quantity;
            $total_delivery_charge                  = $delivery_charge + $total_quantity_charge;
            $grand_total                            = $total_delivery_charge + $sub_total;
            $cart[0]['delivery_charge']            = $delivery_charge;
            $cart[0]['total_quantity_charge']      = $total_quantity_charge;
            $cart[0]['total_delivery_charge']      = $total_delivery_charge;
            $cart[0]['grand_total']                = $grand_total;
        }
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
                $maxValue                               = Order::max('order_no');
                $order                                  = new Order();
                if($maxValue){
                    $maxValue                           = $maxValue+1;
                    $order_no                           = str_pad($maxValue, 7, '0', STR_PAD_LEFT);
                }else{
                    $order_no                           = str_pad('1', 7, '0', STR_PAD_LEFT);
                }
                $sub_total                              = 0;
                $cart                                   = Cart::where('customer_id',$user->id)->get();
                $arr_delivery_charge                    = array();
                $sum_quantity                           = 0;
                $dc_per_quantity                        = 10;
                foreach($cart as $row){
                    $category                           = Category::select('delivery_charge')->where('id',$row->category_id)->first();
                    $arr_delivery_charge[]              = array_push($arr_delivery_charge, $category->delivery_charge);
                    $sub_total                          += $row->price;
                    $sum_quantity                       += $row->quantity;
                    $order_details                      = new OrderDetail();
                    $order_details->order_id            = $order_no;
                    $order_details->customer_id         = $user->id;
                    $order_details->category_id         = $row->category_id;
                    $order_details->sub_category_id     = $row->sub_category_id;
                    $order_details->brand_id            = $row->brand_id;
                    $order_details->modal_id            = $row->model_id;
                    $order_details->image_customize     = $row->customize_image;
                    $order_details->text_customize      = $row->customize_text;
                    $order_details->quantity            = $row->quantity;
                    $order_details->price               = $row->price;
                    $order_details->order_date	        = date('Y-m-d');
                    $order_details->save();
                }
                $delivery_charge                    = max($arr_delivery_charge);
                $total_quantity_charge              = $sum_quantity * $dc_per_quantity;
                $total_delivery_charge              = $delivery_charge + $total_quantity_charge;
                $grand_total                        = $total_delivery_charge + $sub_total;
                $order->address_id                  = '1';
                $order->customer_id                 = $user->id;
                $order->order_no                    = $order_no;
                $order->order_date	                = date('Y-m-d');
                $order->sub_total                   = $sub_total;
                $order->discount_amount             = '0.00';
                $order->tax                         = '0.00';
                $order->delivery_charge             = $total_delivery_charge;
                $order->grand_total	                = $grand_total;
                $order->save();
                if($order){
                    $merchTxnId             = uniqId();
                    $amount                 = $grand_total;
                    $login                  = "317159";
                    $password               = "Test@123";
                    $product_id             = "NSE";
                    $date                   = date('Y-m-d H:i:s'); // current date
                    $encRequestKey          = "A4476C2062FFA58980DC8F79EB6A799E";
                    $decResponseKey         = "75AEF0FA1B94B3C10D4F5B268F757F11";
                    $api_url                = "https://caller.atomtech.in/ots/aipay/auth";
                    $user_email             = $user->email;
                    $user_contact_number    = $user->mobile;
                    $return_url             = "http://localhost/carspa/response";
                    $payData                = array(
                        'login'             =>    $login,
                        'password'          =>    $password,
                        'amount'            =>    $amount,
                        'prod_id'           =>    $product_id,
                        'txnId'             =>    $merchTxnId,
                        'date'              =>    $date,
                        'encKey'            =>    $encRequestKey,
                        'decKey'            =>    $decResponseKey,
                        'payUrl'            =>    $api_url,
                        'email'             =>    $user_email,
                        'mobile'            =>    $user_contact_number,
                        'txnCurrency'       =>    'INR',
                        'return_url'        =>    $return_url,
                        'udf1'              =>    "",  // optional
                        'udf2'              =>    "",  // optional 
                        'udf3'              =>    "",  // optional
                        'udf4'              =>    "",  // optional
                        'udf5'              =>    ""   // optional
                        );
                    $atomTokenId                    = $this->createTokenId($payData);
                    $transaction                    = new Transaction();
                    $transaction->order_id          = $order_no;
                    $transaction->txnId             = $merchTxnId;
                    $transaction->trans_date        = date('Y-m-d');
                    $transaction->price             = $amount;
                    $transaction->payment_status    = 'notpaid';
                    $transaction->save();
                    $clearCart = Cart::where('customer_id',$user->id)->delete();
                    return response()->json(['atomTokenId'=>$atomTokenId,'message'=>'Success']);
                }else{
                    return response()->json(['status'=>false,'message'=>'Error']);
                }
        } catch (Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    // main response function to get response data
    public function response()
    {
        dd("KK");
             $data = $_POST['encData'];
        
             // change decryption key below for production
             $decData = $this->decrypt($data, '75AEF0FA1B94B3C10D4F5B268F757F11', '75AEF0FA1B94B3C10D4F5B268F757F11');
             $jsonData = json_decode($decData, true);
                   
              if($jsonData['payInstrument']['responseDetails']['statusCode'] == 'OTS0000'){
                  echo 'Payment status = Transaction Successful';
                  echo "<br>";
                  echo 'Transaction id = '.$jsonData['payInstrument']['merchDetails']['merchTxnId'];
                  echo "<br>";
                  echo 'Transaction date = '.$jsonData['payInstrument']['merchDetails']['merchTxnDate']; 
                  echo "<br>";
                  echo 'Bank transaction id = '.$jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'];
               }else{
                  echo 'Payment status = Transaction Failed';
              }
              echo "<pre>";
              print_r($jsonData);  
    }
     //do not change anything in below function
     public function createTokenId($data)
     {
            $jsondata = '{
                 "payInstrument": {
                     "headDetails": {
                         "version": "OTSv1.1",      
                         "api": "AUTH",  
                         "platform": "FLASH"	
                     },
                     "merchDetails": {
                         "merchId": "'.$data['login'].'",
                         "userId": "",
                         "password": "'.$data['password'].'",
                         "merchTxnId": "'.$data['txnId'].'",      
                         "merchTxnDate": "'.$data['date'].'"
                     },
                     "payDetails": {
                         "amount": "'.$data['amount'].'",
                         "product": "'.$data['prod_id'].'",
                         "custAccNo": "213232323",
                         "txnCurrency": "'.$data['txnCurrency'].'"
                     },	
                     "custDetails": {
                         "custEmail": "'.$data['email'].'",
                         "custMobile": "'.$data['mobile'].'"
                     },
                     "extras": {
                         "udf1": "'.$data['udf1'].'",  
                         "udf2": "'.$data['udf2'].'",  
                         "udf3": "'.$data['udf3'].'", 
                         "udf4": "'.$data['udf4'].'",  
                         "udf5": "'.$data['udf5'].'" 
                     }
                 }  
             }';
         
              $encData = $this->encrypt($jsondata, $data['encKey'], $data['encKey']);
              
              $curl = curl_init();
              curl_setopt_array($curl, array(
                   CURLOPT_URL => $data['payUrl'],
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 0,
                   CURLOPT_FOLLOWLOCATION => true,
                   CURLOPT_SSL_VERIFYHOST => 2,
                   CURLOPT_SSL_VERIFYPEER => 1,
                   CURLOPT_CAINFO => dirname(__FILE__).'/cacert.pem', //added in Controllers folder
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "POST",
                   CURLOPT_POSTFIELDS => "encData=".$encData."&merchId=".$data['login'],
                   CURLOPT_HTTPHEADER => array(
                     "Content-Type: application/x-www-form-urlencoded"
                   ),
             ));
             $atomTokenId = null;
             $response = curl_exec($curl);
             $resp = json_decode($response, true);
             dd($resp);
             if($resp['txnMessage'] == 'FAILED'){
                 echo $resp['txnDescription'];
              }else{
                     $getresp = explode("&", $response); 
                     $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);
                     $decData = $this->decrypt($encresp, $data['decKey'], $data['decKey']);
                     if(curl_errno($curl)) {
                         $error_msg = curl_error($curl);
                         echo "error = ".$error_msg;
                     }      
                     if(isset($error_msg)) {
                         echo "error = ".$error_msg;
                     }   
                     curl_close($curl);
                     $res = json_decode($decData, true);
                     if($res){
                       if($res['responseDetails']['txnStatusCode'] == 'OTS0000'){
                         $atomTokenId = $res['atomTokenId'];
                       }else{
                         echo "Error getting data";
                          $atomTokenId = null;
                       }
                     }
             }
             return $atomTokenId;
     }
     
     //do not change anything in below function 
     public function encrypt($data, $salt, $key)
     { 
             $method = "AES-256-CBC";
             $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
             $chars = array_map("chr", $iv);
             $IVbytes = join($chars);
             $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
             $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
             $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 
             $encrypted = openssl_encrypt($data, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
             return strtoupper(bin2hex($encrypted));
     }  
     
     //do not change anything in below function
     public function decrypt($data, $salt, $key)
     {
             $dataEncypted = hex2bin($data);
             $method = "AES-256-CBC";
             $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
             $chars = array_map("chr", $iv);
             $IVbytes = join($chars);
             $salt1 = mb_convert_encoding($salt, "UTF-8");//Encoding to UTF-8
             $key1 = mb_convert_encoding($key, "UTF-8");//Encoding to UTF-8
             $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 
             $decrypted = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
             return $decrypted;
     }
     /**
     * 
     */
    public function orderList(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $orders       = Order::where('customer_id',$user->id)->orderBy('id','desc')->get();
        if($orders){
            return $this->sendResponse($orders, 'Success');
        }else{
            return $this->sendError('No Orders Found','',200);
        }
    }
    /**
     * 
     */
    public function orderDetails(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $orders     = Order::with('orderdetail')
                            ->where('customer_id',$user->id)
                            ->where('order_no',$request->order_no)
                            ->first();
        foreach($orders->orderdetail as $key =>$row){
            $orders['orderdetail'][$key]['customer_id'] = $row->customer->name;
            $orders['orderdetail'][$key]['category_id'] = $row->category->title;
            if($row->subcategory!=""){
                $orders['orderdetail'][$key]['sub_category_id'] = $row->subcategory->title;
            }
            if($row->brands!=""){
                $orders['orderdetail'][$key]['brand_id'] = $row->brands->title;
            }
            if($row->brandmodel!=""){
                $orders['orderdetail'][$key]['modal_id'] = $row->brandmodel->title;
            }
        }
        if($orders){
            return $this->sendResponse($orders, 'Success');
        }else{
            return $this->sendError('No Orders Found','',200);
        }
    }
     /**
     * 
     */
    public function updateCart(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $rules = [
            'cart_id'                   => 'required',
            'quantity'                  => 'required'
        ];
        $messages = [
            'cart_id.required'          => 'Cart Id is required',
            'quantity.required'         => 'Quantity is required',
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $cart           = Cart::where('id',$request->cart_id)->where('customer_id',$user->id)->first();
        $cart->quantity = $request->quantity;
        $cart->save();
        if($cart){
            return $this->sendResponse($cart, 'Success');
        }else{
            return $this->sendError('Cart Not Updated','',200);
        }
    }
    /**
     * 
     */
    public function deleteCartItem(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $rules = [
            'cart_id'                   => 'required',
        ];
        $messages = [
            'cart_id.required'          => 'Cart Id is required',
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $cart  = Cart::where('id',$request->cart_id)->where('customer_id',$user->id)->delete();
        if($cart){
            return $this->sendResponse($cart, 'Success');
        }else{
            return $this->sendError('Cart Item Not Deleted','',200);
        }
    }
     /**
     * 
     */
    public function orderChangeStatus(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $rules = [
            'order_no'                   => 'required',
            'order_status'              => 'required'
        ];
        $messages = [
            'order_no.required'          => 'OrderNo is required',
            'order_status.required'     =>'Order Status Required'         
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $orders                 = Order::where('order_no',$request->order_no)->where('customer_id',$user->id)->first();
        $orders->order_status   = $request->order_status;
        $orders->save();
        if($orders){
            return $this->sendResponse($orders, 'Success');
        }else{
            return $this->sendError('No Orders Found','',200);
        }
    }
     /**
     * 
     */
    public function orderListByStatus(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $rules = [
            'order_status'              => 'required'
        ];
        $messages = [
            'order_status.required'     =>'Order Status Required'         
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $orders       = Order::where('order_status',$request->order_status)->where('customer_id',$user->id)->orderBy('order_no','desc')->get();
        if($orders){
            return $this->sendResponse($orders, 'Success');
        }else{
            return $this->sendError('No Orders Found','',200);
        }
    }
     /**
     * 
     */
    public function saveAddress(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $rules = [
            'email'                 => 'required|unique:users,email,'.$user->id,
            'mobile'                => 'required|unique:users,mobile,'.$user->id,
            'name'                  => 'required',
            'address1'              => 'required',
            'address2'              => 'required',
            'landmark'              => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            'pincode'               => 'required',
        ];
        $messages = [
            'email.required'        => 'email is Required',
            'mobile.required'       => 'mobile is Required',
            'name.required'         => 'name is Required',
            'address1.required'     => 'Address1 is Required',
            'address2.required'     => 'Address2 is Required',            
            'landmark.required'     => 'Landmark is Required',  
            'city.required'         => 'City is Required',
            'state.required'        => 'state is Required' ,
            'pincode.required'      => 'pincode is Required' ,

        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),'',200);
        }
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->mobile       = $request->mobile;
        $user->save();
        $profile_count  = Profile::where('customer_id',$user->id)->count();
        if($profile_count>0){
            $profile = Profile::where('customer_id',$user->id)->first();
        }else{
            $profile                = new Profile();
            $profile->customer_id   = $user->id;
        }
            $profile->customer_id   = $user->id;
            $profile->address1      = $request->address1;
            $profile->address2      = $request->address2;
            $profile->landmark      = $request->landmark;
            $profile->city          = $request->city;
            $profile->state         = $request->state;
            $profile->pincode       = $request->pincode;
            $profile->default       = '1';
            $profile->save();
            if($profile){
                return $this->sendResponse($profile, 'Success');
            }else{
                return $this->sendError('No Profile Found','',200);
            }
    }
     /**
     * 
     */
    public function getAddress(){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $profile    = User::with('profileDetail')->where('id',$user->id)->first();
        if($profile){
            return $this->sendResponse($profile, 'Success');
        }else{
            return $this->sendError('No profile Found','',200);
        }
    }
     /**
     * 
     */
    public function getSampleProduct(Request $request){ 
        $user_id    = Auth::user()->id;
        $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
        if(!$user){
            return $this->sendError('No customer Found','',200);
        }
        $category = $request->category_id;
        $sample_products    = SampleProduct::with('category')->where('category_id',$category)->get();
        if($sample_products){
            return $this->sendResponse($sample_products, 'Success');
        }else{
            return $this->sendError('No sample products Found','',200);
        }
    }
     /**
     * 
     */
    public function getSupportDetails(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Customer Found','',200);
            }else{
                $support            = Support::where('status','active')->first();
                if($support){
                    return $this->sendResponse($support, 'Success');
                }else{
                    return $this->sendError('No Support Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getPolicyDetails(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Customer Found','',200);
            }else{
                $policy  = Policy::where('status','active')->get();
                if($policy){
                    return $this->sendResponse($policy, 'Success');
                }else{
                    return $this->sendError('No policy Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
}