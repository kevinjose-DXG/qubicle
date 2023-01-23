@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Transactions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Transactions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="row">
                           
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Start Date</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" class="form-control datepicker" id="col1_filter" name="start_date" data-target="#reservationdate" value="{{date('m/d/Y')}}"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">End Date</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" class="form-control datepicker" id="col2_filter" name="end_date" data-target="#reservationdate"  value="{{date('m/d/Y')}}"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card">

                            @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible">
                                <p>{{ $message }}</p>
                            </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body">
                            <table id="datatable-transaction-list" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Order Id</th>
                                        <th scope="col">Payment Id</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Plan</th>
                                        <th scope="col">Amount</th>
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
    $('#report_menu').addClass('menu-is-opening menu-open');
    $('#transaction_report_menu').addClass('active');
    $(function () {
        $('.datepicker').datepicker({

        });
    });
        $(document).ready(function() {
            var dataTable = $('#datatable-transaction-list').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "searching": false,
                responsive: true,
            
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Transaction_Details'
                    },
                ],
                
                'ajax': {
                    'url': "{{ route('filterTransactionDetails') }}",
                    'data': function (data) {
                        // Read values
                        var start_date              = $('#col1_filter').val();
                        var end_date                = $('#col2_filter').val();
                        // Append to data
                        data.filterByStartDate      = start_date;
                        data.filterByEndDate        = end_date;
                    }
                },
                'columns': [
                    
                    {data: 'date'},
                    {data: 'order_id'},
                    {data: 'payment_id'},
                    {data: 'vendor'},
                    {data: 'plan'},
                    {data: 'amount'},
                ],
            });

            $('#col0_filter,#col1_filter, #col2_filter').change(function () {
                dataTable.draw();
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
  </script>
@endsection