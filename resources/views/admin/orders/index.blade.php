@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Orders</li>
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
                      <table id="datatable-order-list" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th scope="col">Order No</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Address</th>
                            <th scope="col">Order Status</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Price</th>
                            <th scope="col">Action</th>
                          </tr>
                          <tr>
                            <th scope="col">
                              <input type="text" class="form-control" placeholder="order_no" name="order_no" id="col0_filter" value="">
                            </th>
                            <th scope="col">
                                
                            </th>
                            <th scope="col">
                                
                            </th>
                            <th scope="col">
                                
                            </th>
                            <th scope="col">
                                <select class="form-control" tabindex="1" style="width: 100%" name="order_status" id="col1_filter">
                                  <option value="">All</option>
                                  <option value="onprocess">on process</option>
                                  <option value="confirmed">confirmed</option>
                                  <option value="shipped">shipped</option>
                                  <option value="deliverd">deliverd</option>
                                  <option value="cancelled">cancelled</option>
                                </select>
                            </th>
                            <th scope="col">
                                <select class="form-control" tabindex="1" style="width: 100%" name="payment_status" id="col2_filter">
                                  <option value="">All</option>
                                  <option value="paid">paid</option>
                                  <option value="notpaid">not paid</option>
                                  <option value="cancelled">cancelled</option>.
                                  <option value="refunded">Refunded</option>
                                </select>
                            </th>
                            <th scope="col">
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
    $('#vendor_menu').addClass('active');
    $(document).ready(function() {
        var dataTable = $('#datatable-order-list').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          "searching": false,
          responsive: true,
            'ajax': {
              'url': "{{ route('filterOrder') }}",
                'data': function (data) {
                  // Read values
                  var order_no                = $('#col0_filter').val();
                  var order_status            = $('#col1_filter').val();
                  var payment_status          = $('#col2_filter').val();
                  // Append to data
                  data.filterByOrderNo        = order_no;
                  data.filterByOrderStatus    = order_status;
                  data.filterByPaymentStatus  = payment_status;
                }
            },
          'columns': [
              {data: 'order_no'},
              {data: 'order_date'},
              {data: 'customer'},
              {data: 'address'},
              {data: 'order_status'},
              {data: 'payment_status'},
              {data: 'price'},
              {data: 'actions'},
          ],
        });
      $('#col0_filter').keyup(function () {
          dataTable.draw();
      });
      $('#col1_filter, #col2_filter').change(function () {
          $('#col0_filter').val('');
          dataTable.draw();
      });
    });
    $('#datatable-order-list').on('click', '.deleteBtn', function (event) {
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
    $('#datatable-order-list').on('change', '.statusBtn', function (event) {
      let order_status    = $(this).val();
      let order           = $(this)[0].id;
      let order_id        = order.split("_").pop();
      let url             = "{{route('changeOrderstatus')}}";
      if (confirm('Are you sure you want to Update?')) {
        $.ajax({
            url: url,
            type: 'get',
            data: {order_id: order_id,order_status:order_status},
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
      }
    });
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
@endsection