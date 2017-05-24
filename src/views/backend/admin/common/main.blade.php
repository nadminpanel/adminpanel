<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
<style>
    .hov:hover{
        background-color: #1e282c!important;
    }
</style>
    @include('nadminpanel::backend.admin.common.head')
    @yield('extra-css')
    <!-- Theme style -->
    <link rel="stylesheet" href="/backend/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/backend/adminlte/dist/css/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>
<body class="hold-transition hov skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo" style="background-color: #1e282c">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>c2vu</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                <strong>N Admin Panel</strong>
            </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" style="background-color: #1e282c">
            <!-- Sidebar toggle button-->
            <a href="#" class=" hov sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                             <li class="dropdown user user-menu">
                                <div style="padding: 13px 10px 0 10px; color: #fff">
                                    {{ Auth::user()->name }}
                                </div>
                            </li>
                            <li class="dropdown user user-menu">
                                <a href="#" title="Change Password"><i class="fa fa-unlock-alt"></i></a>          
                            </li> 
                            <li class="dropdown user user-menu">
                                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" title="Logout" style="cursor:pointer;"><i class="fa fa-sign-out"></i></a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                                {{ csrf_field() }}
                                </form>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    @include('nadminpanel::backend.admin.common.sidebar')

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- <section class="content-header">
            <h1>
                @yield('title')
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Blank page</li>
            </ol>
        </section> -->

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            @yield('box')
            @include('nadminpanel::backend.admin.common.status')
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer" style="background-color:#ecf0f5;">
        <div class="pull-right hidden-xs">
           <!--  <b>Version</b> 0.0.1 -->
        </div>
        <strong>Copyright &copy; {{ date("Y") }}
         <a href="{{ url('/') }}">{{ config('app.name') }}</a>.
         </strong> All rights
        reserved.
    </footer>

</div>
<!-- ./wrapper -->
<script>
    window.Laravel = @php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); @endphp
</script>
@include('nadminpanel::backend.admin.common.script')
@yield('extra-script')
</body>
</html>
