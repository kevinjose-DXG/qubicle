@extends('layouts.innerApp')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('page.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('page.user') }}</li>
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
                                    <th>User</th>
                                    <th>Level</th>
                                    <th width="280px">Points</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($user as $row)
                                    @php
                                        $level = 0;
                                        if($row->network_count==0)
                                            $level = 'Level 11';
                                        elseif ($row->network_count==1)
                                            $level = 'Level 10';
                                        elseif ($row->network_count==2)
                                            $level = 'Level 9';
                                        elseif ($row->network_count==3)
                                            $level = 'Level 8';
                                        elseif ($row->network_count==4)
                                            $level = 'Level 7';
                                        elseif ($row->network_count==5)
                                            $level = 'Level 6';
                                        elseif ($row->network_count==6)
                                            $level = 'Level 5';
                                        elseif ($row->network_count==7)
                                            $level = 'Level 4';
                                        elseif ($row->network_count==8)
                                            $level = 'Level 3';  
                                        elseif ($row->network_count==9)
                                            $level = 'Level 2'; 
                                        elseif ($row->network_count==10)
                                            $level = 'Level 1';                       
                                        elseif ($row->network_count>10)
                                            $level = 'Level 1';     
                                    @endphp
                                  <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                    
                                     {{$level}}
                                    </td>
                                    <td>
                                        {{$row->network_count}}
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
  $('#user_menu').addClass('active');

</script>
@endsection