<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  {{-- <title>Home page</title> --}}
  <title>@yield('title_area')</title>
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('FrontEnd') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('FrontEnd') }}/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('FrontEnd') }}/bower_components/Ionicons/css/ionicons.min.css">

  @yield('style_area')

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('FrontEnd') }}/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('FrontEnd') }}/dist/css/skins/_all-skins.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  
</head>
<!-- {{-- <title>@yield('title_area')</title> --}} -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

<body class="hold-transition skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ asset('FrontEnd') }}/index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="{{ asset('FrontEnd') }}/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="notificationID"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('FrontEnd') }}/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('FrontEnd') }}/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                     {{ ('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                  </form>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
    
        <li class="active">
          <a href="{{ url('/dasboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="">
          <a href="{{ url('/teacher/grade') }}">
            <i class="fa fa-dashboard"></i> <span>Grade</span>
          </a>
        </li>
        <li class="">
          <a href="{{ url('/teacher/question_bank') }}">
            <i class="fa fa-dashboard"></i> <span>Grade</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      @yield('content_area')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('FrontEnd') }}/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('FrontEnd') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

@yield('script_area')

<!-- SlimScroll -->
<script src="{{ asset('FrontEnd') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ asset('FrontEnd') }}/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('FrontEnd') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('FrontEnd') }}/dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
<script>
  //this ajaxSetup will be used in each pages for 'post' request
    $.ajaxSetup( {
    headers: {
    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
    }
    });

    notify();

    // function notify()
    // {
    //     var url ='{{ url("/stock_alert/notification") }}';
    //     $.post( url, function( data ) {
    //        var result = data[0]['count(item_id)']; //here json key = 'count(item_id)'
    //         if(result>0)
    //         {
    //             $( "#notificationID" ).html(result);    //show number of notification
    //             $( "#notificationID" ).show();           //notification 'digit' in  bell-icon 'show'
    //         }
    //         else
    //        $( "#notificationID" ).hide(); //notification digit in  bell icon hide

    //     });
    //     setTimeout(notify,2000);    //auto call notify() after 2000 miliseconds
    // }
</script>

</body>
</html>
