<!DOCTYPE html>
<html>

@section('htmlheader')
    @include('admin.layouts.partials.head')
@show

<body class="page-container-bg-solid">

	<div class="page-wrapper">

		<div class="page-wrapper-row">

			<div class="page-wrapper-top">

				<div class="page-header">

					<div class="page-header-top">

						<div class="container">

							<div class="page-logo">
                                <a href="index.html">
                                    <img src="{{ URL::asset('/images/logo-default.jpg') }}" alt="logo" class="logo-default">
                                </a>
                            </div><!-- end page-logo -->

                            <!-- RESPONSIVE MENU TOGGLER -->
                            <a href="javascript:;" class="menu-toggler"></a>

                            <div class="top-menu">

                            	<ul class="nav navbar-nav pull-right">

                            		<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                                        
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        	<i class="icon-bell"></i>
                                            <span class="badge badge-default">7</span>
                                        </a>
                                        
                                        <ul class="dropdown-menu">

                                            <li class="external">
                                                <h3>You have
                                                    <strong>12 pending</strong> tasks</h3>
                                                 <a href="app_todo.html">view all</a>
                                            </li>
                                            
                                            <li>
                                                <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">just now</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-success">
                                                                    <i class="fa fa-plus"></i>
                                                                </span> New user registered. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">3 mins</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-danger">
                                                                        <i class="fa fa-bolt"></i>
                                                                </span> Server #12 overloaded. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">10 mins</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-warning">
                                                                    <i class="fa fa-bell-o"></i>
                                                                </span> Server #2 not responding. 
                                                           	</span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">14 hrs</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-info">
                                                                        <i class="fa fa-bullhorn"></i>
                                                                </span> Application error. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">2 days</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-danger">
                                                                    <i class="fa fa-bolt"></i>
                                                                </span> Database overloaded 68%. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">3 days</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-danger">
                                                                    <i class="fa fa-bolt"></i>
                                                                </span> A user IP blocked. 
                                                            </span>
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
                                                                </span> System Error. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="time">9 days</span>
                                                            <span class="details">
                                                                <span class="label label-sm label-icon label-danger">
                                                                    <i class="fa fa-bolt"></i>
                                                                </span> Storage server failed. 
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <i class="icon-calendar"></i>
                                            <span class="badge badge-default">3</span>
                                        </a>
                                        
                                        <ul class="dropdown-menu extended tasks">
                                            <li class="external">
                                                <h3>You have
                                                <strong>12 pending</strong> tasks</h3>
                                                
                                                <a href="app_todo_2.html">view all</a>
                                            </li>
                                            
                                            <li>
                                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">New release v1.2 </span>
                                                                <span class="percent">30%</span>
                                                            </span>
                                                            <span class="progress">
                                                                <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                                	<span class="sr-only">40% Complete</span>
                                                            	</span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">Application deployment</span>
                                                                <span class="percent">65%</span>
                                                            </span>
                                                            <span class="progress">
                                                                <span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">65% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">Mobile app release</span>
                                                                <span class="percent">98%</span>
                                                           	</span>
                                                            <span class="progress">
                                                                <span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">98% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">Database migration</span>
                                                                <span class="percent">10%</span>
                                                            </span>
                                                            
                                                            <span class="progress">
                                                                <span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">10% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">Web server upgrade</span>
                                                                <span class="percent">58%</span>
                                                            </span>
                                                            <span class="progress">
                                                                <span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">58% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">Mobile development</span>
                                                                <span class="percent">85%</span>
                                                            </span>
                                                            
                                                            <span class="progress">
                                                               	<span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">85% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="javascript:;">
                                                            <span class="task">
                                                                <span class="desc">New UI release</span>
                                                                <span class="percent">38%</span>
                                                            </span>
                                                            
                                                            <span class="progress progress-striped">
                                                                <span style="width: 38%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="sr-only">38% Complete</span>
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>

                                            </li>

                                        </ul>
                                    
                                    </li>

                                    <li class="droddown dropdown-separator">
                                        <span class="separator"></span>
                                    </li>
                                    
                                    <!-- BEGIN INBOX DROPDOWN -->
                                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <span class="circle">3</span>
                                            <span class="corner"></span>
                                        </a>
                                        
                                        <ul class="dropdown-menu">
                                            <li class="external">
                                                <h3>You have
                                                <strong>7 New</strong> Messages</h3>

                                                <a href="app_inbox.html">view all</a>
                                            </li>

                                            <li>
                                                
                                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                                    <li>
                                                        <a href="#">
                                                            <span class="photo">
                                                                <img src="{{ URL::asset('/images/avatar2.jpg') }}" class="img-circle" alt=""> 
                                                           	</span>
                                                            
                                                            <span class="subject">
                                                                <span class="from"> Lisa Wong </span>
                                                                <span class="time">Just Now </span>
                                                            </span>
                                                            <span class="message">
                                                            	Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="#">
                                                            <span class="photo">

                                                                <img src="{{ URL::asset('/images/avatar3.jpg') }}" class="img-circle" alt=""> 
                                                            </span>

                                                            <span class="subject">
                                                                <span class="from"> Richard Doe </span>
                                                                <span class="time">16 mins </span>
                                                            </span>
                                                            
                                                            <span class="message"> 
                                                            	Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... 
                                                           	</span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="#">
                                                            <span class="photo">
                                                                <img src="{{ URL::asset('/images/avatar1.jpg') }}" class="img-circle" alt=""> 
                                                            </span>

                                                            <span class="subject">
                                                                <span class="from"> Bob Nilson </span>
                                                                <span class="time">2 hrs </span>
                                                            </span>
                                                            
                                                            <span class="message"> 
                                                            	Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... 
                                                            </span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="#">
                                                            <span class="photo">
                                                                
                                                                <img src="{{ URL::asset('/images/avatar2.jpg') }}" class="img-circle" alt=""> 
                                                            </span>
                                                            
                                                            <span class="subject">
                                                                <span class="from"> Lisa Wong </span>
                                                                <span class="time">40 mins </span>
                                                            </span>

                                                            <span class="message"> 
                                                            	Vivamus sed auctor 40% nibh congue nibh... 
                                                           	</span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li>
                                                       <a href="#">
                                                            <span class="photo">
                                                                <img src="{{ URL::asset('/images/avatar3.jpg') }}" class="img-circle" alt=""> 
                                                            </span>
                                                            
                                                            <span class="subject">
                                                                <span class="from"> Richard Doe </span>
                                                                <span class="time">46 mins </span>
                                                            </span>
                                                            
                                                            <span class="message"> 
                                                            	Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>

                                    <li class="dropdown dropdown-user dropdown-dark">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <img alt="" class="img-circle" src="{{ URL::asset('/images/avatar9.jpg') }}">
                                            <span class="username username-hide-mobile">Nick</span>
                                        </a>
                                        
                                        <ul class="dropdown-menu dropdown-menu-default">
                                            <li>
                                                <a href="page_user_profile_1.html">
                                                    <i class="icon-user"></i> My Profile </a>
                                            </li>
                                            
                                            <li>
                                                <a href="app_calendar.html">
                                                    <i class="icon-calendar"></i> My Calendar </a>
                                            </li>
                                            
                                            <li>
                                                <a href="app_inbox.html">
                                                    <i class="icon-envelope-open"></i> My Inbox
                                                    <span class="badge badge-danger"> 3 </span>
                                                </a>
                                            </li>
                                                
                                            <li>
                                                <a href="app_todo_2.html">
                                                    <i class="icon-rocket"></i> My Tasks
                                                    <span class="badge badge-success"> 7 </span>
                                                </a>
                                            </li>
                                                
                                            <li class="divider"> </li>
                                            
                                            <li>
                                                <a href="page_user_lock_1.html">
                                                    <i class="icon-lock"></i> Lock Screen </a>
                                            </li>
                                            
                                            <li>
                                                <a href="{{ URL::to('/admin/auth/logout') }}">
                                                    <i class="icon-key"></i> Log Out </a>
                                            </li>
                                        </ul>
                                   </li>                                   

                            	</ul><!-- end nav navbar-nav pull-right -->

                            </div><!-- end top-menu -->

						</div><!-- end container -->

					</div><!-- end page-header-top -->

					<div class="page-header-menu">

						<div class="container">

							<form class="search-form" action="page_general_search.html" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="query">
                                    
                                    <span class="input-group-btn">
                                        <a href="javascript:;" class="btn submit">
                                            <i class="icon-magnifier"></i>
                                        </a>
                                    </span>
                                </div>
                            </form>

                            <div class="hor-menu">
                            	<ul class="nav navbar-nav">

                            		<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">
                                        <a href="javascript:;"> Dashboard
                                            <span class="arrow"></span>
                                        </a>
                                        
                                        <ul class="dropdown-menu pull-left">
                                            <li aria-haspopup="true" class="active">
                                                <a href="index.html" class="nav-link  active">
                                                    <i class="icon-bar-chart"></i> Default Dashboard
                                                    <span class="badge badge-success">1</span>
                                                </a>
                                            </li>
                                            
                                            <li aria-haspopup="true" class=" ">
                                                <a href="dashboard_2.html" class="nav-link">
                                                    <i class="icon-bulb"></i> Dashboard 2</a>
                                            </li>
                                            
                                            <li aria-haspopup="true" class=" ">
                                                <a href="dashboard_3.html" class="nav-link">
                                                    <i class="icon-graph"></i> Dashboard 3
                                                    <span class="badge badge-danger">3</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                                        <a href="javascript:;"> Manage Account
                                            <span class="arrow"></span>
                                        </a>
                                        
                                        <ul class="dropdown-menu pull-left">
                                            <li>
                                                <a href="{{ URL::to('/admin/all-accounts') }}">All Accounts</a>
                                            </li>

                                            <li>
                                                <a href="{{ URL::to('/admin/add-account') }}">Add New Account</a>
                                            </li>
                                        </ul>
                                    </li>

                                </ul><!-- end nav navbar-nav -->
                            </div><!-- end hor-menu -->

						</div><!-- end container -->

					</div><!-- end page-header-menu -->

				</div><!-- end page-header -->

			</div><!-- end page-wrapper-top -->

		</div><!-- end page-wrapper-row -->

		<div class="page-wrapper-row full-height">

            <div class="page-wrapper-middle">

            	@yield('main-content')

            </div><!-- end page-wrapper-middle -->

        </div><!-- end page-wrapper-row full-height -->

		<div class="page-wrapper-row">
            
            <div class="page-wrapper-bottom">
                    
                @include('admin.layouts.partials.footer')
                    
            </div><!-- end page-wrapper-bottom -->

        </div><!-- end page-wrapper-row -->

	</div><!-- end page-wrapper -->

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