@extends('admin.layouts.app')

@section('main-content')

	<div class="page-container">

        <div class="page-content-wrapper">

            <div class="page-head">

                <div class="container">

                	<div class="page-title">

                        <h1>Edit Account</h1>

                    </div><!-- end page-title -->

                </div><!-- end container -->

            </div><!-- end page-head -->

            <div class="page-content">

                <div class="container">

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/operator/index">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Edit Account</span>
                        </li>
                    </ul>

										<div class="validation-error">
										</div><!-- end validation-error -->

                    @if($errors->any())

                        <div class="alert alert-danger">

                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach

                        </div>

                    @endif

                    @if(Session::has('success'))
                        <div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
                    @endif

                    @if(Session::has('error'))
                        <div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
                    @endif

                    <div class="page-content-inner">

                        <div class="row">

                            <div class="col-md-12">

                                <div class="portlet light">

                                    <div class="portlet-title">

                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Edit Account</span>
                                        </div><!-- end caption font-red-sunglo -->

                                    </div><!-- end portlet-title -->


                                    <div class="portlet-body form">

                                        <form method="post" action="{{ URL::to('/admin/change-account') }}">
                                            {!! csrf_field() !!}

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="staff_id"
                                                        value="{{ old( 'staff_id', $staff->id) }}">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>First Name</label>

                                                    <input type="text" class="form-control" name="first_name"
                                                        value="{{ old( 'first_name', $staff->first_name) }}">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Last Name</label>

                                                    <input type="text" class="form-control" name="last_name"
                                                        value="{{ old( 'last_name', $staff->last_name) }}">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>User Name</label>

                                                    <input type="text" class="form-control" name="user_name" id="user_name"
                                                            value="{{ old( 'user_name', $staff->user_name) }}">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Password</label>

                                                    <input type="password" class="form-control" name="password" id="password">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Confirm Password</label>

                                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password">

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Role</label>

                                                    <div class="mt-radio-inline">

																												@if(Auth::user()->role == 1)

																												<label class="mt-radio">
                                                            <input type="radio" name="role" value="1" <?php if ($staff->role == 1){ ?>checked="checked"<?php }?>> Super Admin
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="2" <?php if ($staff->role == 2){ ?>checked="checked"<?php }?>> Admin
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="3" <?php if ($staff->role == 3){ ?>checked="checked"<?php }?>> Supervisor
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="4" <?php if ($staff->role == 4){ ?>checked="checked"<?php }?>> Account Officer
                                                            <span></span>
                                                        </label>

																												<label class="mt-radio">
                                                            <input type="radio" name="role" value="6" <?php if ($staff->role == 6){ ?>checked="checked"<?php }?>> Account Supervisor
                                                            <span></span>
                                                        </label>

																												@endif

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="5" <?php if ($staff->role == 5){ ?>checked="checked"<?php }?>> Operator
                                                            <span></span>
                                                        </label>

                                                    </div><!-- end mt-radio-inline -->

                                                </div><!-- end form-group -->

                                            </div><!-- end form-body -->

                                            <div class="form-actions">
                                              <button type="submit" class="btn blue" id="update-account-btn">Update</button>
                                              <a href="/admin/all-accounts" class="btn default">Cancel</a>
                                            </div><!-- end form-actions -->

                                        </form>

                                    </div><!-- end portlet-body form -->

                                </div><!-- end portlet light -->



                            </div><!-- end col-md-6 -->

                        </div><!-- end row -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container -->

@stop

@section('script-js')

<script type="text/javascript">
	$(function() {

		$("#update-account-btn").click(function() {

			var count = 0;
			var errors = new Array();
			var validationFailed = false;

			var user_name = $("#user_name").val();
			var password = $("#password").val();
			var confirm_password = $("#confirm_password").val();

			if ($.trim(user_name).length <= 0)
			{
					validationFailed = true;
					errors[count++] = "User Name should not be empty.";
			}

			if ($.trim(password).length > 0)
      {
				if ($.trim(confirm_password).length <= 0)
				{
						validationFailed = true;
						errors[count++] = "Confirm Password should not be empty.";
				}
      }

			if (validationFailed)
			{
					var errorMsgs = '';

					for(var i = 0; i < count; i++)
					{
							errorMsgs = errorMsgs + errors[i] + "<br/>";
					}

					$('html,body').animate({ scrollTop: 0 }, 'slow');

					$(".validation-error").addClass("bg-danger alert alert-error")
					$(".validation-error").html(errorMsgs);

					return false;
			}

			else
			{
					$(".validation-error").removeClass("bg-danger alert alert-error")
					$(".validation-error").empty();
			}
		});

	});
</script>

@stop
