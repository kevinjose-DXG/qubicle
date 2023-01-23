@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Location</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Location</li>
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
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-Location"> Create Location</a>
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
                                    <th>Location</th>
                                    <th>District</th>
                                    <th>State</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th width="280px">{{ __('page.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                  
                                  @foreach($location as $row)
                                  <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->district->title }}</td>
                                    <td>{{ $row->states->title }}</td>
                                    <td>
                                      <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                    </td>
                                    <td>
                                    @php
                                        $catid = Crypt::encrypt($row->id);
                                    @endphp
                                    <a href="{{route('editLocation', ['id'=>$catid])}}" data-id="{{ $row->id }}" data-field="" class="btn btn-primary"> Edit</a> 
                                        <!-- <a class="btn btn-primary editLocation" data-id="{{$row->id}}" data-district="{{$row->district_id}}" href="">{{ __('page.edit') }}</a> -->
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
  <div class="modal fade" id="modal-add-Location" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Create Location</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
            <form action="javascript:;" name="LocationForm" id="LocationForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">State</label>
                        <select name="state" id="state" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($state as $row)
                                <option value="{{$row->id}}">{{$row->title}}</option>
                            @endforeach
                        </select>
                    </div>
                     
                        <div class="form-group">
                          <label for="exampleInputEmail1">District</label>
                          <select name="district" id="district" class="form-control districtss">
                            
                          </select>
                        </div>
                    
                  <div class="form-group">
                     <label for="exampleInputEmail1">{{ __('page.name') }}</label>
                     <input type="text" class="form-control" id="location" name="location" placeholder="Location">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Description</label>
                     <textarea class="form-control" id="description" name="description"></textarea>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="location_id" id="location_id" value="">
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
    $('#location_menu').addClass('active');
    //submit form
    $("#LocationForm").validate({
        normalizer : function (value) {
            return $.trim(value);
        },
        ignore: [],
        rules: {
            'Location': {
                required: true,
            },
        },
        messages: {
            Location: "Please enter Location",
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
    //edit Location
    $(document).on('click', '.editLocation', function (event) {
        event.preventDefault();
        var id          = $(this).data('id');
        var district_id = $(this).data('district');
        $.ajax({
            type: "get",
            url: "{{route('editLocation','')}}"+"/"+id,
            processData: false,
            dataType: "json",
            success: function (data) {
                $('#state').val(data.location.state_id);
                $('#state').trigger('change');
                $("#district").val(district_id);
                $('#modal-add-Location').modal('show');
                $('.modal-title').html("Edit Location");
                $('#location').val(data.location.title);
                $('#description').val(data.location.description);
                $(".addBtn").text('Update');
                $('#location_id').val(data.location.id);
            },
        });
    });
    //Change Status
    $('body').on('change', '.toggle-class', function (event) {
            event.preventDefault();
            var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
            var cat_id  = $(this).data('id');
            let url     = "{{route('changeLocationStatus')}}";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: url,
                data: {'status': status, 'location_id': cat_id},
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
    //change state
    $(document).on('change', '#state', function (event) {
      var state_id = this.value;
      $("#district").html('');
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
            $.each(result.district, function (key, value) {
              $("#district").append('<option value="' + value.id + '">' + value.title + '</option>');
            });
          }
      });
    });
    $(".modal").on("hidden.bs.modal", function(){
        $(".modal-add-Location").html("");
        $(this).find('form').trigger('reset');
        $('#modal-add-Location').modal('hide');
    });
</script>
@endsection