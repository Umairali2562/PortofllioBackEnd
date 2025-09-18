<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blank Page - SB Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
    <link href="{{asset('css/mystyles.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
    @yield('css_role_page')
</head>

<body>

<div id="wrapper">

    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.blade.php">Umair's Admin Panel</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>

                @canany(['create_permissions_gate', 'view_permissions_gate','update_permissions_gate','delete_permissions_gate'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-unlock-alt"></i> Permissions <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        @can('create_permissions_gate')
                        <li><a href="/Permissions/create">Add Permissions</a></li>
                        @endcan

                        @can('view_permissions_gate')
                            <li><a href="/Permissions">View Permissions</a></li>
                            @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['create_roles_gate', 'view_roles_gate','update_roles_gate','delete_roles_gate'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-unlock-alt"></i> Roles <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        @can('create_roles_gate')
                        <li><a href="/roles/create">Add Roles</a></li>
                        @endcan

                        @canany(['view_roles_gate','update_roles_gate','delete_roles_gate'])
                        <li><a href="/roles">View Roles</a></li>
                            @endcan
                    </ul>
                </li>
                @endcanany


                @canany(['create_users_gate', 'view_users_gate','update_users_gate','delete_users_gate'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-square-o-down"></i> Users <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    @can('create_users_gate')
                        <li><a href="/users/create">Add Users</a></li>
                        @endcan

                        @canany(['view_users_gate','update_users_gate','delete_users_gate'])
                        <li><a href="/users">View Users</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany
            </ul>

            <ul class="nav navbar-nav navbar-right navbar-user">
                <li class="dropdown messages-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge">7</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">7 New Messages</li>
                        <li class="message-preview">
                            <a href="#">
                                <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                <span class="name">John Smith:</span>
                                <span class="message">Hey there, I wanted to ask you something...</span>
                                <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li class="message-preview">
                            <a href="#">
                                <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                <span class="name">John Smith:</span>
                                <span class="message">Hey there, I wanted to ask you something...</span>
                                <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li class="message-preview">
                            <a href="#">
                                <span class="avatar"><img src="http://placehold.it/50x50"></span>
                                <span class="name">John Smith:</span>
                                <span class="message">Hey there, I wanted to ask you something...</span>
                                <span class="time"><i class="fa fa-clock-o"></i> 4:34 PM</span>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#">View Inbox <span class="badge">7</span></a></li>
                    </ul>
                </li>
                <li class="dropdown alerts-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> Alerts <span class="badge">3</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Default <span class="label label-default">Default</span></a></li>
                        <li><a href="#">Primary <span class="label label-primary">Primary</span></a></li>
                        <li><a href="#">Success <span class="label label-success">Success</span></a></li>
                        <li><a href="#">Info <span class="label label-info">Info</span></a></li>
                        <li><a href="#">Warning <span class="label label-warning">Warning</span></a></li>
                        <li><a href="#">Danger <span class="label label-danger">Danger</span></a></li>
                        <li class="divider"></li>
                        <li><a href="#">View All</a></li>
                    </ul>
                </li>
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{Auth::user()?Auth::user()->name:'Username'}} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>
                        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="{{route('logout')}}"><i class="fa fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        @yield('content')

    </div>



</div><!-- /#page-wrapper -->

</div><!-- /#wrapper -->

<!-- Bootstrap core JavaScript-->
<script src="/jquery/jquery.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="/jquery-easing/jquery.easing.min.js"></script>

<script src="{{asset('js/bootstrap.js')}}"></script>
@yield('js_role_page')
@yield('js_user_page')

</body>
</html>
