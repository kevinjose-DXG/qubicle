@extends('layouts.innerApp')
@section('content')
<style>
   #main_image-error{
      width: 200px;
      margin-top: 80px;
   }
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
               <h1>Edit Plan</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">Edit plan</li>
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
                     <h3 class="card-title">Plan Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <form action="javascript:;" name="planForm" id="planForm">
                     @csrf
                      <div class="card-body">
                        <div class="row">
                           <div class="col-sm-6">
                              <!-- text input -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Plan</label>
                                    <input type="text" class="form-control" id="plan" name="plan" placeholder="plan" value="{{$plan->name}}">
                                </div>
                           </div>
                           <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mrp</label>
                                    <input type="text" class="form-control" id="mrp" name="mrp" placeholder="Mrp" value="{{$plan->mrp}}">
                                </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">No Of Months</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="Duration" value="{{$plan->duration}}">
                                </div>
                            </div>
                           <div class="col-sm-4">
                              <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">No.Of Flyers</label>
                                    <input type="text" class="form-control" id="no_of_flyers" name="no_of_flyers" placeholder="No Of Flyers" value="{{$plan->no_of_flyers}}">
                                </div>
                           </div>
                           <div class="col-sm-4">
                              <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">No Of Images Per Flyer</label>
                                    <input type="text" class="form-control" id="no_of_images_per_flyers" name="no_of_images_per_flyers" placeholder="No Of Images Per Flyer" value="{{$plan->no_of_images_per_flyers}}">
                                </div>
                           </div>
                            <div class="col-sm-4">
                                <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Description</label>
                                    <textarea class="form-control" id="description" name="description">{{$plan->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <table id="highlights" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Highlights</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="highlightbody">
                                @foreach($plan['highlights'] as $row)
                                    <tr id="highlight-row_{{$loop->iteration}}">
                                        <td>
                                        <input type="text" name="highlight[]" id="highlight_{{$loop->iteration}}" class="form-control" placeholder="Enter ..." value="{{$row->highlights}}">
                                        </td>
                                        <td><button type="button" class="btn btn-danger deleteBtn" data-id="{{$loop->iteration}}">Delete</button></td>
                                    </tr>
                                @endforeach
                              
                            </tbody>
                        </table>
                        @php
                           $count_hightlight = count($plan->highlights);
                           @endphp
                           <input type="hidden" name="highlight_count" id="highlight_count" value="{{$count_hightlight}}">
                             <br>
                        </div>
                      <div class="card-footer">
                        <input type="hidden"  id="plan_edit_id" name="plan_edit_id" value="{{$plan->id}}">
                        <button type="submit" class="btn btn-primary addBtn">Submit</button>
                      </div>
                  </form>
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
   $('#plan_menu').addClass('active');
   
   $("#planForm").validate({
      normalizer : function (value) {
         return $.trim(value);
      },
      ignore: [],
      rules: {
         'plan': {
            required: true,
         },
         'category':{
            required:true
         },
         'price':{
            required:true
         },
         'sales_price':{
            required:true
         },
         'discount_price':{
            required: true,
            number:true
         },
         'description':{
            required: true,
         },
         'main_image': {
            required: true,
         },
         'stock':{
            required: true,
         },
         'other_images':{
            required: true,
         },
         'highlight[]':{
            required: true,
         }
      },
      messages: {
         plan_name: "Please Enter the plan Name",
         category: "Please Select Category",
         price:"Please Enter Price",
         sales_price : "Please Enter Sales Price",
         main_image: "Select One Main Image",
         stock: "Please Enter Stock",
         discount_price: {
            required: "Please Enter the Discount",
            number: "Please Enter a Valid Number"
         },
         description: "Please Enter Description",
         other_images:"Select Atleast One Image",
         "highlight[]" : "Please Enter One Highlight"
      },
      submitHandler: function (form) {
         var form        = document.getElementById("planForm");
         var formData    = new FormData(form);
         $(".addBtn").prop("disabled", true);
         $.ajax({
               data: formData,
               type: "post",
               url: "{{ route('savePlan') }}",
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
                           setTimeout(function(){location.href="{{ route('showPlan') }}"} , 3000);
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
        $(document).on('click','.add-highlight',function(){
            var highlight_count        = 0;
            highlight_count            = parseInt($('#highlight_count').val());
            let futureval              = highlight_count + 1;
            $(".highlightbody").append(`<tr id="highlight-row_${futureval}"><td>
                                       <input type="text" name="highlight[]" id="highlight_${futureval}" class="form-control" placeholder="Enter ...">
                                    </td><td><button type="button" class="btn btn-danger deleteBtn" data-id="${futureval}">Delete</button></td></tr>`);
        });
        $(document).on('click','.deleteBtn',function(){
            var highlight_length = $('.deleteBtn').length;
            if(highlight_length>1){
                let del_row_id                     = $(this).data('id');
                let service_row_id                 = $('#highlight_count').val();
                let service_row_next_id            = (parseInt(service_row_id)-1);
                $('#highlight_count').val(service_row_next_id);
                $(this).closest('tr').remove();
            }else{
               toastr.error("Atleast One Highlight is required");
               return false;
            }
        });
    });
</script>
@endsection
