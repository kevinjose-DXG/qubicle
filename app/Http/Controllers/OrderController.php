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
        $filterByOrderNo            = $_POST['filterByOrderNo'];
        $filterByOrderStatus        = $_POST['filterByOrderStatus'];
        $filterByPaymentStatus      = $_POST['filterByPaymentStatus'];
        $all_records                = Order::get();
        $totalRecords               = $all_records->count();
        // TOTAL NUMBER OF RECORDS WITH FILTERING
        $all_records_withFilter     = Order::query();
        if($filterByOrderNo!=''){
            $all_records_withFilter = $all_records_withFilter->where('order_no', 'LIKE', "%$filterByOrderNo%");
        }
        if($filterByOrderStatus!=''){
            $all_records_withFilter = $all_records_withFilter->where('order_status', '=', $filterByOrderStatus);
        }
        if($filterByPaymentStatus!=''){
            $all_records_withFilter = $all_records_withFilter->where('payment_status', '=', $filterByPaymentStatus);
        }
        $totalRecordsWithFilter     = $all_records_withFilter->count();
        $all_records_withFilter     = $all_records_withFilter->orderBy('id', 'desc')
            ->offset($skip)
            ->take($rowperpage)
            ->get();
        foreach ($all_records_withFilter as $record) {
            $customer               = $record->customer->name;

            $actions                = '<a href="'.route('viewOrder', ['id'=>Crypt::encrypt($record->id)]).'" data-id="{{ $record->id }}" data-field="variant" class="btn btn-primary">View</a>';
           
            if($record->status=="active"){
                $status             = '<a href="#" class="statusBtn btn btn-success" data-id="'.$record->id.'" data-prostatus="'.$record->status.'" >Active</a>';
            } else{
                $status             = '<a href="#" class="statusBtn btn btn-danger" data-id="'.$record->id.'" data-prostatus="'.$record->status.'">In-Active</a>';
            }
            $data[] = [
                "order_no"              => $record->order_no,
                "order_date"            => $record->order_date,
                "customer"              => $customer,
                "order_status"          => $record->order_status,
                "payment_status"        => $record->payment_status ,
                "price"                 => $record->grand_total,
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
            'order'        => $order,
        ];
        return view('admin.orders.view',$data);
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
