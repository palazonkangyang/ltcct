@extends('layouts/backend/default')

@section('main-content')

	<div class="logo">
   <!-- <img src="{{ URL::asset('/images/logo.jpg') }}" class="img-circle" alt=""> -->
  </div><!--  end logo -->

	<div class="col-md-12">
		<div class="validation-error">
		</div><!-- end validation-error -->

		@if(Session::has('success'))
				<div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
		@endif

		@if(Session::has('error'))
				<div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
		@endif
	</div><!-- end col-md-12 -->

	<div class="col-md-12">

		<div class="col-md-4">

			<div class="col-md-12">
				<div class="version">
					<h5>Version : TMS 2017 - v1.62</h5>
				</div>
			</div>

			<div class="col-md-12">
				<div class="content">

		    	<form class="login-form" action="{{ URL::to('/auth/login') }}" method="post">
		    		{!! csrf_field() !!}

		            <h3 class="form-title font-green">Sign In</h3>

		            <div class="alert alert-danger display-hide">
		              <button class="close" data-close="alert"></button>
		                <span> Enter any username and password.</span>
		            </div><!-- end alert alert-danger display-hide -->

		           	<div class="form-group">
		            	<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		              <label class="control-label visible-ie8 visible-ie9">User ID</label>
		              <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
											placeholder="User ID" name="user_name" id="user_name" />
		           	</div><!-- end form-group -->

		            <div class="form-group">
		                <label class="control-label visible-ie8 visible-ie9">Password</label>
		                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
											placeholder="Password" name="password" id="password" />
		            </div><!-- end form-group -->

		            <div class="form-actions">
		                <button type="submit" class="btn green uppercase" id="login_btn">Login 登入</button>
		                <button type="reset" class="btn green uppercase pull-right">Clear</button>
		            </div><!-- end form-actions -->
		        </form>

		    </div><!-- end content -->
			</div>

		</div><!-- end col-md-4 -->

		<div class="col-md-8">

		  <div class="acknowledge">

		    <h3 class="form-title font-green">Prelogin Notes</h3>

		    @if($acknowledge[0]->show_prelogin != 0)

		    <div class="form-body">

		      <div class="form-group">

		        <p>{!! $acknowledge[0]->prelogin_notes !!}</p>

		      </div><!-- end form-group -->

		      <div class="form-group">

		        <div class="mt-checkbox-list">
		          <label class="mt-checkbox">
		            <input value="1" name="" type="checkbox" id="terms"> Read & Acknowledge
		            <span></span>
		          </label>
		        </div><!-- end mt-checkbox-list -->

		      </div><!-- end form-group -->

		    </div><!-- end form-body -->


		    @endif
		  </div><!-- end acknowledge -->

		</div><!-- end col-md-8 -->

	</div><!-- end col-md-12 -->

  <div class="copyright"> <?php echo date('Y'); ?> © LI TECK CHUAN CIN TONG. ALL RIGHTS RESERVED </div>

@stop
