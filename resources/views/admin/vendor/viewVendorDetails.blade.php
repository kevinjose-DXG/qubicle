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
               <h1>View Vendor</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">View Vendor</li>
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
                      <div class="card-body">
                        <div class="row">
                           <div class="col-sm-6">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Name</label>
                                 <input type="text" name="Vendor_name" id="Vendor_name" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->name}}@endif" disabled>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Email</label>
                                 <input type="text" name="email" id="email" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->email}}@endif" disabled>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Mobile</label>
                                 <input disabled type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->mobile}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="card-header">
                           <h3 class="card-title">Business Details</h3>
                        </div>
                        <div class="row">
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Business Name</label>
                                 <input disabled type="text" name="business_name" id="business_name" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->reg_business_name}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Business Category</label>
                                 <select disabled class="form-control" name="business_category" id="business_category">
                                    @foreach($business_category as $row)
                                    <option value="{{$row->id}}"@if($row->id==$vendor->vendorDetail->business_category)selected @endif>{{$row->title}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-1">
                              <div class="form-group">
                                 <label>Shop Category</label>
                                 <select disabled class="form-control" name="shop_category" id="shop_category">
                                 @foreach($category as $row)
                                    <option value="{{$row->id}}"@if($row->id==$vendor->vendorDetail->shop_category)selected @endif>{{$row->title}}</option>
                                 @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Shop Name</label>
                                 <input disabled type="text" name="shop_name" id="shop_name" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->shop_name}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Gst No</label>
                                 <input disabled type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->gst_no}} @endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Mobile</label>
                                 <input disabled type="text" name="business_mobile" id="business_mobile" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->mobile_no}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Email</label>
                                 <input disabled type="text" name="business_email" id="business_email" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->email_id}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Address 1</label>
                                 <input disabled type="text" name="address1" id="address1" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->address1}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Address 2</label>
                                 <input disabled type="text" name="address2" id="address2" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->address2}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Location</label>
                                 <input disabled type="text" name="location" id="location" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->locations->title}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>District</label>
                                 <input disabled type="text" name="district" id="district" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->districts->title}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>State</label>
                                 <input disabled type="text" name="state" id="state" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->states->title}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-2">
                              <div class="form-group">
                                 <label>Pin</label>
                                 <input disabled type="text" name="pin" id="pin" class="form-control" placeholder="Enter ..." value="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->pin}}@endif">
                              </div>
                           </div>
                           
                        </div>
                        @if($vendor->vendorDetail->plan_id!='0') 
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
                        
                        @if($vendor->vendorDetail->planDetail&&count($vendor->vendorDetail->planDetail->flyer)>0)
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
                                 </div>
                                 @foreach($flyer_row->flyers as $row)
                                    <div class="col-sm-2">
                                       <div class="form-group">
                                          <br>
                                          <img src="{{asset('/')}}public/storage/flyers/{{$row->flyers}}" width="200px">
                                       </div>
                                    </div>
                                 @endforeach
                              </div> 
                              @endif
                              <div class="card-header"></div>
                           @endforeach
                        @endif
                     </div>
                           <!-- <div class="card-footer">
                              <div class="d-flex flex-row">
                                 <div class="">
                                    <div class="form-group">
                                       @if($vendor->vendorDetail&&$vendor->vendorDetail->admin_approved_vendor!=0)
                                          <button type="button" data-id="{{$vendor->vendorDetail->id}}" data-status="0" class="btn btn-primary vendorApproval">Admin Approved Vendor</button>
                                       @else
                                          <button type="button" data-id="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->id}}@endif" data-status="1" class="btn btn-danger vendorApproval">Admin Not Approved Vendor</button>
                                       @endif
                                    </div>
                                 </div>
                                 <div class="mx-2">
                                    <div class="form-group">
                                       @if($vendor->vendorDetail&&$vendor->vendorDetail->plan_id!=0&&$vendor->vendorDetail->admin_approved_vendor_payment!=0)
                                          <button type="button" data-id="{{$vendor->vendorDetail->id}}" data-plan="{{$vendor->vendorDetail->plan_id}}" data-status="0" class="btn btn-primary vendorPaymentApproval">Payment Approved</button>
                                       @else
                                          <button type="button" data-id="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->id}}@endif" data-plan="@if($vendor->vendorDetail!=''){{$vendor->vendorDetail->plan_id}}@endif"  data-status="1" class="btn btn-danger vendorPaymentApproval">Payment Not Approved</button>
                                       @endif
                                    </div>
                                 </div>
                                 <div class="">
                                    <div class="form-group">
                                       @if($vendor->reason=="")
                                          <a class="btn btn-danger" data-toggle="modal" data-target="#modal-add-reason">Reject</a>
                                       @else
                                          <a class="btn btn-danger">Rejected</a>
                                       @endif
                                    </div>
                                 </div>
                                 <div class="mx-2">
                                    <div class="form-group">
                                       @if($vendor->reason!="")
                                          <span>{{$vendor->reason}}</span>
                                       @endif
                                    </div>
                                 </div>
                              </div>
                           </div> -->
                        </div>
               <!-- /.card-body -->
            </div>
            <!-- table or form or list -->
            <!-- /.card -->
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
   $('#Vendor_menu').addClass('active');
  
   $(document).ready(function () {
      $('.vendorApproval').on('click', function (event) {
         event.preventDefault();
         let vendor_details_id = $(this).data("id");
         let status            = $(this).data("status");
         let url               = "{{route('vendorAdminApproval')}}";
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
      });
      $('.vendorPaymentApproval').on('click', function (event) {
         event.preventDefault();
         let vendor_details_id   = $(this).data("id");
         let status              = $(this).data("status");
         let plan                = $(this).data("plan");
         let url                 = "{{route('adminApprovalVendorPayment')}}";
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
      });
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
   });
</script>
@endsection