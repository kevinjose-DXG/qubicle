@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Designed By</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Designed By</li>
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
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-designed"> Create Designed By</a>
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
                                    <th>{{ __('page.name') }}</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Description</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th width="280px">{{ __('page.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($designed_by as $row)
                                  <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ $row->mobile }}</td>
                                    <td>{{ $row->description }}</td>
                                    <td>
                                      <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                    </td>
                                    <td>
                                    @php
                                        $catid = Crypt::encrypt($row->id);
                                    @endphp
                                        <a class="btn btn-primary editDesigned" data-id="{{$row->id}}" href="">{{ __('page.edit') }}</a>
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
  <div class="modal fade" id="modal-add-designed" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Create Designed</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
            <form action="javascript:;" name="DesignedForm" id="DesignedForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                  <div class="form-group">
                     <label for="exampleInputEmail1">{{ __('page.name') }}</label>
                     <input type="text" class="form-control" id="designed" name="designed" placeholder="designed">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Email</label>
                     <input type="text" class="form-control" id="email" name="email" placeholder="email">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Mobile</label>
                     <input type="text" class="form-control" id="mobile" name="mobile" placeholder="mobile">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Description</label>
                     <textarea class="form-control" id="description" name="description"></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="designed_id" id="designed_id" value="">
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
  $('#designed_menu').addClass('active');
    //submit form
    $("#DesignedForm").validate({
        normalizer : function (value) {
            return $.trim(value);
        },
        ignore: [],
        rules: {
            'designed': {
                required: true,
            },
            'email':{
                email:true
            },
            'mobile':{
                number:true
            }
        },
        messages: {
            designed: "Please enter Designed By",
            email: "Please enter Valid Email Id",
            mobile:"Please enter a valid mobile number"
        },
        submitHandler: function (form) {
            var form        = document.getElementById("DesignedForm");
            var formData    = new FormData(form);
            $(".addBtn").prop("disabled", true);
            $.ajax({
                data: formData,
                type: "post",
                url: "{{ route('saveDesigned') }}",
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
                            setTimeout(function(){location.href="{{route('showDesigned')}}"} , 3000);
                    }else{
                        toastr.error(data.message);
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                    }
                },
            });
        },
    });
    //edit designed
    $('body').on('click', '.editDesigned', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "get",
            url: "{{route('editDesigned','')}}"+"/"+id,
            processData: false,
            dataType: "json",
            success: function (data) {
                //console.log(data);
                $('#modal-add-designed').modal('show');
                $('.modal-title').html("Edit Designed By");
                $('#designed').val(data.designed.name);
                $('#email').val(data.designed.email);
                $('#mobile').val(data.designed.mobile);
                $('#description').val(data.designed.description);
                $(".addBtn").text('Update');
                $('#designed_id').val(data.designed.id);
            },
        });
    });
    //Change Status
    $('body').on('change', '.toggle-class', function (event) {
            event.preventDefault();
            var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
            var cat_id  = $(this).data('id');
            let url     = "{{route('changeDesignedStatus')}}";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {'status': status, 'designed_id': cat_id},
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
        $(".modal-add-designed").html("");
        $(this).find('form').trigger('reset');
        $('#modal-add-designed').modal('hide');
    });
</script>
@endsection