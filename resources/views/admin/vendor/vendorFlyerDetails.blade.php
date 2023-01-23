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
               <h1>View Vendor Flyer</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">View Vendor Flyer</li>
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
                     <h3 class="card-title">Vendor Flyer Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <form action="javascript:;" name="VendorFlyerForm" id="VendorFlyerForm">
                     @csrf
                      <div class="card-body">
                        <div class="row">
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Vendor Name</label>
                                 <input type="text" name="vendor_id" id="vendor_id" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->userDetail->name}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Plan</label>
                                 <input type="text" name="plan_id" id="plan_id" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->planDetail->plans->name}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Designed by</label>
                                 <input type="text" name="designed_by" id="designed_by" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->designer->name}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Title</label>
                                 <input type="text" name="title" id="title" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->title}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Start Date</label>
                                 <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->start_date}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-4">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>End Date</label>
                                 <input type="text" name="end_date" id="end_date" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->end_date}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Duration</label>
                                 <input type="text" name="duration" id="duration" class="form-control" placeholder="Enter ..." value="@if($vendor!=''){{$vendor->duration}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Description</label>
                                 <textarea name="description" id="description" class="form-control">{{$vendor->description}}</textarea>
                              </div>
                           </div>
                        </div>
                        <div class="card-header">
                           <h3 class="card-title">Flyer Details</h3>
                        </div>
                        <div class="row">
                           <div class="col-sm-2">
                              <div class="form-group">
                                  <br>
                                 <img src="{{asset('/')}}public/storage/flyers/{{$vendor->thumb_image}}" width="200px">
                              </div>
                           </div>
                           @foreach($vendor->flyers as $row)
                              <div class="col-sm-2">
                                 <div class="form-group">
                                    <br>
                                    <img src="{{asset('/')}}public/storage/flyers/{{$row->flyers}}" width="200px">
                                 </div>
                              </div>
                           @endforeach
                        </div>
                     </div>
                        <div class="card-footer">
                           <div class="col-sm-8">
                              <div class="form-group">
                                 <a href="{{route('showVendorFlyer')}}" class="btn btn-primary">Back</a>
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
            <div class="row">
               <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card">
                        
                        @if ($message = Session::get('success'))
                          <div class="alert alert-success alert-dismissible">
                            <p>{{ $message }}</p>
                          </div>
                        @endif
                        
                          <!-- /.card-body -->
                      </div>
                    <!-- /.card -->
                <!-- /.modal -->
                </div>
               <!-- /.row -->
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
   $("#VendorFlyerForm").validate({
      normalizer : function (value) {
         return $.trim(value);
      },
      ignore: [],
      rules: {
         'Vendor_name': {
               required: true,
         },
      },
      messages: {
         Vendor_name: "Please Enter the Vendor Name",
      },
      submitHandler: function (form) {
         var form        = document.getElementById("VendorFlyerForm");
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