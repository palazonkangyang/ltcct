@extends('layouts.backend.app')

@section('main-content')

	<div class="page-container-fluid">
                        
        <div class="page-content-wrapper">
            
            <div class="page-head">
                
                <div class="container-fluid">

                	<div class="page-title">

                        <h1>Edit Account</h1>

                    </div><!-- end page-title -->

                </div><!-- end container-fluid -->

            </div><!-- end page-head -->

            <div class="page-content">

                <div class="container-fluid">

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Devotee</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                        <div class="inbox">

                            <div class="row">

                                @include('layouts.partials.sidebar')

                                <div class="col-md-9">

	                                <div class="inbox-body">

	                                    <div class="inbox-header">
	                                        <h1 class="pull-left">Add Member</h1>
	                                    </div>

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

	                                    <div class="inbox-content" style="overflow: hidden;">

	                                    	<div class="col-md-4">

	                                    		<form role="form" method="post" action="{{ URL::to('/supervisor/add-member') }}">
                                            		{!! csrf_field() !!}

	                                    		<div class="form-body">

                                                	<div class="form-group">
	                                                    <label>Introduced By 1</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Introduced By 1" 
	                                                        	name="introduced_by1" value="{{ old('introduced_by1') }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Introduced By 2</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Introduced By 2" 
	                                                        	name="introduced_by2" value="{{ old('introduced_by2') }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Approved Date</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Approved Date" 
	                                                        	id="approved_date" name="approved_date" value="{{ old('approved_date') }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Cancelled Date</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Cancelled Date" 
	                                                        	id="cancelled_date" name="cancelled_date" 
	                                                        	value="{{ old('cancelled_date') }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Reason for Cancel</label>

	                                                    <select class="form-control" name="reason_for_cancel">
	                                                    	<option value="0">Please select</option>
                                                            <option value="1">Deceased</option>
                                                            <option value="2">Self withdrawal</option>
                                                            <option value="3">Had been inactive for years</option>
                                                            <option value="4">Others</option>
                                                        </select>	                                                    

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                	<p>&nbsp;</p>
	                                                </div><!-- end form-group -->

                                                </div><!-- end form-body -->

                                            	<div class="form-actions">
	                                                <button type="submit" class="btn blue">Create</button>
	                                                <button type="button" class="btn default">Cancel</button>
	                                        	</div><!-- end form-actions -->

	                                        	</form>

	                                        	<div class="clearfix"></div>

	                                        </div><!-- end col-md-4 -->

	                                        <div class="col-md-4">

	                                        </div><!-- end col-md-4 -->

	                                        <div class="col-md-4">

	                                        </div><!-- end col-md-4 -->

	                                    </div><!-- end inbox-content -->
	                                
	                                </div><!-- end inbox-body -->                                    

                                </div><!-- end col-md-9 -->

                            </div><!-- end row -->

                        </div><!-- end box -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container-fluid -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop