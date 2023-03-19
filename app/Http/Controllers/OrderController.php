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
            $order_status = [];
        foreach ($all_records_withFilter as $key=> $record) {
            $customer               = $record->customer->name;
            if($record->customer->profileDetail!=""){
                $address = $record->customer->profileDetail->address1.',<br>'.$record->customer->profileDetail->address2.',<br>'.$record->customer->profileDetail->city.',<br>'.$record->customer->profileDetail->state.',<br>'.$record->customer->profileDetail->pincode.',<br> Landmark :'.$record->customer->profileDetail->landmark;
            }else{
                $address = '';
            }
            
            $order_id = $record->id;
            if($record->order_status=='onprocess'){
                $order_status = "<select name='current_order_status' id='current_order_status_$order_id' class='form-control statusBtn'>
                    <option value='onprocess'   @if($record->order_status=='onprocess') selected @endif>On Process</option>
                    <option value='cancelled'   >Cancelled</option>
                    <option value='shipped'     >Shipped</option>
                    <option value='confirmed'   >Confirmed</option>
                    <option value='delivered'   >Delivered</option>
                </select>";
            }else if($record->order_status=='cancelled'){
                $order_status = "<select name='current_order_status' id='current_order_status_$order_id' class='form-control statusBtn'>
                    <option value='onprocess'   >On Process</option>
                    <option value='cancelled'   @if($record->order_status=='cancelled') selected @endif>Cancelled</option>
                    <option value='shipped'    >Shipped</option>
                    <option value='confirmed'   >Confirmed</option>
                    <option value='delivered'   >Delivered</option>
                </select>";
            }else if($record->order_status=='shipped'){
                $order_status = "<select name='current_order_status' id='current_order_status_$order_id' class='form-control statusBtn'>
                    <option value='onprocess'   >On Process</option>
                    <option value='cancelled'  >Cancelled</option>
                    <option value='shipped'     @if($record->order_status=='shipped')   selected @endif>Shipped</option>
                    <option value='confirmed'   >Confirmed</option>
                    <option value='delivered'   >Delivered</option>
                </select>";
            }else if($record->order_status=='confirmed'){
                $order_status = "<select name='current_order_status' id='current_order_status_$order_id' class='form-control statusBtn'>
                    <option value='onprocess'  >On Process</option>
                    <option value='cancelled'  >Cancelled</option>
                    <option value='shipped'     >Shipped</option>
                    <option value='confirmed'   @if($record->order_status=='confirmed') selected @endif>Confirmed</option>
                    <option value='delivered'   >Delivered</option>
                </select>";
            }else if($record->order_status=='delivered'){
                $order_status = "<select name='current_order_status' id='current_order_status_$order_id' class='form-control statusBtn'>
                    <option value='onprocess'  >On Process</option>
                    <option value='cancelled'   >Cancelled</option>
                    <option value='shipped'    >Shipped</option>
                    <option value='confirmed'   >Confirmed</option>
                    <option value='delivered'   @if($record->order_status=='delivered') selected @endif>Delivered</option>
                </select>";
            }
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
                "address"               => $address,
                "order_status"          => $order_status,
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
        $id                     = $request->order_id;
        $order_status           = $request->order_status;
        $order                  = Order::where('id',$id)->first();
        $order->order_status    = $order_status;
        $order->save();
        if($order){
            return response()->json(['status'=>true, 'message'=>'Success']);
        }else{
            return response()->json(['status'=>false, 'message'=>'Error']);
        }
    }
}
