<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ env('APP_NAME') }}</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('/') }}images/favicon.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/') }}css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('/') }}css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('/') }}css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('/') }}css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('/') }}css/select2.min.css">
  <link rel="stylesheet" href="{{ asset('/') }}css/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css" >
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/') }}css/adminlte.min.css">
  
  <style type="text/css">
	.dropdown h2{
           color: #fff;
           font-size: 20px;
	}
	.dropdown p{
           color: #fff;
	}
	.bg-gray{
		background-color: #343a40;
	}
  .error{
    color: red;
  }
  .toggle-off.btn{padding-left: 20px !important;}
</style>
</head>
<body class="sidebar-mini control-sidebar-slide-open sidebar-collapse">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <div class="dropdown" style="margin-right:120px;">
          <img src="{{ asset('/') }}images/avatar.png" width="50" height="50"  class="dropdown-toggle rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" alt="">
        <div class="dropdown-menu profle-blk p-2" aria-labelledby="dropdownMenuButton1">
            <div class="bg-gray">
              <div class="d-flex justify-content-center pt-2">
                <img src="{{ asset('/') }}images/avatar.png" width="100" height="100"   class="rounded-circle p-2">
              </div>
                <h2 class="text-center pt-1">{{Auth::user()->name}}</h2>
                <p class="text-center pb-1">{{Auth::user()->email}}</p>
            </div>
            <div class="profile-btn d-flex" style="justify-content: center;">
              <!-- <button type="button" class="btn btn-outline-secondary mr-3">{{ __('page.profile')}}</button> -->
              <a href="{{ route('admin.logout') }}"  class="btn btn-outline-secondary">{{ __('page.logout')}}</a>
            </div>
        </div>
      </div>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ asset('/')  }}images/mail_logo.png" alt="AdminLTE Logo" class="brand-image img" style="opacity: .8">
      <span class="brand-text font-weight-light">IWILLFLY</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> -->

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      @include('layouts.sidenav.sideNav')
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  @yield('content')
  <footer class="main-footer">
    <strong>{{ __('page.copyright')}} &copy; {{ date('Y')}} <a href="{{ route('dashboard')}}">IWILLFLY</a>.</strong>
    {{ __('page.all_rights_reserved')}}
    <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div> -->
  </footer>
  <!-- Control Sidebar -->
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="{{ asset('/') }}js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/') }}js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/') }}js/select2.full.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('/') }}js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script src="{{ asset('/') }}js/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('/') }}js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/') }}js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}js/buttons.html5.min.js"></script>
<script src="{{ asset('/') }}js/buttons.print.min.js"></script>
<script src="{{ asset('/') }}js/buttons.colVis.min.js"></script>
<script src="{{ asset('/') }}js/bootstrap-switch.min.js"></script>
<script src="{{ asset('/') }}js/bs-custom-file-input.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/') }}js/adminlte.min.js"></script>
<script src="{{ asset('/')}}js/jquery.validate.js"></script>

<!-- AdminLTE for demo purposes -->

<!-- Page specific script -->
@yield('script')
<script>
  $(function () {
    $("#example1").DataTable({
      
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('.select2').select2();
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
    bsCustomFileInput.init();
  });
</script>
</body>
</html>
