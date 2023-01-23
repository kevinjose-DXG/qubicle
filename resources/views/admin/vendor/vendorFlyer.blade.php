@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Vendor Flyer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Vendor Flyer</li>
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
                          <!-- <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-plan">Add New Plan</a> -->
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
                                    <th>Vendor</th>
                                    <th>Plan</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th>Admin Approved</th>
                                    <th width="280px">{{ __('page.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($flyer as $row)
                                    @php $vendor_id = Crypt::encrypt($row->vendor_id);@endphp
                                  <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="{{route('viewVendor',['id'=>$vendor_id])}}"> {{$row->userDetail->name }}</a></td>
                                    <td>{{ $row->planDetail->plans->name }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->start_date }}</td>
                                    <td>{{ $row->end_date }}</td>
                                    <td>{{ $row->duration }}</td>
                                    <td>
                                      @if($row->status=="active") Active @else Inactive @endif
                                    </td>
                                    <td>
                                      <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->admin_approved=="1") checked @endif>
                                    </td>
                                    <td>
                                        @php $catid = Crypt::encrypt($row->id);@endphp
                                        <a href="{{route('viewVendorFlyer',['id'=>$catid])}}" class="btn btn-primary" data-id="{{$row->id}}" href="">View</a>
                                        <a class="btn btn-danger deleteFlyer" data-id="{{$row->id}}"  href="">Delete</a>
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

@endsection
@section('script')
<script type="text/javascript">
  $('#vendor_flyer_menu').addClass('active');
  //change status
  $('body').on('change', '.toggle-class', function (event) {
      event.preventDefault();
      var status  = $(this).prop('checked') == true ? '1' : '0';
      var flyer_id = $(this).data('id');
      let url = "{{route('flyerApprovalByAdmin')}}";
        $.ajax({
          type: "GET",
          dataType: "json",
          url: url,
          data: {'status': status, 'flyer_id': flyer_id},
            success: function(data){
              if(data.status == true) {
                  toastr.success(data.message);
                  setTimeout(function () { window.location.reload() }, 1000);
              }else{
                  toastr.error(data.message);
                  setTimeout(function () { window.location.reload() }, 1000);
              }
            }
        });
  });
  $('body').on('click', '.deleteFlyer', function (event) {
      event.preventDefault();
      var flyer_id = $(this).data('id');
      let url = "{{route('flyerDeleteByAdmin')}}";
        $.ajax({
          type: "GET",
          dataType: "json",
          url: url,
          data: {'flyer_id': flyer_id},
            success: function(data){
              if(data.status == true) {
                  toastr.success(data.message);
                  setTimeout(function () { window.location.reload() }, 1000);
              }else{
                  toastr.error(data.message);
                  setTimeout(function () { window.location.reload() }, 1000);
              }
            }
        });
  });
</script>
@endsection