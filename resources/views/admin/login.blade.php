@extends('layouts/backend/default')

@section('main-content')

	<div class="logo">
   <!-- <img src="{{ URL::asset('/images/logo.jpg') }}" class="img-circle" alt=""> -->
  </div><!--  end logo -->

	<div class="col-md-12">
		<div class="col-md-4">
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
	                <button type="submit" class="btn green uppercase">Login 登入</button>
	                <button type="button" class="btn green uppercase pull-right">Clear</button>
	            </div><!-- end form-actions -->
	        </form>

	    </div><!-- end content -->
		</div><!-- end col-md-4 -->

		<div class="col-md-8">

			<div class="acknowledge">
				<h3 class="form-title font-green">Prelogin Notes</h3>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
					aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
					occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</div>

		</div><!-- end col-md-8 -->
	</div><!-- end col-md-12 -->

  <div class="copyright"> <?php echo date('Y'); ?> © LI TECK CHUAN CIN TONG. ALL RIGHTS RESERVED </div>

@stop
