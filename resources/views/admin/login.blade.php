@extends('layouts/backend/default')

@section('main-content')

	<div class="logo">
    </div><!-- end logo -->

    <div class="content">

    	<form class="login-form" action="{{ URL::to('/admin/auth/login') }}" method="post">
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
                <label class="rememberme check mt-checkbox mt-checkbox-outline">
	                <input type="checkbox" name="remember" value="1" />Remember
	                <span></span>
	            </label>
	            <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
            </div><!-- end form-actions -->
            
            <div class="login-options">
                <h4>Or login with</h4>
                
                <ul class="social-icons">
                    <li>
                        <a class="social-icon-color facebook" data-original-title="facebook" href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color twitter" data-original-title="Twitter" href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color googleplus" data-original-title="Goole Plus" href="javascript:;"></a>
                    </li>
                    <li>
                        <a class="social-icon-color linkedin" data-original-title="Linkedin" href="javascript:;"></a>
                    </li>
                </ul>
            </div><!-- end login-options -->

            <div class="create-account">
                <p>
                    <a href="javascript:;" id="register-btn" class="uppercase">Create an account</a>
                </p>
            </div><!-- end create-account -->

        </form>

    </div><!-- end content -->

    <div class="copyright"> <?php echo date('Y'); ?> © LTCCT. Admin Dashboard. </div>

@stop