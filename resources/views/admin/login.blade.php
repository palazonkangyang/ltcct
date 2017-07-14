@extends('layouts/backend/default')

@section('main-content')

	<div class="logo">
     <img src="{{ URL::asset('/images/logo.jpg') }}" class="img-circle" alt=""> 
    </div><!-- end logo -->

    <div class="content">

    	<form class="login-form" action="{{ URL::to('/auth/login') }}" method="post">
    		{!! csrf_field() !!}

            <h3 class="form-title font-green">Sign In</h3>
            
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span> Enter any username and password. </span>
            </div><!-- end alert alert-danger display-hide -->

           	<div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">User Name</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="User Name" name="user_name" /> 
           	</div><!-- end form-group -->
            
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 
            </div><!-- end form-group -->
            
            <div class="form-actions">
                <button type="submit" class="btn green uppercase">Login</button>
                <label style='margin-left:50px;' class="rememberme check mt-checkbox mt-checkbox-outline">
	                <input type="checkbox" name="remember" value="1" />        Remember Me
	                <span></span>
	            </label>
	           
            </div><!-- end form-actions -->
            
         

           

        </form>

    </div><!-- end content -->

    <div class="copyright"> <?php echo date('Y'); ?> © LI TECK CHUAN CIN TONG. ALL RIGHTS RESERVED </div>

@stop