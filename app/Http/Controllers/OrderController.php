<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends Controller
{
     /**
     *
     */
    public function showOrder(){
        $data           = [
            'subcategory'  => '',
            'products'  => '',
        ];
        return view('admin.orders.index',$data);
    }
     /**
     *
     */
    public function filterOrder(Request $request){
        $data                       = [];
        $draw                       = $_POST['draw'];
        $skip                       = $_POST['start'];
        $rowperpage                 = $_POST['length'];
        // FILTERS
        $filterByorder             = $_POST['filterByorder'];
        $filterByEmail              = $_POST['filterByEmail'];
        $filterByMobile             = $_POST['filterByMobile'];
        $filterByStatus             = $_POST['filterByStatus'];
        $all_records                = Order::get();
        $totalRecords               = $all_records->count();
        // TOTAL NUMBER OF RECORDS WITH FILTERING
        $all_records_withFilter     = Order::query();
        if($filterByorder!=''){
            $all_records_withFilter = $all_records_withFilter->where('name', 'LIKE', "%$filterByorder%");
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
                $actions            = '<a href="'.route('editorder', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-warning">Edit</a>';
            } else {
                $actions            = '<a href="'.route('editorder', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-warning">Edit</a> <a href="'.route('vieworder', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-primary">View</a>';
            }
            if($record->status=="active"){
                $status             = '<a href="#" class="statusBtn btn btn-success" data-id="'.$record->id.'" data-prostatus="'.$record->status.'" >Active</a>';
            } else{
                $status             = '<a href="#" class="statusBtn btn btn-danger" data-id="'.$record->id.'" data-prostatus="'.$record->status.'">In-Active</a>';
            }
            $data[] = [
                "order_name"           => $record->name,
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
    public function viewOrder($order_id){
        $order_id          = Crypt::decrypt($order_id);
        $order             = Order::with('orderDetail')->where('id',$order_id)->first();
        $data              = [
            'order'            => $order,
        ];
        return view('admin.order.vieworderDetails',$data);
    }
    /**
     *
     */
    public function changeOrderStatus(Request $request){
        $id                 = $request->order_id;
        $order             = Order::where('id',$id)->first();
        if($request->status=='active'){
            $order->status     = 'inactive';
        }else{
            $order->status     = 'active';
        }
        $order->save();
        if($order){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
