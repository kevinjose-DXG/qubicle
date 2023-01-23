@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Vendor Approval Panel</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Vendor Approval Panel</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card">
                  <div class="card-header">
                    <!-- <a class="btn btn-success" href=""> Add Product</a> -->
                  </div>
                    @if ($message = Session::get('success'))
                      <div class="alert alert-success alert-dismissible">
                        <p>{{ $message }}</p>
                      </div>
                    @endif
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="datatable-vendor-list" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                          <tr>
                            <th scope="col">
                            <input type="text" class="form-control" placeholder="Name" name="name" id="col0_filter" value="">
                            </th>
                            <th scope="col">
                                <input type="text" class="form-control" placeholder="Email" name="email" id="col1_filter" value="">
                            </th>
                            <th scope="col">
                                <input type="text" class="form-control" placeholder="Mobile" name="mobile" id="col2_filter" value="">
                            </th>
                            <th scope="col">
                                <select class="form-control" tabindex="1" style="width: 100%" name="status" id="col3_filter">
                                  <option value="">All</option>
                                  <option value="active">Active</option>
                                  <option value="inactive">Inactive</option>
                                </select>
                            </th>
                            <th scope="col">
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
              <!-- /.card -->

              <!-- /.modal -->
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
    $('#pending_vendor_menu').addClass('active');
      $(document).ready(function() {
          var dataTable = $('#datatable-vendor-list').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            "searching": false,
            responsive: true,
              'ajax': {
                'url': "{{ route('filterPendingVendor') }}",
                'data': function (data) {
                    // Read values
                    var vendor_name             = $('#col0_filter').val();
                    var email                   = $('#col1_filter').val();
                    var mobile                  = $('#col2_filter').val();
                    var status                  = $('#col3_filter').val();
                    // Append to data
                    data.filterByVendor         = vendor_name;
                    data.filterByEmail          = email;
                    data.filterByMobile         = mobile;
                    data.filterByStatus         = status;
                }
              },
            'columns': [
                {data: 'vendor_name'},
                {data: 'email'},
                {data: 'mobile'},
                {data: 'status'},
                {data: 'actions'},
            ],
          });
        $('#col0_filter, #col1_filter, #col3_filter').keyup(function () {
            dataTable.draw();
        });
        $('#col2_filter, #col4_filter').change(function () {
            $('#col0_filter').val('');
            $('#col1_filter').val('');
            dataTable.draw();
        });
      });
        $('#datatable-vendor-list').on('click', '.deleteBtn', function (event) {
          let product_id = $(this).data("id");
          let url = "";
          $.ajax({
              url: url,
              type: 'get',
              data: {product_id: product_id},
              dataType: "json",
              success: function (data) {
                  if (data.status == true) {
                      toastr.success(data.message);
                      setTimeout(function () {
                          window.location.reload()
                      }, 1000);
                  } else {
                      toastr.error(data.message);
                  }
              }
          });
        });
      $('#datatable-vendor-list').on('click', '.statusBtn', function (event) {
        let vendor_id   = $(this).data("id");
        let status      = $(this).data("prostatus");
        let url         = "{{route('changeVendorStatus')}}";
        $.ajax({
            url: url,
            type: 'get',
            data: {vendor_id: vendor_id,status:status},
            dataType: "json",
            success: function (data) {
                if (data.status == true) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        window.location.reload()
                    }, 1000);
                } else {
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
  </script>
@endsection