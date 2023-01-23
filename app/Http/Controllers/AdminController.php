<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VendorDetail;
use App\Models\VendorFlyer;

class AdminController extends Controller
{
     /**
     *
     */
    public function showLogin(Request $request){

        return view('auth.login');
    }
     /**
     *
     */
    public function showDashboard(Request $request){
        $total_vendor           = User::where('user_type','2')->where('admin_approved','1')->count();
        $total_customer         = User::where('user_type','0')->count();
        $total_pending_flyers   = VendorFlyer::where('admin_approved','0')->count();
        $total_pending_vendors  = User::where('user_type','2')->where('admin_approved','0')->count();
        $data                   = [
            'total_vendor'          => $total_vendor,
            'total_customer'        => $total_customer,
            'total_pending_flyers'  => $total_pending_flyers,
            'total_pending_vendors' => $total_pending_vendors
        ];
        return view('dashboard',$data);
    }
    /***
     *
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('admin/');
    }
     /**
     *
     */
    public function transactionReport(){
        return view('admin.report.transactionReport');
    }
    /**
     *
     */
    public function filterTransactionDetails(Request $request){
        $data                           = [];
        $draw                           = $_POST['draw'];
        $skip                           = $_POST['start'];
        $rowperpage                     = $_POST['length'];
        // FILTERS
        if($_POST['filterByStartDate']!=''){
            $filterByStartDate          = date('Y-m-d',strtotime($_POST['filterByStartDate']));
        }else{
            $filterByStartDate          = '';
        }
        if($_POST['filterByEndDate']!=''){
            $filterByEndDate            = date('Y-m-d',strtotime($_POST['filterByEndDate']));
        }else{
            $filterByEndDate            = '';
        }
        $all_records                    = Transaction::get();
        $totalRecords                   = $all_records->count();
        // TOTAL NUMBER OF RECORDS WITH FILTERING
        $all_records_withFilter         = Transaction::query();
        if($filterByStartDate!=''){
            $all_records_withFilter     = $all_records_withFilter->where('payment_date', '>=', $filterByStartDate);
        }
        if($filterByEndDate!=''){
            $all_records_withFilter     = $all_records_withFilter->where('payment_date', '<=', $filterByEndDate);
        }
        $totalRecordsWithFilter         = $all_records_withFilter->orderBy('id')->count();
        $all_records_withFilter         = $all_records_withFilter->offset($skip)
            ->take($rowperpage)
            ->get();
        foreach ($all_records_withFilter as $record) {
            
            $data[] = [
                "date"                  => date('m/d/Y',strtotime($record->payment_date)),
                "order_id"              => $record->order_id,
                "vendor"                => $record->userDetail->name,
                "plan"                  => $record->planDetail->plans->name,
                "payment_id"            => $record->payment_id,
                "amount"                => $record->mrp,
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
    public function showPendingVendor(){
        $data           = [
            'subcategory'  => '',
            'products'  => '',
        ];
        return view('admin.vendor.approval',$data);
    }
    /**
     *
     */
    public function filterPendingVendor(Request $request){
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
        $all_records_withFilter     = $all_records_withFilter->where('admin_approved', '=', '0');
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
        $totalRecordsWithFilter     = $all_records_withFilter->where('user_type', '=', '2')->where('admin_approved', '=', '0')->count();
        $all_records_withFilter     = $all_records_withFilter->where('user_type', '=', '2')->orderBy('id', 'desc')
        ->offset($skip)
            ->take($rowperpage)
            ->get();
        foreach ($all_records_withFilter as $record) {
            $mobile                 = $record->mobile;
           
                $actions            = '<a href="'.route('editVendor', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-warning">Edit</a>';
            
            if($record->status=="active"){
                $status       = '<a href="#" class="statusBtn btn btn-success" data-id="'.$record->id.'" data-prostatus="'.$record->status.'" >Active</a>';
            } else{
                $status       = '<a href="#" class="statusBtn btn btn-danger" data-id="'.$record->id.'" data-prostatus="'.$record->status.'">In-Active</a>';
            }
            $data[] = [
                "vendor_name"           => $record->name,
                "email"                 => $record->email,
                "mobile"                => $mobile,
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
    public function showPendingVendorPayment(){
        $vendor_details = VendorDetail::where('admin_approved_vendor_payment','=','0')
                                        ->where('plan_id','!=','0')
                                        ->orderBy('id','desc')
                                        ->get();
        $data           = [
            'vendor_details'  => $vendor_details,
        ];
        return view('admin.vendor.paymentApproval',$data);
    }
    /**
     *
     */
    public function showCustomer(){
        $data           = [
            'subcategory'  => '',
            'products'  => '',
        ];
        return view('admin.vendor.customer',$data);
    }
    /**
     *
     */
    public function filterCustomer(Request $request){
        $data                       = [];
        $draw                       = $_POST['draw'];
        $skip                       = $_POST['start'];
        $rowperpage                 = $_POST['length'];
        // FILTERS
        $filterByVendor             = $_POST['filterByVendor'];
        $filterByEmail              = $_POST['filterByEmail'];
        $filterByMobile             = $_POST['filterByMobile'];
        $filterByStatus             = $_POST['filterByStatus'];
        $all_records                = User::where('user_type', '=', '0')->get();
        $totalRecords               = $all_records->count();
        // TOTAL NUMBER OF RECORDS WITH FILTERING
        $all_records_withFilter     = User::query();
        $all_records_withFilter     = $all_records_withFilter->where('user_type', '=', '0');
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
        $totalRecordsWithFilter     = $all_records_withFilter->where('user_type', '=', '0')->count();
        $all_records_withFilter     = $all_records_withFilter->where('user_type', '=', '0')
        ->offset($skip)
            ->take($rowperpage)
            ->get();
        foreach ($all_records_withFilter as $record) {
            $mobile                 = $record->mobile;
            if($record->status=="active"){
                $status       = '<a href="#" class="statusBtn btn btn-success" data-id="'.$record->id.'" data-prostatus="'.$record->status.'" >Active</a>';
            } else{
                $status       = '<a href="#" class="statusBtn btn btn-danger" data-id="'.$record->id.'" data-prostatus="'.$record->status.'">In-Active</a>';
            }
            $data[] = [
                "name"                  => $record->name,
                "email"                 => $record->email,
                "mobile"                => $mobile,
                "otp"                   => $record->otp,
                "status"                => $status,
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
    public function changeCustomerStatus(Request $request){
        $id                     = $request->vendor_id;
        $customer               = User::where('id',$id)->first();
        if($request->status=="active"){
            $customer->status       = 'inactive';
        }else{
            $customer->status       = 'active';
        }
        $customer->save();
        if($customer){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}