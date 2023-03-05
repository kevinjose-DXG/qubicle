@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>support</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">support</li>
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
                          <a class="btn btn-success" data-toggle="modal" data-target="#model-add-support"> Create support</a>
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
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>{{ __('page.status') }}</th>
                                        <th width="280px">{{ __('page.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($support as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->mobile }}</td>
                                        <td>{{ $row->address }}</td>
                                        <td>
                                        <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                        </td>
                                        <td>
                                        @php
                                            $catid = Crypt::encrypt($row->id);
                                        @endphp
                                            <a class="btn btn-primary editSupport" data-id="{{$row->id}}" href="">{{ __('page.edit') }}</a>
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
  <div class="modal fade" id="model-add-support" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Create support</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
            <form action="javascript:;" name="supportForm" id="supportForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mobile</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="support_id" id="support_id" value="">
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
  $('#support_menu').addClass('active');
    //submit form
    $("#supportForm").validate({
        normalizer : function (value) {
            return $.trim(value);
        },
        ignore: [],
        rules: {
            'email': {
                required:true,
                email:true,
                maxlength:25
            },
            'mobile':{
                required:true,
                maxlength:10
            }
        },
        messages: {
            email:{
                required:"Please Enter Email",
                email:"Enter a Valid Email-Id",
                maxlength:"Maximum 25 Characters allowed"
            },
            mobile:{
                required:"Please enter mobile number",
                maxlength:"Maximum 10 integer allowed"
            }
        },
        submitHandler: function (form) {
            var form        = document.getElementById("supportForm");
            var formData    = new FormData(form);
            $(".addBtn").prop("disabled", true);
            $.ajax({
                data: formData,
                type: "post",
                url: "{{ route('saveSupport') }}",
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
                            setTimeout(function(){location.href="{{route('showSupport')}}"} , 3000);
                    }else{
                        toastr.error(data.message);
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                    }
                },
            });
        },
    });
    //edit support
    $('body').on('click', '.editSupport', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "get",
            url: "{{route('editSupport','')}}"+"/"+id,
            processData: false,
            dataType: "json",
            success: function (data) {
                $('#model-add-support').modal('show');
                $('.modal-title').html("Edit Support");
                $('#email').val(data.support.email);
                $('#mobile').val(data.support.mobile);
                $('#address').val(data.support.address);
                $('#description').val(data.support.description);
                $(".addBtn").text('Update');
                $('#support_id').val(data.support.id);
            },
        });
    });
    //Change Status
    $('body').on('change', '.toggle-class', function (event) {
            event.preventDefault();
            var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
            var cat_id  = $(this).data('id');
            let url     = "{{route('changeSupportStatus')}}";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {'status': status, 'support_id': cat_id},
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
        $(".model-add-support").html("");
        $(this).find('form').trigger('reset');
        $('#model-add-support').modal('hide');
    });
</script>
@endsection