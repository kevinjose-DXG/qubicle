@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Policy</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Policy</li>
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
                          <a class="btn btn-success" data-toggle="modal" data-target="#model-add-policy"> Create policy</a>
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
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>{{ __('page.status') }}</th>
                                        <th width="280px">{{ __('page.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($policy as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td>{{ substr($row->content,0, 50).'...' }}</td>
                                        <td>
                                        <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                        </td>
                                        <td>
                                        @php
                                            $catid = Crypt::encrypt($row->id);
                                        @endphp
                                            <a class="btn btn-primary editPolicy" data-id="{{$row->id}}" href="">{{ __('page.edit') }}</a>
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
  <div class="modal fade" id="model-add-policy" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Create policy</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
            <form action="javascript:;" name="policyForm" id="policyForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <select name="title" class="form-control" id="title">
                            <option value=""></option>
                            <option value="terms_condition">Terms&Condition</option>
                            <option value="about_us">About Us</option>
                            <option value="privacy_policy">Privacy Policy</option>
                            <option value="refund_policy">Refund Policy</option>
                        </select>
                    </div>
                   <div class="form-group">
                        <label for="exampleInputPassword1">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" cols="3"></textarea>
                    </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="policy_id" id="policy_id" value="">
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
  $('#policy_menu').addClass('active');
    //submit form
    $("#policyForm").validate({
        normalizer : function (value) {
            return $.trim(value);
        },
        ignore: [],
        rules: {
            'title': {
                required:true,
                
            },
            'description':{
                required:true,
                
            }
        },
        messages: {
            title:{
                required:"Please Select Title",
                
            },
            description:{
                required:"Please enter description",
                
            }
        },
        submitHandler: function (form) {
            var form        = document.getElementById("policyForm");
            var formData    = new FormData(form);
            $(".addBtn").prop("disabled", true);
            $.ajax({
                data: formData,
                type: "post",
                url: "{{ route('savePolicy') }}",
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
                            setTimeout(function(){location.href="{{route('showPolicy')}}"} , 3000);
                    }else{
                        toastr.error(data.message);
                        $(".addBtn").text('Submit');
                        $(".addBtn").prop("disabled", false);
                    }
                },
            });
        },
    });
    //edit policy
    $('body').on('click', '.editPolicy', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "get",
            url: "{{route('editPolicy','')}}"+"/"+id,
            processData: false,
            dataType: "json",
            success: function (data) {
                $('#model-add-policy').modal('show');
                $('.modal-title').html("Edit Policy");
                $('#title').val(data.policy.title);
                $('#description').val(data.policy.content);
                $(".addBtn").text('Update');
                $('#policy_id').val(data.policy.id);
            },
        });
    });
    //Change Status
    $('body').on('change', '.toggle-class', function (event) {
            event.preventDefault();
            var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
            var cat_id  = $(this).data('id');
            let url     = "{{route('changePolicyStatus')}}";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {'status': status, 'policy_id': cat_id},
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
        $(".model-add-policy").html("");
        $(this).find('form').trigger('reset');
        $('#model-add-policy').modal('hide');
    });
</script>
@endsection