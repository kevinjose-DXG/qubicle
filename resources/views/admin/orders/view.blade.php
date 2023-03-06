@extends('layouts.innerApp')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>View Order</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
                  <li class="breadcrumb-item active">View Order</li>
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
                     <h3 class="card-title">Order Details</h3>
                  </div>
                  <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Order No</label>
                                 <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->order_no}}@endif" disabled>
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>Customer</label>
                                 <input type="text" name="customer" id="customer" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->customer->name}}@endif" disabled>
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>order date</label>
                                 <input disabled type="text" name="order_date" id="order_date" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->order_date}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>order status</label>
                                 <input disabled type="text" name="order_status" id="order_status" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->order_status}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>payment status</label>
                                 <input disabled type="text" name="payment_status" id="payment_status" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->payment_status}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>sub total</label>
                                 <input disabled type="text" name="sub_total" id="sub_total" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->sub_total}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>delivery charge</label>
                                 <input disabled type="text" name="delivery_charge" id="delivery_charge" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->delivery_charge}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>discount amount</label>
                                 <input disabled type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->discount_amount}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>tax</label>
                                 <input disabled type="text" name="tax" id="tax" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->tax}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>grand total</label>
                                 <input disabled type="text" name="grand_total" id="grand_total" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->grand_total}}@endif">
                              </div>
                           </div>
                           <div class="col-sm-3">
                              <!-- text input -->
                              <div class="form-group">
                                 <label>delivery date</label>
                                 <input disabled type="text" name="delivery_date" id="delivery_date" class="form-control" placeholder="Enter ..." value="@if($order!=''){{$order->delivery_date}}@endif">
                              </div>
                           </div>
                        </div>
                        <div class="row">
              <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card">
                        <!-- <div class="card-header">
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-brand"> Create Brand</a>
                        </div> -->
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
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Customized Image</th>
                                        <th>Customized Text</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($order->orderDetail as $row)
                                  <tr>
                                    <td>{{ $row->category->title }}</td>
                                    <td>@if($row->subcategory!=""){{ $row->subcategory->title }}@endif</td>
                                    <td>@if($row->brands!=""){{ $row->brands->title }}@endif</td>
                                    <td>@if($row->brandmodel!=""){{ $row->brandmodel->title }}@endif</td>
                                    <td>
                                        @if($row->image_customize!="")
                                           @php $image_path = $row->image_customize; @endphp
                                            <a href="{{ route('download-image', ['path' => $image_path]) }}">Download Image</a>  
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->text_customize!="")
                                          {{$row->text_customize}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$row->quantity}}
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
   $('#Order_menu').addClass('active');
  
   $(document).ready(function () {
     
      $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });
   });
</script>
@endsection