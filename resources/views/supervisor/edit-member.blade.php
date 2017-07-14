@extends('layouts.backend.app')

@section('main-content')

	<div class="page-container-fluid">
                        
        <div class="page-content-wrapper">
            
            <div class="page-head">
                
                <div class="container-fluid">

                	<div class="page-title">

                        <h1>Edit Member</h1>

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
                            <span>Edit Member</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                        <div class="inbox">

                            <div class="row">

                                <div class="col-md-2">

                                    <div class="inbox-sidebar">
                                        <a href="javascript:;" data-title="Compose" class="btn red compose-btn btn-block">
                                            <i class="fa fa-edit"></i> Compose 
                                        </a>
                                                        
                                        <ul class="inbox-nav">
                                            <li class="active">
                                                <a href="javascript:;" data-type="inbox" data-title="Inbox"> Inbox
                                                    <span class="badge badge-success">3</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-type="important" data-title="Inbox"> Important </a>
                                            </li>
                                        </ul>
                                        
                                        <ul class="inbox-contacts">
                                            <li class="divider margin-bottom-30"></li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img class="contact-pic" src="../assets/pages/media/users/avatar4.jpg">
                                                    <span class="contact-name">Adam Stone</span>
                                                    <span class="contact-status bg-green"></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img class="contact-pic" src="../assets/pages/media/users/avatar2.jpg">
                                                    <span class="contact-name">Lisa Wong</span>
                                                    <span class="contact-status bg-red"></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img class="contact-pic" src="../assets/pages/media/users/avatar5.jpg">
                                                    <span class="contact-name">Nick Strong</span>
                                                    <span class="contact-status bg-green"></span>
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="javascript:;">
                                                    <img class="contact-pic" src="../assets/pages/media/users/avatar6.jpg">
                                                    <span class="contact-name">Anna Bold</span>
                                                    <span class="contact-status bg-yellow"></span>
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="javascript:;">
                                                    <img class="contact-pic" src="../assets/pages/media/users/avatar7.jpg">
                                                    <span class="contact-name">Richard Nilson</span>
                                                    <span class="contact-status bg-green"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- end col-md-2 -->

                                <div class="col-md-10">

	                                <div class="inbox-body">

	                                    <div class="inbox-header">
	                                        <h1 class="pull-left">Edit Member</h1>
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

	                                    		<form role="form" method="post" 
                                                    action="{{ URL::to('/supervisor/member/edit/' . $member->member_id) }}">
                                            		{!! csrf_field() !!}

	                                    		<div class="form-body">

                                                	<div class="form-group">
	                                                    <label>Introduced By 1</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Introduced By 1" 
	                                                        	name="introduced_by1" value="{{ $member->introduced_by1 }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Introduced By 2</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Introduced By 2" 
	                                                        	name="introduced_by2" value="{{ $member->introduced_by2 }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Approved Date</label>

	                                                    <div class="input-group">
	                                                        <span class="input-group-addon">
	                                                            <i class="fa fa-envelope"></i>
	                                                        </span>

	                                                        <input type="text" class="form-control" placeholder="Approved Date" 
	                                                        	id="approved_date" name="approved_date" value="{{ $member->approved_date }}">
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
	                                                        	value="{{ $member->cancelled_date }}">
	                                                    </div><!-- end input-group -->

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                    <label>Reason for Cancel</label>

	                                                    <select class="form-control" name="reason_for_cancel">
	                                                    	<option value="0">Please select</option>
                                                            <option value="1" <?php if ($member->reason_for_cancel == 1) echo "selected"; ?>>Deceased</option>
                                                            <option value="2" <?php if ($member->reason_for_cancel == 2) echo "selected"; ?>>Self withdrawal</option>
                                                            <option value="3" <?php if ($member->reason_for_cancel == 3) echo "selected"; ?>>Had been inactive for years</option>
                                                            <option value="4" <?php if ($member->reason_for_cancel == 4) echo "selected"; ?>>Others</option>
                                                        </select>	                                                    

	                                                </div><!-- end form-group -->

	                                                <div class="form-group">
	                                                	<p>&nbsp;</p>
	                                                </div><!-- end form-group -->

                                                </div><!-- end form-body -->

                                            	<div class="form-actions">
	                                                <button type="submit" class="btn blue">Update</button>
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

                                </div><!-- end col-md-10 -->

                            </div><!-- end row -->

                        </div><!-- end box -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container-fluid -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop