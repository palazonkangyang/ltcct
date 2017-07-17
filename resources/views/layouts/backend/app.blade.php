<!DOCTYPE html>
<html>

@section('htmlheader')
    @include('layouts.partials.header')
@show

<body class="page-container-bg-solid">

    <div class="page-wrapper">

        <div class="page-wrapper-row">

            <div class="page-wrapper-top">

                <div class="page-header">

                    <div class="page-header-top">

                        <div class="container">

                           <!-- <div class="page-logo">
                                <a href="/operator/index">
                                    <img src="{{ URL::asset('/images/logo-small.jpg') }}" alt="logo" class="logo-default">
                                </a>
                            </div> end page-logo -->

                            <!-- RESPONSIVE MENU TOGGLER -->
                            <a href="javascript:;" class="menu-toggler"></a>

                            <div class="top-menu">

                                <ul class="nav navbar-nav pull-right">


                                    
                                    
                                    <!-- BEGIN INBOX DROPDOWN -->
                                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">
                                      
                                        
                                        <ul

                                            <li>
                                                
                                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                                   
                                                 
                                                    
                                                    
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>

                                    <li class="dropdown dropdown-user dropdown-dark">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <img alt="" class="img-circle" src="{{ URL::asset('/images/avatar9.jpg') }}">
                                            <span class="username username-hide-mobile">{{ Auth::user()->user_name }}</span>
                                        </a>
                                        
                                        <ul class="dropdown-menu dropdown-menu-default">
                                           
                                            
                                            <li>
                                                <a href="{{ URL::to('/auth/logout') }}">
                                                    <i class="icon-key"></i> Log Out 登出 </a>
                                            </li>
                                        </ul>
                                   </li>                                   

                                </ul><!-- end nav navbar-nav pull-right -->

                            </div><!-- end top-menu -->

                        </div><!-- end container -->

                    </div><!-- end page-header-top -->

                    <div class="page-header-menu">

						<div class="container-fluid">

							

                            <div class="hor-menu">
                            	<ul class="nav navbar-nav">

                            		<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">
                                        <a href="/operator/index"> Main Page 主页
                                            <span class="arrow"></span>
                                        </a>
                                        
                                       
                                    </li>

                                    <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                                        <a href="/staff/donation"> General Donation 乐捐
                                            <span class="arrow"></span>
                                        </a>
                                        
                                        <!-- <ul class="dropdown-menu pull-left">
                                            <li>
                                                <a href="#">All Account</a>
                                            </li>

                                            <li>
                                                <a href="#">Add New Account</a>
                                            </li>
                                        </ul> -->
                                    </li>

                                </ul><!-- end nav navbar-nav -->
                            </div><!-- end hor-menu -->

						</div><!-- end container-fluid -->

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
                    
                @include('layouts.partials.footer')
                    
            </div><!-- end page-wrapper-bottom -->

        </div><!-- end page-wrapper-row -->

	</div><!-- end page-wrapper -->

	@section('scripts')
    
        @include('layouts.partials.scripts')

    @show

    <!-- <script>
        $(document).ready(function()
        {
            $('#clickmewow').click(function() {
                
                $('#radio1003').attr('checked', 'checked');
            });
        })
    </script> -->

    @yield('custom-js')

	
</body>
</html>