<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\VendorDetail;
use App\Models\VendorFlyer;
use App\Models\VendorPlan;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\District;
use App\Models\Location;
use App\Models\State;
use App\Models\VendorFlyerDetail;
use App\Mail\AdminRejectVendor;
use Exception;

class VendorController extends Controller
{
    /**
     *
     */
    public function showVendor(){
        $data           = [
            'subcategory'  => '',
            'products'  => '',
        ];
        return view('admin.vendor.vendorApproval',$data);
    }
    /**
     *
     */
    public function filterVendor(Request $request){
        $data                       = [];
        $draw                       = $_POST['draw'];
        $skip                       = $_POST['start'];
        $rowperpage                 = $_POST['length'];
        // FILTERS
        $filterByVendor             = $_POST['filterByVendor'];
        $filterByEmail              = $_POST['filterByEmail'];
        $filterByMobile             = $_POST['filterByMobile'];
        $filterByStatus             = $_POST['filterByStatus'];
        $all_records                = User::where('user_type', '=', '2')->get();
        $totalRecords               = $all_records->count();
        // TOTAL NUMBER OF RECORDS WITH FILTERING
        $all_records_withFilter     = User::query();
        if($filterByVendor!=''){
            $all_records_withFilter = $all_records_withFilter->where('name', 'LIKE', "%$filterByVendor%");
        }
        if($filterByEmail!=''){
            $all_records_withFilter = $all_records_withFilter->where('email', 'LIKE', "%$filterByEmail%");
        }
        if($filterByMobile!=''){
            $all_records_withFilter = $all_records_withFilter->where('mobile', '=', $filterByMobile);
        }
        if($filterByStatus != ''){
            $all_records_withFilter = $all_records_withFilter->where('status', '=', $filterByStatus);
        }
        $totalRecordsWithFilter     = $all_records_withFilter->count();
        $all_records_withFilter     = $all_records_withFilter->where('user_type', '=', '2')->where('admin_approved', '=', '1')
            ->offset($skip)
            ->take($rowperpage)
            ->get();
        foreach ($all_records_withFilter as $record) {
            $mobile                 = $record->mobile;
            $otp                    = $record->otp;
            if($record->details=='0'){
                $actions            = '<a href="'.route('editVendor', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-warning">Edit</a>';
            } else {
                $actions            = '<a href="'.route('editVendor', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-warning">Edit</a> <a href="'.route('viewVendor', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-primary">View</a>';
            }
            if($record->status=="active"){
                $status             = '<a href="#" class="statusBtn btn btn-success" data-id="'.$record->id.'" data-prostatus="'.$record->status.'" >Active</a>';
            } else{
                $status             = '<a href="#" class="statusBtn btn btn-danger" data-id="'.$record->id.'" data-prostatus="'.$record->status.'">In-Active</a>';
            }
            $data[] = [
                "vendor_name"           => $record->name,
                "email"                 => $record->email,
                "mobile"                => $mobile,
                "otp"                   => $otp,
                "status"                => $status,
                "actions"               => $actions,
            ];
        }
        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data,
        ];
        return response()->json($response);
    }
    /**
     *
     */
    public function viewVendor($vendor_id){
        $vendor_id          = Crypt::decrypt($vendor_id);
        $vendor             = User::with('vendorDetail')->where('id',$vendor_id)->first();
        $category           = Category::get();
        $business_category  = BusinessCategory::get();
        $data               = [
            'vendor'            => $vendor,
            'vendor_id'         => $vendor_id,
            'category'          => $category,
            'business_category' => $business_category
        ];
        return view('admin.vendor.viewVendorDetails',$data);
    }
     /**
     *
     */
    public function saveVendor(Request $request){
        try{
            $rules = [
                'vendor_name'             => 'required',
            ];
            $messages = [
                'vendor_name.required'    => 'vendor name is required',
            ];
                $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'response' => $validator->errors()->first()]);
            }
            if($request->vendor_id!=""){
                $user           = User::where('id',$request->vendor_id)->first();
            }else{
                $user           = new User();
            }
                $user->name	    = $request->vendor_name;
                $user->email    = $request->email;
                $user->mobile   = $request->mobile;
                $user->save();
            if($request->vendor_id!=""){
                $vendor_detail  = VendorDetail::where('vendor_id',$request->vendor_id)->first();
            }else{
                $vendor_detail  = new VendorDetail();
            }
            $vendor_detail->reg_business_name    = $request->business_name;
            $vendor_detail->shop_category        = $request->shop_category;
            $vendor_detail->business_category    = $request->business_category;
            $vendor_detail->shop_name            = $request->shop_name;
            $vendor_detail->gst_no               = $request->gst_no;
            $vendor_detail->mobile_no            = $request->business_mobile;
            $vendor_detail->email_id             = $request->business_email;
            $vendor_detail->address1             = $request->address1;
            $vendor_detail->address2             = $request->address2;
            $vendor_detail->district             = $request->district;
            $vendor_detail->state                = $request->state;
            $vendor_detail->location             = $request->location;
            $vendor_detail->pin                  = $request->pin;
            $vendor_detail->save();
            if($user){
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
    public function editVendor($vendor_id){
        $vendor_id              = Crypt::decrypt($vendor_id);
        $vendor                 = User::with('vendorDetail')->where('id',$vendor_id)->first();
        $state                  = State::get();
        $category               = Category::get();
        $business_category      = BusinessCategory::get();
        $data                   = [
            'vendor'            => $vendor,
            'vendor_id'         => $vendor_id,
            'category'          => $category,
            'business_category' => $business_category,
            'state'             => $state
        ];
        return view('admin.vendor.editVendor',$data);
    }
    /**
     *
     */
    public function vendorAdminApproval(Request $request){
        $vendor_details_id              = $request->vendor_details_id;
        $status                         = $request->status;
        $vendor                         = VendorDetail::where('id',$vendor_details_id)->first();
        $vendor->admin_approved_vendor  = $status;
        $vendor->save();
        $user                           = User::where('id',$vendor->vendor_id)->first();
        $user->admin_approved           = $status;
        if($status=='1'){
            $user->reason               = '';
        }
        $user->save();
        if($vendor){
            return response()->json(['status'=>true,'message'=>'Success']);
        }else{
            return response()->json(['status' => false, 'message' => 'Error']);
        }
    }
    /**
     *
     */
    public function adminApprovalVendorPayment(Request $request){
        $vendor_details_id                      = $request->vendor_details_id;
        $status                                 = $request->status;
        $plan                                   = $request->plan;
        $vendor                                 = VendorDetail::where('id',$vendor_details_id)->first();
        $vendor->admin_approved_vendor_payment  = $status;
        if($status=='0'){
            $vendor->plan_payment_status        = 'notpaid';
        }else{
            $vendor->plan_payment_status        = 'paid';
        }
        $vendor->save();
        $vendor_plan                            = VendorPlan::where('id',$plan)->first();
        if($status=='1'){
            $vendor_plan->payment_approval      = 'active';
        }else{
            $vendor_plan->payment_approval      = 'inactive';
        }
        $vendor_plan->plan_status               = 'active';
        $vendor_plan->save();
        if($vendor&&$vendor_plan){
            return response()->json(['status'=>true,'message'=>'Success']);
        }else{
            return response()->json(['status' => false, 'message' => 'Error']);
        }
    }
    /**
     *
     */
    public function changeVendorStatus(Request $request){
        $id                 = $request->vendor_id;
        $vendor             = User::where('id',$id)->first();
        if($request->status=='active'){
            $vendor->status     = 'inactive';
        }else{
            $vendor->status     = 'active';
        }
        $vendor->save();
        if($vendor){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
     /**
     *
     */
    public function fetchVendorDistrict(Request $request){
        $district_id    = $request->district_id;
        $state_id       = $request->state_id;
        $location_id    = $request->location_id;
        $district       = District::select('id','title')->where('state_id',$state_id)->get();
        $location       = Location::select('id','title')->where('state_id',$state_id)->where('district_id',$district_id)->get();
        $data           = [
            'district'      =>  $district,
            'location'      =>  $location,
            'district_id'   =>  $district_id,
            'location_id'   =>  $location_id
        ];
        return response()->json($data);
    }
    /**
     * 
     */
    public function flyerStatusChange(Request $request){ 
        try{
                if($request->flyer=='0'){
                    $flyers                 = VendorFlyer::where('id',$request->flyer_id)->first();
                    $flyers_details         = VendorFlyerDetail::where('vendor_flyer_id',$request->flyer_id);
                    if($flyers->status=='active'){
                        $flyers->status = 'inactive';
                        $flyers->save();
                        $flyers_details->update(['status'=>'inactive']);
                        if($flyers){
                            return response()->json(['status'=>true, 'message'=>'Success']);
                        }else{
                            return response()->json(['status'=>true, 'message'=>'Error']);
                        }
                    }elseif($flyers->status=='inactive'){
                        $flyers->status         = 'active';
                        $flyers->save();
                        $flyers_details->update(['status'=>'active']);
                        if($flyers){
                            return response()->json(['status'=>true, 'message'=>'Success']);
                        }else{
                            return response()->json(['status'=>true, 'message'=>'Error']);
                        }
                    }
                }else{
                    $flyers                 = VendorFlyerDetail::where('id',$request->flyer_id)->first();
                }
                if($flyers){
                    if($flyers->status=='active'){
                        $flyers->status = 'inactive';
                        $flyers->save();
                        if($flyers){
                            return response()->json(['status'=>true, 'message'=>'Success']);
                        }else{
                            return response()->json(['status'=>true, 'message'=>'Error']);
                        }
                    }elseif($flyers->status=='inactive'){
                        $flyers->status         = 'active';
                        $flyers->save();
                        if($flyers){
                            return response()->json(['status'=>true, 'message'=>'Success']);
                        }else{
                            return response()->json(['status'=>true, 'message'=>'Error']);
                        }
                    }
                }else{
                    return response()->json(['status'=>true, 'message'=>'No Flyer Found']);
                }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     * 
     */
    public function deleteFlyerByAdmin(Request $request){ 
        try{
                if($request->status=='0'){
                    $flyers                 = VendorFlyer::where('id',$request->flyer_id)->delete();
                    $flyers_details         = VendorFlyerDetail::where('vendor_flyer_id',$request->flyer_id)->delete();
                }else{
                    $flyers                 = VendorFlyerDetail::where('id',$request->flyer_id)->delete();
                }
                if($flyers){
                    return response()->json(['status'=>true, 'message'=>'Success']);
                }else{
                    return response()->json(['status'=>true, 'message'=>'Error']);
                }
        }catch(Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
    /**
     *
     */
    public function saveRejectReason(Request $request){
        $id                     = $request->vendor_id;
        $vendor                 = User::where('id',$id)->first();
        $vendor->reason         = $request->reason;
        $vendor->admin_approved = '0';
        $vendor->save();
        $vendor_details                         = VendorDetail::where('vendor_id',$vendor->id)->first();
        $vendor_details->admin_approved_vendor  = '0';
        $vendor_details->save();
        if($vendor&&$vendor_details){
            $data = array('name' => $vendor->name, 'email' => $vendor->email,'reason'=>$vendor->reason);
            Mail::to($vendor->email)->send(new AdminRejectVendor($data));
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}