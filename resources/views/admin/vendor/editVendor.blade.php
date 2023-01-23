@extends('layouts.innerApp')
@section('content')
<style>
   fieldset {
  display: block;
  margin-left: 2px;
  margin-right: 2px;
  padding-top: 0.35em;
  padding-bottom: 0.625em;
  padding-left: 0.75em;
  padding-right: 0.75em;
  border: 2px groove (internal value);
}
fieldset {
  background-color: #eeeeee;
}

legend {
  background-color: gray;
  color: white;
  padding: 5px 10px;
}

input {
  margin: 5px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Vendor</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">Edit Vendor</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card card-secondary">
                  <div class="card-header">
                     <h3 class="card-title">Vendor Details</h3>
                  </div>
                     <!-- /.card-header -->
                  <form action="javascript:;" name="VendorForm" id="VendorForm">
                        @csrf
                        <div class="card-body">
                           <div class="row">
                              <div class="col-sm-6">
                                 <!-- text input -->
                                 <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->name}}@endif">
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <!-- text input -->
                                 <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->email}}@endif">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-sm-6">
                                 <!-- text input -->
                                 <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->mobile}}@endif">
                                 </div>
                              </div>
                           </div>
                           @if($vendor->vendorDetail)
                              <div class="card-header">
                                 <h3 class="card-title">Business Details</h3>
                              </div>
                              <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Business Name</label>
                                        <input type="text" name="business_name" id="business_name" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->reg_business_name}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Business Category</label>
                                        <select class="form-control" name="business_category" id="business_category">
                                            @foreach($business_category as $row)
                                            <option value="{{$row->id}}"@if($row->id==$vendor->vendorDetail->business_category)selected @endif>{{$row->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Shop Category</label>
                                        <select class="form-control" name="shop_category" id="shop_category">
                                        @foreach($category as $row)
                                            <option value="{{$row->id}}"@if($row->id==$vendor->vendorDetail->shop_category)selected @endif>{{$row->title}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Shop Name</label>
                                        <input type="text" name="shop_name" id="shop_name" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->shop_name}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Gst No</label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->gst_no}} @endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" name="business_mobile" id="business_mobile" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->mobile_no}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="business_email" id="business_email" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->email_id}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Address 1</label>
                                        <input type="text" name="address1" id="address1" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->address1}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Address 2</label>
                                        <input type="text" name="address2" id="address2" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->address2}}@endif">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>State</label>
                                          <select class="form-control" name="state" id="state">
                                             @foreach($state as $row)
                                                <option value="{{$row->id}}" @if($row->id==$vendor->vendorDetail->state_id) selected @endif>{{$row->title}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>District</label>
                                          <select class="form-control" name="district" id="district">
                                            
                                          </select>
                                       </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <select class="form-control" name="location" id="location">
                                            
                                        </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Pin</label>
                                        <input type="text" name="pin" id="pin" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->pin}}@endif">
                                    </div>
                                 </div>
                              </div>
                           @endif
                           @if($vendor->vendorDetail&&$vendor->vendorDetail->plan_id!='0') 
                              <div class="card-header">
                                 <b><h3 class="card-title">Plan Details</h3></b>
                              </div>
                              <div class="row">
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Plan</label>
                                       <input disabled type="text" name="plan_name" id="plan_name" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->plans->name}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Start Date</label>
                                       <input disabled type="text" name="start_date" id="start_date" class="form-control" placeholder="" value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->start_date}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>End Date</label>
                                       <input disabled type="text" name="end_date" id="end_date" class="form-control" placeholder="" value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->end_date}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Plan Status</label>
                                       <input disabled type="text" name="plan_status" id="plan_status" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->plan_status}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Payment Status</label>
                                       <input disabled type="text" name="payment_status" id="payment_status" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->payment_status}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Payment Id</label>
                                       <input disabled type="text" name="payment_id" id="payment_id" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->order_id}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Vendor Plan Status</label>
                                       <input disabled type="text" name="vendor_plan_status" id="vendor_plan_status" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->vendor_plan_status}}@endif">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Payment Approved</label>
                                       <input disabled type="text" name="payment_approval" id="payment_approval" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail->planDetail!=''){{$vendor->vendorDetail->planDetail->payment_approval}}@endif">
                                    </div>
                                 </div>
                              </div>
                           @endif 
                        
                           @if($vendor->vendorDetail&&$vendor->vendorDetail->planDetail&&count($vendor->vendorDetail->planDetail->flyer)>0)
                           <div class="card-header">
                              <h3 class="card-title">Flyer Details</h3>
                           </div>
                           @foreach($vendor->vendorDetail->planDetail->flyer as $key=>$flyer_row)
                              @if($vendor->vendorDetail->planDetail&&$flyer_row&&$flyer_row->admin_approved!=0) 
                                 <div class="row">
                                    <div class="col-sm-2">
                                       <div class="form-group">
                                          <br>
                                          <img src="{{asset('/')}}public/storage/flyers/{{$flyer_row->thumb_image}}" width="200px">
                                       </div>
                                       @if($flyer_row->status=='active')
                                          <button type="button" data-id="{{$flyer_row->id}}" data-flyer="0" data-status="{{$flyer_row->status}}" class="btn btn-danger flyerStatusChange" >Inactivate All Images</button>
                                       @else
                                          <button type="button" data-id="{{$flyer_row->id}}" data-flyer="0" data-status="{{$flyer_row->status}}" class="btn btn-primary flyerStatusChange" >Activate All Images</button>
                                       @endif
                                          <button type="button" data-id="{{$flyer_row->id}}" data-status="0" class="btn btn-danger deleteFlyer" >Delete All Images</button> 
                                    </div>
                                    @foreach($flyer_row->flyers as $row)
                                    <div class="col-sm-2">
                                       <div class="form-group">
                                          <br>
                                          <img src="{{asset('/')}}public/storage/flyers/{{$row->flyers}}" width="200px">
                                       </div>
                                        @if($row->status=='active')
                                        <button type="button" data-id="{{$row->id}}" data-flyer="1" data-status="{{$row->status}}" class="btn btn-primary flyerStatusChange" >Active</button>
                                        @else
                                        <button type="button" data-id="{{$row->id}}" data-flyer="1" data-status="{{$row->status}}" class="btn btn-danger flyerStatusChange" >Inactive</button>
                                        @endif
                                        <button type="button" data-id="{{$row->id}}" data-status="1" class="btn btn-danger deleteFlyer" >Delete</button> 
                                    </div>
                                    @endforeach
                                 </div> 
                              @endif
                              <div class="card-header"></div>
                           @endforeach
                        @endif
                     </div>
                        <div class="card-footer">
                           <input type="hidden"  id="Vendor_edit_id" name="Vendor_edit_id" value="@if($vendor!=''){{$vendor->id}}@endif">
                           <!-- <div class="d-flex flex-row bd-highlight mb-3">
                              <div class="p-2 bd-highlight">Flex item 1</div>
                              <div class="p-2 bd-highlight">Flex item 2</div>
                              <div class="p-2 bd-highlight">Flex item 3</div>
                           </div> -->
                           <div class="d-flex flex-row">
                              <div class="">
                                 <div class="form-group">
                                    @if($vendor->vendorDetail&&$vendor->admin_approved!=0&&$vendor->vendorDetail->admin_approved_vendor!=0)
                                       <button type="button" data-id="{{$vendor->vendorDetail->id}}" data-status="0" class="btn btn-primary vendorApproval">Admin Approved Vendor</button>
                                    @else
                                       <button type="button" data-id="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->id}}@endif" data-status="1" class="btn btn-danger vendorApproval">Admin Not Approved Vendor</button>
                                    @endif
                                 </div>
                              </div>
                              <div class="mx-2">
                                 <div class="form-group">
                                    @if($vendor->vendorDetail && $vendor->vendorDetail->plan_id!=0 && $vendor->vendorDetail->admin_approved_vendor_payment!=0)
                                       <button type="button" data-id="{{$vendor->vendorDetail->id}}" data-plan="{{$vendor->vendorDetail->plan_id}}" data-status="0" class="btn btn-primary vendorPaymentApproval">Payment Approved</button>
                                    @else
                                       <button type="button" data-id="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->id}}@endif" data-plan="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->plan_id}}@endif"  data-status="1" class="btn btn-danger vendorPaymentApproval">Payment Not Approved</button>
                                    @endif
                                 </div>
                              </div>
                              <div class="">
                                 <div class="form-group">
                                       <input type="hidden"  id="vendor_id" name="vendor_id" value="{{$vendor->id}}">
                                       <button type="submit" class="btn btn-primary addBtn">Update Details</button>
                                 </div>
                              </div>
                              <div class="mx-2">
                                 <div class="form-group">
                                    
                                       <a class="btn btn-danger" data-toggle="modal" data-target="#modal-add-reason">Reject</a>
                                 </div>
                              </div>
                              <div class="">
                                 <div class="form-group">
                                    @if($vendor->reason!="")
                                       <textarea disabled id="editreason" name="editreason">{{$vendor->reason}}</textarea>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                  </form>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- table or form or list -->
            <!-- /.card -->
         </div>
         <div class="modal fade" id="modal-add-reason" role="dialog">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 class="modal-title">Rejection</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                     <form action="javascript:;" name="reasonForm" id="reasonForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                           <div class="card-body">
                              <div class="form-group">
                                 <label for="exampleInputPassword1">Reason</label>
                                 <textarea class="form-control" id="reason" name="reason"></textarea>
                              </div>
                           </div>
                           <!-- /.card-body -->
                        </div>
                        <div class="modal-footer justify-content-between">
                           <input type="hidden" name="vendor_id" id="vendor_id" value="{{$vendor->id}}">
                           <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('page.close') }}</button>
                           <button class="btn btn-primary reasonBtn">{{ __('page.submit') }}</button>
                        </div>
                     </form>
               </div>
               <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>

