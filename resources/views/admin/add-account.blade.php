@extends('admin.layouts.app')

@section('main-content')

	<div class="page-container">

        <div class="page-content-wrapper">

            <div class="page-head">

                <div class="container">

                	<div class="page-title">

                        <h1>Add New Account</h1>

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
                            <span>Add New Account</span>
                        </li>
                    </ul>

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
                                            <span class="caption-subject bold uppercase"> Add New Account</span>
                                        </div><!-- end caption font-red-sunglo -->

                                    </div><!-- end portlet-title -->


                                    <div class="portlet-body form">

                                        <form role="form" method="post" action="{{ URL::to('/admin/add-account') }}">
                                            {!! csrf_field() !!}

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label>First Name</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>

                                                        <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{ old('first_name') }}">
                                                    </div><!-- end input-group -->

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Last Name</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>

                                                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}">
                                                    </div><!-- end input-group -->

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>User Name</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>

                                                        <input type="text" class="form-control" placeholder="User Name" name="user_name"
																													value="{{ old('user_name') }}">
                                                    </div><!-- end input-group -->

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Password</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user font-red"></i>
                                                        </span>

                                                        <input type="password" class="form-control" placeholder="Password" name="password">
                                                    </div><!-- end input-group -->
                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Confirm Password</label>

                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user font-red"></i>
                                                        </span>

                                                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password">
                                                    </div><!-- end input-group -->
                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <label>Role</label>


                                                    <div class="mt-radio-inline">
																												@if(Auth::user()->role == 1)
                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="1" checked> Super Admin
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="2"> Admin
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="3"> Supervisor
                                                            <span></span>
                                                        </label>

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="4"> Account Officer
                                                            <span></span>
                                                        </label>

																												@endif

                                                        <label class="mt-radio">
                                                            <input type="radio" name="role" value="5" @if(old('role') ==  5) checked="checked" @endif> Operator
                                                            <span></span>
                                                        </label>

                                                    </div><!-- end mt-radio-inline -->

                                                </div><!-- end form-group -->

                                            </div><!-- end form-body -->

                                            <div class="form-actions">
                                                <button type="submit" class="btn blue">Create</button>
                                                <button type="button" class="btn default">Cancel</button>
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
