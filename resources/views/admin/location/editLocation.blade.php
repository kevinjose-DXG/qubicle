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
               <h1>Edit Location</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">Edit Location</li>
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
                     <h3 class="card-title">Location Details</h3>
                  </div>
                  <!-- /.card-header -->
                  <form action="javascript:;" name="LocationForm" id="LocationForm">
                     @csrf
                      <div class="card-body">
                        <div class="row">
                           <div class="col-sm-6">
                              <!-- text input -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">State</label>
                                    <select name="state" id="state" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($state as $row)
                                            <option value="{{$row->id}}"@if($row->id==$location->state_id) selected @endif>{{$row->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                           </div>
                           <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">District</label>
                                    <select name="district" id="district" class="form-control districtss">
                                        @foreach($location->states->district as $row)
                                            <option value="{{$row->id}}"@if($row->id==$location->district_id) selected @endif>{{$row->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ __('page.name') }}</label>
                                    <input type="text" class="form-control" id="location" name="location" placeholder="Location" value="{{$location->title}}">
                                </div>
                            </div>
                           <div class="col-sm-4">
                              <!-- select -->
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Description</label>
                                    <textarea class="form-control" id="description" name="description">{{$location->description}}</textarea>
                                </div>
                           </div>
                        </div>
                        </div>
                        <div class="card-footer">
                            <input type="hidden"  id="location_id" name="location_id" value="{{$location->id}}">
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
   $('#location_menu').addClass('active');
    //submit form
    $("#LocationForm").validate({
        normalizer : function (value) {
            return $.trim(value);
        },
        ignore: [],
        rules: {
            'location': {
                required: true,
            },
        },
        messages: {
            location: "Please enter Location",
        },
        submitHandler: function (form) {
            var form        = document.getElementById("LocationForm");
            var formData    = new FormData(form);
            $(".addBtn").prop("disabled", true);
            $.ajax({
                data: formData,
                type: "post",
                url: "{{ route('saveLocation') }}",
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
                            setTimeout(function(){location.href="{{route('showLocation')}}"} , 3000);
                    }else{
                        toastr.error(data.message);
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                    }
                },
            });
        },
    });
   
</script>
@endsection