@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Combo Offers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">Combo Offers</li>
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
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-add-plan">Add Combo Offer</a>
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
                                    <th>Image</th>
                                    <th>{{ __('page.status') }}</th>
                                    <th width="280px">{{ __('page.action') }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($combo as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td><img src="{{asset('/')}}public/storage/combo/{{$row->image}}" width="50px" ></td>
                                        <td>
                                            <input data-id="{{$row->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status=="active") checked @endif>
                                        </td>
                                        <td>
                                            @php $catid = Crypt::encrypt($row->id); @endphp
                                            <a class="btn btn-primary editCombo" data-id="{{$catid}}" href="">{{ __('page.edit') }}</a>
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
  <div class="modal fade" id="modal-add-plan" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Add Combo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
            <form action="javascript:;" name="planForm" id="planForm" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="card-body">
                  <div class="form-group">
                     <label for="exampleInputEmail1">Title</label>
                     <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Category</label>
                        <select name="category[]" id="category[]" class="form-control" multiple>
                          @foreach($category as $row)
                            <option value="{{$row->id}}">{{$row->title}}</option>
                          @endforeach
                        </select>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Price</label>
                     <input type="text" class="form-control" id="price" name="price" placeholder="price">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Description</label>
                     <textarea class="form-control" id="description" name="description"></textarea>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputEmail1">Image</label>
                     <input type="file" class="form-control" id="combo_image" name="combo_image" placeholder="image">
                  </div>
                  <div class="editImage"></div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <input type="hidden" name="combo_id" id="combo_id" value="">
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
  $('#plan_menu').addClass('active');
  //submit form
  $("#planForm").validate({
      normalizer : function (value) {
          return $.trim(value);
      },
      ignore: [],
      rules: {
          'plan': {
              required: true,
          },
      },
      messages: {
          plan: "Please enter plan",
      },
      submitHandler: function (form) {
          var form        = document.getElementById("planForm");
          var formData    = new FormData(form);
          $(".addBtn").prop("disabled", true);
          $.ajax({
              data: formData,
              type: "post",
              url: "{{ route('saveCombo') }}",
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
                          setTimeout(function(){location.href="{{route('showCombo')}}"} , 3000);
                  }else{
                      toastr.error(data.message);
                      $(".addBtn").text('Submit');
                      $(".addBtn").prop("disabled", false);
                  }
              },
          });
      },
  });
  $('body').on('click', '.editCombo', function (event) {
      event.preventDefault();
      var id = $(this).data('id');
      $.ajax({
          type: "get",
          url: "{{route('editCombo','')}}"+"/"+id,
          processData: false,
          dataType: "json",
          success: function (data) {
            console.log(data);
              $('#modal-add-combo').modal('show');
              $('.modal-title').html("Edit Combo");
              $('#combo').val(data.combo.title);
              $('#description').val(data.combo.description);
              $('.editImage').html('<img style="width:100px" src='+'{{asset('/')}}public/storage/combo/'+data.combo.image+'/>');
              $(".addBtn").text('Update');
              $('#combo_id').val(data.combo.id);
          },
      });
  });
  //Change Status
  $('body').on('change', '.toggle-class', function (event) {
      event.preventDefault();
      var status  = $(this).prop('checked') == true ? 'active' : 'inactive';
      var combo_id = $(this).data('id');
      let url = "{{route('changeComboStatus')}}";
        $.ajax({
          type: "GET",
          dataType: "json",
          url: url,
          data: {'status': status, 'combo_id': combo_id},
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
    $(".modal-add-plan").html("");
    $(this).find('form').trigger('reset');
    $('#modal-add-plan').modal('hide');
  });
</script>
@endsection