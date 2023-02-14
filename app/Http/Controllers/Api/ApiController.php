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
use App\Models\VendorDetail;
use App\Models\BusinessCategory;
use App\Models\VendorPlan;
use App\Models\VendorFlyer;
use App\Models\VendorFlyerDetail;
use App\Models\PlanMaster;
use App\Models\Transaction;
use App\Models\State;
use App\Models\District;
use App\Models\Location;
use App\Models\DesignedBy;
use App\Models\Slider;
use Exception;

class ApiController extends BaseController
{
    /**
     *
     */
    public function customerLogin(Request $request){
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
                        $this->sendSms($customer->mobile,$random);
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
            $this->sendSms($customer->mobile,$random);
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
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                if($request->category_id!=""){
                    $category = Category::select('id','title','description')->where('id',$request->category_id)->where('status','active')->first();
                }else{
                    $category = Category::select('id','title','description')->where('status','active')->get();
                }
                if($category){
                    return $this->sendResponse($category, 'Success');
                }else{
                    return $this->sendError('No Vendor Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getPlanDetails(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $plan = PlanMaster::select('id','mrp','name','duration','no_of_flyers','description')->with('highlights')->where('status','active')->get();
                if($plan){
                    return $this->sendResponse($plan, 'Success');
                }else{
                    return $this->sendError('No Vendor Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function acivateVendorPlan(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::with('vendorDetail')->where('user_type','2')->where('verified','1')->where('details','1')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $plan_id                                = $user->vendorDetail->plan_id;
                $plan                                   = VendorPlan::where('id',$plan_id)->first();
                //selecting duration from plan master table
                $plan_master                            = PlanMaster::select('duration')->where('id',$plan->plan_id)->first();
                $duration                               = $plan_master->duration;
                $current_date                           = date('Y-m-d');
                $current_days                           = 30;
                $real_days                              = $duration*$current_days;
                $real_end_date                          = date('Y-m-d', strtotime($current_date . " +$real_days days"));
                for($i=0;$i<$duration;$i++){
                    $days                               = $i*$current_days;
                    $start_date                         = date('Y-m-d', strtotime($current_date . " +$days days"));
                    $end_date                           = date('Y-m-d', strtotime($start_date . " +$current_days days"));
                    if($i==0){
                        //insertion to the vendor plans table
                        $plan->real_start_date                  = $current_date;
                        $plan->start_date                       = $start_date;
                        $plan->end_date                         = $end_date;
                        $plan->real_end_date                    = $real_end_date;
                        $plan->vendor_plan_status               = 'active';
                        $plan->save();
                        //status updating in vendor details table
                        $vendor_detail                          = VendorDetail::where('plan_id',$plan_id)->first();
                        $vendor_detail->vendor_plan_activated   = '1';
                        $vendor_detail->save();
                    }else{
                        $vendor_plan                        = new VendorPlan();
                        $vendor_plan->order_id              = $plan->order_id;
                        $vendor_plan->vendor_id             = $user->id;
                        $vendor_plan->plan_id               = $plan->plan_id;
                        $vendor_plan->payment_status        = 'paid';
                        $vendor_plan->start_date            = $start_date;
                        $vendor_plan->end_date              = $end_date;
                        $vendor_plan->real_end_date         = $real_end_date;
                        $vendor_plan->real_start_date       = $current_date;
                        $vendor_plan->vendor_plan_status    = 'inactive';
                        $vendor_plan->payment_approval      = 'active';
                        $vendor_plan->save();
                    }
                }
                return $this->sendResponse('', 'Success');
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getPlanDetailsOfPlan(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $plan = PlanMaster::select('id','mrp','name','duration','no_of_flyers','description')->with('highlights')->where('status','active')->where('id',$request->plan_id)->first();
                if($plan){
                    return $this->sendResponse($plan, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**\
     * 
     */
    public function addFlyers(Request $request){
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $plan_id            = $user->vendorDetail->plan_id;
                $plan               = VendorPlan::with('plans')->where('vendor_id',$user->id)->where('id',$plan_id)->first();
                $no_of_flyer        = $plan->plans->no_of_flyers;
                $no_of_vendor_flyer = VendorFlyer::where('plan_id',$plan_id)->where('status','active')->where('admin_approved','1')->count();
                $current_date       = date('Y-m-d');
                $end_date           = date('Y-m-d', strtotime($current_date . " +$request->duration days"));
                $plan_end_date      = strtotime($plan->end_date);
                $flyer_end_date     = strtotime($current_date . " +$request->duration days");
                if($flyer_end_date>$plan_end_date&&$flyer_end_date!=$plan_end_date){
                    return $this->sendError('Day Duration Exceeded','',200);
                }
                if(!$plan){
                    return $this->sendError('You dont have Any Plan','',200);
                }
                if($plan&&$plan->plan_status!='active'){
                    return $this->sendError('Admin Did not Approved Your Plan','',200);
                }
                if($plan&&$plan->vendor_plan_status!='active'){
                    return $this->sendError('You Should Activate Your Plan','',200);
                }
                if($no_of_vendor_flyer<$no_of_flyer){
                    $flyer                              = new VendorFlyer();
                    $flyer->vendor_id                   = $user->id;
                    $flyer->plan_id                     = $plan_id;
                    $flyer->designed_by                 = $request->designed_by;
                    $flyer->title                       = $request->title;
                    $flyer->description                 = $request->description;
                    $flyer->start_date                  = $current_date;
                    $flyer->end_date                    = $end_date;
                    $flyer->duration                    = $request->duration;
                    if ($request->hasFile('thumb_image')) {
                        $filenameWithExt                = $request->file('thumb_image')->getClientOriginalName();
                        $filename                       = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension                      = $request->file('thumb_image')->getClientOriginalExtension();
                        $fileNameToStore                = 'flyer_'.uniqid().'.'.$extension;
                        $path                           = $request->file('thumb_image')->storeAs('public/flyers/', $fileNameToStore);
                        $flyer->thumb_image             = $fileNameToStore;
                    }
                    $flyer->save();    
                    $count                              = sizeof($request->file('flyers'));
                    for($i=0;$i<$count;$i++){
                        $flyer_details                  = new VendorFlyerDetail();
                        if ($request->hasFile('flyers')) {
                            $filenameWithExt            = $request->file('flyers')[$i]->getClientOriginalName();
                            $filename                   = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension                  = $request->file('flyers')[$i]->getClientOriginalExtension();
                            $fileNameToStore            = 'flyer_'.uniqid().'.'.$extension;
                            $path                       = $request->file('flyers')[$i]->storeAs('public/flyers/', $fileNameToStore);
                            $flyer_details->flyers      = $fileNameToStore;
                        }
                        $flyer_details->vendor_flyer_id = $flyer->id;
                        $flyer_details->save();
                    }
                    if($flyer){
                        return $this->sendResponse($flyer, 'Success');
                    }else{
                        return $this->sendError('No flyer Found','',200);
                    }
                }else{
                    return $this->sendError('Flyer Limit Reached!!','',200);
                }
                
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
        
    }
    /**
     * 
     */
    public function planPayment(Request $request){
        try{
            $plan_id    = $request->plan_id;
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type','name','mobile','email')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }else{
                    $plan = PlanMaster::select('mrp','id','name')->where('id',$plan_id)->first();
                    if(!$plan){
                        return $this->sendError('No Plan Found','',200);
                    }
                    $order_id = Transaction::max('order_id');
                    if($order_id!=""){
                        $order_id   = $order_id+1;
                        $order_id   = str_pad($order_id, 8, '0', STR_PAD_LEFT);
                    }else{
                        $value      = 1;
                        $order_id   = str_pad($value, 8, '0', STR_PAD_LEFT);
                    }
                    
                    //entry to transaction
                    $transaction                = new Transaction();
                    $transaction->vendor_id     = $user->id;
                    $transaction->order_id      = $order_id;
                    $transaction->plan_id       = $plan->id;
                    $transaction->payment_date  = date('Y-m-d');
                    $transaction->mrp           = $plan->mrp;
                    $transaction->save();
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,
                                array("X-Api-Key:test_72270c8c99bcb0202c2d2c6c17c",
                                      "X-Auth-Token:test_7c9ac16dd3c5cfdd93ceee509ed"));
                    $payload = Array(
                        'purpose'                   => 'Payment For Plan:'.$plan->name,
                        'amount'                    => $plan->mrp,
                        'phone'                     => $user->mobile,
                        'buyer_name'                => $user->name.'-'.$order_id,
                        'redirect_url'              => "https://vendor.GIFTX.in/payment_success",
                        'send_email'                => true,
                        'send_sms'                  => true,
                        'email'                     => $user->email,
                        'allow_repeated_payments'   => false
                    );
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                    $response = curl_exec($ch);
                    curl_close($ch); 
                    $response = json_decode($response);
                    return response()->json($response);
                }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }

    }
     /**
     * 
     */
    public function getStates(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $state = State::select('id','title')->where('status','active')->get();
                if($state){
                    return $this->sendResponse($state, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getDistrictByState(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $district = District::select('id','title','state_id')->where('state_id',$request->state_id)->where('status','active')->get();
                if($district){
                    return $this->sendResponse($district, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getLocationByDistrict(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $location = Location::select('id','title','state_id','district_id')->where('district_id',$request->district_id)->where('status','active')->get();
                if($location){
                    return $this->sendResponse($location, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getFlyers(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $plan_id    = $user->vendorDetail->plan_id;
                $flyers     = VendorFlyer::where('vendor_id',$user->id)
                                            ->where('plan_id',$plan_id)
                                            ->orderBy('admin_approved','desc')
                                            ->get();
                if($flyers){
                    return $this->sendResponse($flyers, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getFlyersDetails(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $flyers = VendorFlyer::with('flyers')->where('id',$request->flyer_id)->where('vendor_id',$user->id)->first();
                if($flyers){
                    return $this->sendResponse($flyers, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function flyerStatusChange(Request $request){ 
        try{
            
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $flyers = VendorFlyer::with('flyers')->where('id',$request->flyer_id)->where('vendor_id',$user->id)->first();
                if($flyers){
                    $current_date           = date('Y-m-d');
                    if($flyers->status=='active'){
                        if($flyers->start_date!=$current_date){
                            $flyers->status = 'inactive';
                            $flyers->save();
                        }else{
                            return $this->sendError("You Can't deactivate it Today ",'',200);
                        }
                        
                    }elseif($flyers->status=='inactive'){
                        $rules = [
                            'duration'                 => 'required'
                        ];
                        $messages = [
                            'duration.required'        => 'duration is required',
                        ];
                        $validator = Validator::make(request()->all(), $rules, $messages);
                        if ($validator->fails()) {
                            return $this->sendError($validator->errors()->first(),'',200);
                        }
                        $plan                   = VendorPlan::with('plans')->where('id',$user->vendorDetail->plan_id)->first();
                        $real_end_date          = strtotime($plan->real_end_date);
                        $duration               = $request->duration;
                        $no_of_flyers           = $plan->plans->no_of_flyers;
                        $end_datee              = date('Y-m-d', strtotime($current_date . " +$duration days"));
                        $end_date               = strtotime($end_datee);
                        if($end_date<$real_end_date){
                            $flyer_count                = VendorFlyer::where('vendor_id',$user->id)->where('status','active')->count();
                            if($flyer_count<$no_of_flyers){
                                $flyers->status         = 'active';
                                $flyers->start_date     = $current_date;
                                $flyers->end_date       = $end_datee;
                                $flyers->save();
                            }else{
                                return $this->sendError('Maximum Flyers Activated','',200);
                            }
                        }else{
                            return $this->sendError('Duration Exeeded End Date','',200);
                        }
                    }
                    return $this->sendResponse($flyers, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getDesignedBy(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('user_type','2')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }
            else{
                $designed_by = DesignedBy::select('id','name','mobile','email','description','status')->where('status','active')->get();
                if($designed_by){
                    return $this->sendResponse($designed_by, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getCustomerFlyers(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Customer Found','',200);
            }
            else{
                $vendor_details     = [];
                $skip               = $request->start;
                $rowperpage         = $request->length;
                $vendor_detail      = VendorDetail::query();
                if($request->vendor_id!=''){
                    $vendor_detail= $vendor_detail->where('vendor_id',$request->vendor_id);
                }
                if($request->shop_category!=''){
                    $vendor_detail= $vendor_detail->where('shop_category',$request->shop_category);
                }
                if($request->state!=''){
                    $vendor_detail= $vendor_detail->where('state',$request->state);
                }
                if($request->district!=''){
                    $vendor_detail=$vendor_detail->where('district',$request->district);
                }
                if($request->location!=''){
                    $vendor_detail= $vendor_detail->where('location',$request->location);
                }
                $vendor_detail  = $vendor_detail->where('plan_payment_status','paid');
                $vendor_detail  = $vendor_detail->with(['flyers' => function($q) {
                    // Query the name field in status table
                    $q->where('status', '=', 'active'); // '=' is optional
                    $q->where('admin_approved', '=', '1');
                }]);
                $totalRecordsWithFilter     = $vendor_detail->orderBy('id')->count();
                $vendor_detail              = $vendor_detail->offset($skip)->take($rowperpage)->get(); 
                if($vendor_detail){
                    foreach($vendor_detail as $key =>$row){
                        if(count($row->flyers)>0){
                            $vendor_details[]                                   = $row;
                            $vendor_details[$key]['totalRecordsWithFilter']     = $totalRecordsWithFilter;
                            $vendor_details[$key]['location']                   = $row->locations->title.','.$row->districts->title.','.$row->states->title;
                            $vendor_details[$key]['designed_by']                = $row->locations->title;
                        }
                    }
                    return $this->sendResponse($vendor_details, 'Success');
                }else{
                    return $this->sendError('No Flyer Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getCustomerFlyersDetails(Request $request){ 
        try{
            $user_id    = Auth::user()->id;
            $user       = User::select('id','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Customer Found','',200);
            }
            else{
                $flyers = VendorFlyer::with('flyers','designer')->where('id',$request->flyer_id)
                                        ->where('status','active')
                                        ->where('admin_approved','1')
                                        ->first();
                if($flyers){
                    return $this->sendResponse($flyers, 'Success');
                }else{
                    return $this->sendError('No Plan Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function getCustomerDetails(Request $request){ 
        try{
            $user_id                    = Auth::user()->id;
            $user                       = User::select('id','name','email','mobile','status','user_type')->where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }else{
                $customer               = [];
                $customer['name']       = $user->name;  
                $customer['email']      = $user->email;  
                $customer['mobile']     = $user->mobile;  
                $customer['status']     = $user->status;  
                if($customer){
                    return $this->sendResponse($customer, 'Success');
                }else{
                    return $this->sendError('No Customer Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function checkPlan(Request $request){
        $today_date     = date('Y-m-d');
        $vendor_flyer   = VendorFlyer::where('end_date',$today_date)->count();
        if($vendor_flyer>0){
            for($i=0;$i<$vendor_flyer;$i++){
                $vendor_flyers          = VendorFlyer::where('end_date',$today_date)->where('admin_approved','1')->first();
                $vendor_flyers->status  = 'inactive';
                $vendor_flyers->save();
            }
        }
        //plan inactivate 
        $plan_inactive    = VendorPlan::where('end_date',$today_date)
                                        ->where('plan_status','active')
                                        ->where('payment_status','paid')
                                        ->where('payment_approval','active')
                                        ->count();
        $plan_active    = VendorPlan::where('start_date',$today_date)
                                    ->where('plan_status','inactive')
                                    ->where('payment_status','paid')
                                    ->where('payment_approval','active')
                                    ->count();                                
            if($plan_inactive>0){
                for($i=0;$i<$plan_inactive;$i++){
                    $plan_inactivate =  VendorPlan::select('id','vendor_id','start_date','plan_status','payment_approval','vendor_plan_status')
                                        ->where('end_date',$today_date)
                                        ->where('plan_status','active')
                                        ->where('payment_status','paid')
                                        ->where('payment_approval','active')
                                        ->first();
                    $plan_inactivate['plan_status']           = 'expired';
                    $plan_inactivate['vendor_plan_status']    = 'inactive';
                    $plan_inactivate['payment_status']        = 'notpaid';
                    $plan_inactivate['payment_approval']      = 'inactive';
                    $plan_inactivate->save();
                    $vendor_detail                                  = VendorDetail::where('vendor_id',$plan_inactivate->vendor_id)->first();
                    $vendor_detail->plan_id                         = '0';
                    $vendor_detail->plan_payment_status             = 'notpaid';
                    $vendor_detail->vendor_plan_activated           = '0';
                    $vendor_detail->admin_approved_vendor_payment   = '0';
                    $vendor_detail->save();     
                    $vendor_flyer_count                             = VendorFlyer::where('plan_id',$plan_inactivate->id)->count();
                    if($vendor_flyer_count>0){
                        $vendor_flyer                               = VendorFlyer::where('plan_id',$plan_inactivate->id)->first();
                        $vendor_flyer->status                       = 'inactive';
                        $vendor_flyer->save();
                    }
                    
                }
            }
            //plan active function
           
            if($plan_active>0){
                for($i=0;$i<$plan_active;$i++){
                    $plan_activate  =  VendorPlan::select('id','vendor_id','start_date','plan_status','payment_approval','vendor_plan_status')
                                                ->where('start_date',$today_date)
                                                ->where('plan_status','inactive')
                                                ->where('payment_status','paid')
                                                ->where('payment_approval','active')
                                                ->first();
                    $plan_activate['plan_status']           = 'active';
                    $plan_activate['vendor_plan_status']    = 'active';
                    $plan_activate->save();
                    $vendor_detail                          = VendorDetail::where('vendor_id',$plan_activate->vendor_id)->first();
                    $vendor_detail->plan_id                 = $plan_activate->id;
                    $vendor_detail->plan_payment_status             = 'paid';
                    $vendor_detail->vendor_plan_activated           = '1';
                    $vendor_detail->admin_approved_vendor_payment   = '1';
                    $vendor_detail->save();     
                                
                }
            }
    }
    /**
     * 
     */
    public function getSliders(Request $request){ 
        try{
            $user_id                    = Auth::user()->id;
            $user                       = User::where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Customer Found','',200);
            }else{
                $slider                 = Slider::select('id','title','slider_image','description')->where('status','active')->get();
                if($slider){
                    return $this->sendResponse($slider, 'Success');
                }else{
                    return $this->sendError('No Customer Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getStateDistrictLocation(Request $request){ 
        try{
           
                if($request->state_id!=""){
                    $state = State::select('id','title')->where('id',$request->state_id)->where('status','active')->first();
                    $data = [
                        'state'     =>  $state->title,
                        'district'  => '',
                        'location'  => ''
                    ];
                    return $this->sendResponse($data, 'Success');
                }
                elseif($request->district_id!=""){
                    $district = District::select('id','title','state_id')->where('id',$request->district_id)->where('status','active')->first();
                    $data = [
                        'state'     =>  $district->states->title,
                        'district'  =>  $district->title,
                        'location'  => ''
                    ];
                    return $this->sendResponse($data, 'Success');
                    
                }
                elseif($request->location_id!=""){
                    $location = Location::select('id','title','state_id','district_id')->where('id',$request->location_id)->where('status','active')->first();
                    $data = [
                        'state'     =>  $location->states->title,
                        'district'  =>  $location->district->title,
                        'location'  =>  $location->title
                    ];
                    return $this->sendResponse($data, 'Success');
                }
               
            
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getTransactionDetails(Request $request){ 
        try{
            $user_id                    = Auth::user()->id;
            $user                       = User::where('id',$user_id)->first();
            $transaction                = [];
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }else{
                $transaction            = Transaction::with('userDetail','planDetail')->where('vendor_id',$user->id)->orderBy('id','desc')->limit('5')->get();
                foreach($transaction as $key=> $row){
                    $vendor_name                        = $row->userDetail->name;
                    $vendor_plan                        = $row->planDetail->plans->name;
                    $transaction[$key]['vendor_name']   = $vendor_name;
                    $transaction[$key]['plan_name']     = $vendor_plan;
                }
                if($transaction){
                    return $this->sendResponse($transaction, 'Success');
                }else{
                    return $this->sendError('No Vendor Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function getPlanHistory(Request $request){ 
        try{
            $user_id                    = Auth::user()->id;
            $user                       = User::where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }else{
                $plan                   = VendorPlan::with('userDetail','plans')->where('vendor_id',$user->id)->orderBy('id','desc')->limit('5')->get();
                foreach($plan as $key=> $row){
                    $vendor_name                        = $row->userDetail->name;
                    $vendor_plan                        = $row->plans->name;
                    $plan[$key]['vendor_name']   = $vendor_name;
                    $plan[$key]['plan_name']     = $vendor_plan;
                }
                if($plan){
                    return $this->sendResponse($plan, 'Success');
                }else{
                    return $this->sendError('No Vendor Found','',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
     /**
     * 
     */
    public function deleteVendorFlyer(Request $request){ 
        try{
            $user_id                    = Auth::user()->id;
            $user                       = User::where('id',$user_id)->first();
            if(!$user){
                return $this->sendError('No Vendor Found','',200);
            }else{
                $date                           = VendorFlyer::select('start_date')->where('vendor_id',$user->id)->where('id',$request->flyer_id)->first();
                $current_date                   = date('Y-m-d');
                $flyer_start_date               = $date->start_date;
                if($current_date!=$flyer_start_date){
                    $flyer                      = VendorFlyer::where('vendor_id',$user->id)->where('id',$request->flyer_id)->delete();
                    $flyer_details              = VendorFlyerDetail::where('vendor_flyer_id',$request->flyer_id)->delete();
                    if($flyer&&$flyer_details){
                        return $this->sendResponse('', 'Success');
                    }else{
                        return $this->sendError('Error','',200);
                    }
                }else{
                    return $this->sendError("You Can't Delete the Flyer Today",'',200);
                }
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
}