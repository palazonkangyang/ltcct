<!DOCTYPE html>
<html>

@section('htmlheader')
    @include('admin.layouts.partials.head')
@show

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

    <div class="page-header navbar navbar-fixed-top">

        <div class="page-header-inner">

            <div class="page-logo">

                <a href="index.html">
                    LTCCT
                </a>

                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div><!-- end menu-toggler sidebar-toggler -->

            </div><!-- end page-logo -->

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                <span></span>
            </a>

            <div class="top-menu">

                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
                            <span class="badge badge-default"> 7 </span>
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3>
                                    <span class="bold">12 pending</span> notifications</h3>
                                <a href="page_user_profile_1.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">just now</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-success">
                                            <i class="fa fa-plus"></i>
                                            </span> New user registered. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">3 mins</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-danger">
                                                <i class="fa fa-bolt"></i>
                                            </span> Server #12 overloaded. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">10 mins</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-warning">
                                                <i class="fa fa-bell-o"></i>
                                            </span> Server #2 not responding. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">14 hrs</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-info">
                                                <i class="fa fa-bullhorn"></i>
                                            </span> Application error. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">2 days</span>
                                            <span class="details">
                                                <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                            </span> Database overloaded 68%. </span>
                                         </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">3 days</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-danger">
                                                    <i class="fa fa-bolt"></i>
                                            </span> A user IP blocked. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">4 days</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-warning">
                                                <i class="fa fa-bell-o"></i>
                                            </span> Storage Server #4 not responding dfdfdfd. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">5 days</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-info">
                                                <i class="fa fa-bullhorn"></i>
                                            </span> System Error. </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span class="time">9 days</span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-danger">
                                                <i class="fa fa-bolt"></i>
                                            </span> Storage server failed. </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- end top-menu -->

        </div><!-- end page-header-inner -->

    </div><!-- end page-header -->

    <div class="clearfix"> </div>

    <div class="page-container">

        <div class="page-sidebar-wrapper">

            <div class="page-sidebar navbar-collapse collapse">

                <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">

                    <li class="sidebar-toggler-wrapper hide">
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                    </li>

                    <li class="sidebar-search-wrapper">
                                
                        <form class="sidebar-search  sidebar-search-bordered" action="page_general_search_3.html" method="POST">
                            <a href="javascript:;" class="remove">
                                <i class="icon-close"></i>
                            </a>

                            <div class="input-group">
                                
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <a href="javascript:;" class="btn submit">
                                        <i class="icon-magnifier"></i>
                                    </a>
                                </span>
                            </div>
                        </form>
                    </li>

                    <li class="nav-item start ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-home"></i>
                            <span class="title">Dashboard</span>
                            <span class="arrow"></span>
                        </a>
                        
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="index.html" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Dashboard 1</span>
                                </a>
                            </li>
                            
                            <li class="nav-item start ">
                                <a href="dashboard_2.html" class="nav-link ">
                                    <i class="icon-bulb"></i>
                                    <span class="title">Dashboard 2</span>
                                    <span class="badge badge-success">1</span>
                                </a>
                            </li>
                            
                            <li class="nav-item start ">
                                <a href="dashboard_3.html" class="nav-link ">
                                    <i class="icon-graph"></i>
                                    <span class="title">Dashboard 3</span>
                                    <span class="badge badge-danger">5</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="heading">
                        <h3 class="uppercase">Account</h3>
                    </li>

                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-user"></i>
                            <span class="title">Manage Account</span>
                            <span class="arrow"></span>
                        </a>
                        
                        <ul class="sub-menu">
                            <li class="nav-item">
                                <a href="page_user_profile_1.html" class="nav-link ">
                                    <i class="icon-user"></i>
                                    <span class="title">Add New User</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="page_user_profile_1_account.html" class="nav-link ">
                                    <i class="icon-users"></i>
                                    <span class="title">All Users</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </div><!-- end page-sidebar navbar-collapse collapse -->

        </div><!-- end page-sidebar-wrapper -->

        <div class="page-content-wrapper">

            <div class="page-content">

                @yield('main-content')
                
            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container -->

    @section('scripts')
    
        @include('admin.layouts.partials.scripts')

    @show

    <script>
        $(document).ready(function()
        {
            $('#clickmewow').click(function() {
                
                $('#radio1003').attr('checked', 'checked');
            });
        })
    </script>

</body>
</html>