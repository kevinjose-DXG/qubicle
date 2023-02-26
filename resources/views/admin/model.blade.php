@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>model</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">model</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card">
                        <div class="card-header">
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-model"> Create model</a>
                        </div>
                        @if ($message = Session::get('success'))
                          <div class="alert alert-success alert-dismissible">
                            <p>{{ $message }}</p>
                          </div>
                        @endif
                          <!-- /.card-header -->
                          <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                    <th>{{ __('page.no') }}</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th width="280px">{{ __('page.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($model as $row)
                                    @php
                                    $image =  asset('/').$row->image ;
                                    @endphp
                                  <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->brands->title }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>
                                      <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                    </td>
                                    <td>
                                    @php
                                        $catid = Crypt::encrypt($row->id);
                                    @endphp
                                        <a class="btn btn-primary editModel" data-id="{{$row->id}}" href="">{{ __('page.edit') }}</a>
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                            </table>
                          </div>
                          <!-- /.card-body -->
                      </div>
                    <!-- /.card -->

                <!-- /.modal -->
                </div>
            <!-- /.row -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <div class="modal fade" id="modal-add-model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create model</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="javascript:;" name="modelForm" id="modelForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                  <div class="form-group">
                     <label for="exampleInputEmail1">{{ __('page.name') }}</label>
                     <input type="text" class="form-control" id="model" name="model" placeholder="model">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Brand</label>
                      <select name="brand_id" id="brand_id" class="form-control">
                        <option value="">--Select--</option>
                        @foreach ($brand as $row)
                          <option value="{{$row->id}}">{{$row->title}}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Icon</label>
                     <input type="file" class="form-control" id="model_icon" name="model_icon" placeholder="model Icon">
                  </div>
                  <div class="form-group editImage"></div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Description</label>
                     <textarea class="form-control" id="description" name="description"></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="model_id" id="model_id" value="">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('page.close') }}</button>
                <button class="btn btn-primary addBtn">{{ __('page.submit') }}</button>
              </div>
            </form>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
@endsection
@section('script')
<script type="text/javascript">
  $('#model_menu').addClass('active');
  //submit form
  $("#modelForm").validate({
      normalizer : function (value) {
          return $.trim(value);
      },
      ignore: [],
      rules: {
          'model': {
              required: true,
          },
          'brand_id':{
            required: true,
          }
      },
      messages: {
          model: "Please enter model",
          brand_id:"Please Select Brand"
      },
      submitHandler: function (form) {
          var form        = document.getElementById("modelForm");
          var formData    = new FormData(form);
          $(".addBtn").prop("disabled", true);
          $.ajax({
              data: formData,
              type: "post",
              url: "{{ route('saveModel') }}",
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
                          setTimeout(function(){location.href="{{route('showModel')}}"} , 3000);
                  }else{
                      toastr.error(data.message);
                      $(".addBtn").text('Submit');
                      $(".addBtn").prop("disabled", false);
                  }
              },
          });
      },
  });
  //edit model
  $('body').on('click', '.editModel', function (event) {
      event.preventDefault();
      var id = $(this).data('id');
      $.ajax({
          type: "get",
          url: "{{route('editModel','')}}"+"/"+id,
          processData: false,
          dataType: "json",
          success: function (data) {
              //console.log(data);
            $('#modal-add-model').modal('show');
            $('.modal-title').html("Edit Model");
            $('#model').val(data.model.title);
            $('#brand_id').val(data.model.brand_id);
            $('#description').val(data.model.description);
            $('.editImage').html('<img style="width:100px" src='+'{{asset('/')}}public/storage/model/'+data.model.image+'/>');
            $(".addBtn").text('Update');
            $('#model_id').val(data.model.id);
          },
      });
  });
  //Change Status
  $('body').on('change', '.toggle-class', function (event) {
      event.preventDefault();
      var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
      var cat_id = $(this).data('id');
      let url = "{{route('changeModelStatus')}}";
        $.ajax({
          type: "GET",
          dataType: "json",
          url: url,
          data: {'status': status, 'model_id': cat_id},
            success: function(data){
              if(data.status == true) {
                  toastr.success(data.message);
                  setTimeout(function () { window.location.reload() }, 1000);
              }else{
                  toastr.error(data.message);
              }
            }
        });
  });
  $(".modal").on("hidden.bs.modal", function(){
    $(".modal-add-model").html("");
    $(this).find('form').trigger('reset');
    $('#modal-add-model').modal('hide');
  });
</script>
@endsection