<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('script')
<script type="text/javascript">
      $('#vendor_menu').addClass('active');
      window.onload = function() {
         let state_id      = '{{$vendor->vendorDetail->state}}';
         let district_id   = '{{$vendor->vendorDetail->district}}';
         let location_id   = '{{$vendor->vendorDetail->location}}';
         if(state_id!=""&&district_id!=""&&location_id!=""){
            $.ajax({
               url: "{{route('fetchVendorDistrict')}}",
               type: "POST",
               data: {
                  state_id: state_id,
                  district_id:district_id,
                  location_id:location_id,
                  _token: '{{csrf_token()}}'
               },
               dataType: 'json',
               success: function(result) {
                 $('#district').html('<option value="">Select district</option>');
                  $.each(result.district, function (key, value) {
                     let district_id   = result.district_id;
                     let district_val  = value.id;
                     if(district_val==district_id){
                        $("#district").append('<option value="' + value.id + '" selected>' + value.title + '</option>');
                     }else{
                        $("#district").append('<option value="' + value.id + '" >' + value.title + '</option>');
                     }
                  });
                  $('#location').html('<option value="">Select location</option>');
                  $.each(result.location, function (key, value) {
                     let location_id   = result.location_id;
                     let location_val  = value.id;
                     if(location_id==location_val){
                        $("#location").append('<option value="' + value.id + '" selected>' + value.title + '</option>');
                     }else{
                        $("#location").append('<option value="' + value.id + '" >' + value.title + '</option>');
                     }
                  });
               }
            });
         }
         
      };
      $("#VendorForm").validate({
         normalizer : function (value) {
            return $.trim(value);
         },
         ignore: [],
         rules: {
            'vendor_name': {
                  required: true,
            },
            'email':{
                required: true,
                email:true
            }
         },
         messages: {
            vendor_name: "Please Enter the Vendor Name",
            email:{
                required: "Please enter EmailId",
                email:"Please Enter a valid Email"
            }
         },
         submitHandler: function (form) {
            var form        = document.getElementById("VendorForm");
            var formData    = new FormData(form);
            $(".addBtn").prop("disabled", true);
            $.ajax({
                  data: formData,
                  type: "post",
                  url: "{{ route('saveVendor') }}",
                  processData: false,
                  dataType: "json",
                  contentType: false, // The content type used when sending data to the server.
                  cache: false, // To unable request pages to be cached
                  async: true,
                  headers: {
                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                  },
                  beforeSend: function () {
                     $(".addBtn").text('Processing..');
                  },
                  success: function (data) {
                     if(data.status==true){
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                           toastr.success(data.message);
                           setTimeout(function(){location.href="{{ route('showVendor') }}"} , 3000);
                     }else{
                        toastr.error(data.message);
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                     }
                  },
            });
         },
      });
      $("#reasonForm").validate({
         normalizer : function (value) {
            return $.trim(value);
         },
         ignore: [],
         rules: {
            'reason': {
                  required: true,
            },
         },
         messages: {
            reason: "Please Enter Reason",
         },
         submitHandler: function (form) {
            var form        = document.getElementById("reasonForm");
            var formData    = new FormData(form);
            $(".reasonBtn").prop("disabled", true);
            $.ajax({
                  data: formData,
                  type: "post",
                  url: "{{ route('saveRejectReason') }}",
                  processData: false,
                  dataType: "json",
                  contentType: false, // The content type used when sending data to the server.
                  cache: false, // To unable request pages to be cached
                  async: true,
                  headers: {
                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                  },
                  beforeSend: function () {
                     $(".reasonBtn").text('Processing..');
                  },
                  success: function (data) {
                     if(data.status==true){
                        $(".reasonBtn").text('Submit');
                        $(".reasonBtn").prop("disabled", false);
                           toastr.success(data.message);
                           setTimeout(function(){location.href="{{ route('showVendor') }}"} , 3000);
                     }else{
                        toastr.error(data.message);
                        $(".reasonBtn").text('Submit');
                        $(".reasonBtn").prop("disabled", false);
                     }
                  },
            });
         },
      });
   $(document).ready(function () {
      $('.vendorApproval').on('click', function (event) {
         event.preventDefault();
         let vendor_details_id = $(this).data("id");
         let status            = $(this).data("status");
         let url               = "{{route('vendorAdminApproval')}}";
         if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
               url: url,
               type: 'get',
               data: {vendor_details_id: vendor_details_id,status:status},
               dataType: "json",
               success: function (data) {
                  if (data.status == true) {
                     toastr.success(data.message);
                     setTimeout(function () {
                        window.location.reload()
                     }, 1000);
                  }  else {
                        toastr.error(data.message);
                  }
               }
            });
         }
      });
      $('.vendorPaymentApproval').on('click', function (event) {
         event.preventDefault();
         let vendor_details_id   = $(this).data("id");
         let status              = $(this).data("status");
         let plan                = $(this).data("plan");
         let url                 = "{{route('adminApprovalVendorPayment')}}";
         if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
               url: url,
               type: 'get',
               data: {vendor_details_id: vendor_details_id,status:status,plan:plan},
               dataType: "json",
               success: function (data) {
                  if (data.status == true) {
                     toastr.success(data.message);
                     setTimeout(function () {
                        window.location.reload()
                     }, 1000);
                  }  else {
                     toastr.error(data.message);
                  }
               }
            });
         }
      });
      //change state
      $(document).on('change', '#state', function (event) {
         var state_id = this.value;
         $("#district").html('');
         $("#location").html('');
         $.ajax({
            url: "{{route('fetchDistrict')}}",
            type: "POST",
            data: {
               state_id: state_id,
               _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
               $('#district').html('<option value="">Select district</option>');
               $('#location').html('<option value="">Select location</option>');
               $.each(result.district, function (key, value) {
               $("#district").append('<option value="' + value.id + '">' + value.title + '</option>');
               });
            }
         });
      }); 
      //change district
      $(document).on('change', '#district', function (event) {
         var district_id = this.value;
         $("#location").html('');
         $.ajax({
            url: "{{route('fetchLocation')}}",
            type: "POST",
            data: {
               district_id: district_id,
               _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
               $('#location').html('<option value="">Select location</option>');
               $.each(result.location, function (key, value) {
               $("#location").append('<option value="' + value.id + '">' + value.title + '</option>');
               });
            }
         });
      });             
      $('.flyerStatusChange').on('click', function (event) {
        event.preventDefault();
        let flyer_id             = $(this).data("id");
        let status               = $(this).data("status");
        let flyer                = $(this).data("flyer");
        let url                  = "{{route('flyerStatusChange')}}";
        if (confirm('Are you sure you want to change the status?')) {
            $.ajax({
                url: url,
                type: 'get',
                data: {flyer_id: flyer_id,status:status,flyer:flyer},
                dataType: "json",
                success: function (data) {
                if (data.status == true) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location.reload()
                    }, 1000);
                }  else {
                        toastr.error(data.message);
                }
                }
            });
        }
      });
      $('.deleteFlyer').on('click', function (event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete the flyer?')) {
            let flyer_id           = $(this).data("id");
            let status             = $(this).data("status");
            let url                = "{{route('deleteFlyerByAdmin')}}";
            $.ajax({
                url: url,
                type: 'get',
                data: {flyer_id: flyer_id,status:status},
                dataType: "json",
                success: function (data) {
                if (data.status == true) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location.reload()
                    }, 1000);
                }  else {
                        toastr.error(data.message);
                }
                }
            });
        }
      });
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
   });
</script>
@endsection