<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\BusinessCategory;
use App\Models\VendorFlyer;
use App\Models\VendorFlyerDetail;
use Exception;

class VendorFlyerController extends Controller
{
    public function showVendorFlyer(){
        $flyer  = VendorFlyer::where('admin_approved','0')->get();
        $data   = ['flyer' => $flyer];
        return view('admin.vendor.vendorFlyer',$data);
    }
    /**
     *
     */
    public function viewVendorFlyer($vendor_id){
        $vendor_id          = Crypt::decrypt($vendor_id);
        $vendor             = VendorFlyer::where('id',$vendor_id)->first();
        $data               = [
            'vendor'            => $vendor,
            'vendor_id'         => $vendor_id,
        ];
        
        return view('admin.vendor.vendorFlyerDetails',$data);
    }
    /**
     *
     */
    public function flyerApprovalByAdmin(Request $request){
        $flyer_id               = $request->flyer_id;
        $vendor                 = VendorFlyer::with('planDetail')->where('id',$flyer_id)->first();
        $no_of_flyer            = $vendor->planDetail->plans->no_of_flyers;
        $no_of_vendor_flyer     = VendorFlyer::where('plan_id',$vendor->plan_id)->where('status','active')->where('admin_approved','1')->count();
        if($no_of_vendor_flyer<$no_of_flyer){
            $vendor                 = VendorFlyer::where('id',$flyer_id)->first();
            $vendor->admin_approved = $request->status;
            $vendor->save();
            if($vendor){
                return response()->json(['status'=>true,'message'=>'Success']);
            }else{
                return response()->json(['status'=>false,'message'=>'Error']);
            }
        }else{
            return response()->json(['status'=>false,'message'=>'Flyer Limit Reached!!']);
        }
    }
    /**
     *
     */
    public function flyerDeleteByAdmin(Request $request){
        $flyer_id                   = $request->flyer_id;
        $vendor                     = VendorFlyer::with('planDetail')->where('id',$flyer_id)->first();
        if($vendor){
            $vendor_delete          = VendorFlyer::with('planDetail')->where('id',$flyer_id)->delete();
            $vendor_details_delete  = VendorFlyerDetail::where('vendor_flyer_id',$request->flyer_id)->delete();
            if($vendor_delete&&$vendor_details_delete){
                return response()->json(['status'=>true,'message'=>'Success']);
            }else{
                return response()->json(['status'=>false,'message'=>'Error']);
            }
        }else{
            return response()->json(['status'=>false,'message'=>'No Flyer Found']);
        }
    }
}
