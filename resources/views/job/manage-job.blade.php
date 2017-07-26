@extends('layouts.backend.app')

@section('main-content')

<div class="page-container-fluid">

  <div class="page-content-wrapper">

    <div class="page-head">

        <div class="container-fluid">

            <div class="page-title">

                <h1>GL Account</h1>

            </div><!-- end page-title -->

        </div><!-- end container-fluid -->

    </div><!-- end page-head -->

    <div class="page-content">

      <div class="container-fluid">

        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/operator/index">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Job</span>
            </li>
        </ul>

        <div class="page-content-inner">

          <div class="inbox">

            <div class="row">

              <div class="col-md-12">

                <div class="portlet light">

                  <div class="validation-error">
                  </div><!-- end validation-error -->

                  @if($errors->any())

                      <div class="alert alert-danger">

                          @foreach($errors->all() as $error)
                              <p>{{ $error }}</p>
                          @endforeach

                      </div><!-- end alert alert-danger -->

                  @endif

                  @if(Session::has('success'))
                      <div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
                  @endif

                  @if(Session::has('error'))
                      <div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
                  @endif

                  <div class="portlet-body">

                    <div class="tabbable-bordered">
                        <ul class="nav nav-tabs">

                          <li class="active">
                            <a href="#tab_joblist" data-toggle="tab">Job List</a>
                          </li>
                          <li>
                            <a href="#tab_newjob" data-toggle="tab">New Job</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_joblist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="joblist-table">
                                  <thead>
                                      <tr>
                                          <th>Account Group Code</th>
                                          <th>Account Group Description</th>
                                          <th>Balancing Side</th>
                                          <th>Account Group Status</th>
                                      </tr>
                                  </thead>

                                  <tbody>

                                  </tbody>
                                </table>
                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab-glaccount-group-list -->

                          <div class="tab-pane" id="tab_newjob">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/job/new-job') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Reference No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="job_reference_no" value="" id="name">
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Name *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="job_name" value="" id="job_name">
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Description *</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="job_description" rows="4" id="job_description"></textarea>
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  

                                  <div class="form-group">
                                    <p>&nbsp;</p>
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <div class="col-md-6">
                                      <p>
                                        If you have made Changes to the above. You need to CONFIRM to save the Changes.
                                        To Confirm, please enter authorized password to proceed.
                                      </p>
                                    </div><!-- end col-md-6 -->

                                    <div class="col-md-6">
                                      <label class="col-md-6">Authorized Password</label>
                                      <div class="col-md-6">
                                        <input type="password" class="form-control" name="authorized_password" value="" id="authorized_password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="confirm_gl_btn">Confirm
                                        </button>
                                        <button type="button" class="btn default">Cancel</button>
                                      </div><!-- end form-actions -->
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                </form>

                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end form-group -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_newglaccount -->

                          <div class="tab-pane" id="tab_editglaccountgroup">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/account/update-glaccountgroup') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <input type="hidden" name="glcodegroup_id" value="" id="edit_glcodegroup_id">
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Account Group Name *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" value="" id="edit_name">
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Account Description *</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="description" rows="4" id="edit_description"></textarea>
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Balancing Side *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="balancesheet_side" id="edit_balancesheet_side" disabled>
                                          <option value="ap">AP</option>
                                          <option value="ar">AR</option>
                                      </select>
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Group Status *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="status" id="edit_status" disabled>
                                          <option value="active">Active</option>
                                          <option value="inactive">Inactive</option>
                                      </select>
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <p>&nbsp;</p>
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <div class="col-md-6">
                                      <p>
                                        If you have made Changes to the above. You need to CONFIRM to save the Changes.
                                        To Confirm, please enter authorized password to proceed.
                                      </p>
                                    </div><!-- end col-md-6 -->

                                    <div class="col-md-6">
                                      <label class="col-md-6">Authorized Password</label>
                                      <div class="col-md-6">
                                        <input type="password" class="form-control" name="authorized_password" value="" id="edit_authorized_password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="update_gl_btn">Update
                                        </button>
                                        <button type="button" class="btn default">Cancel</button>
                                      </div><!-- end form-actions -->
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                </form>

                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end form-group -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_newglaccountgroup -->

                        </div><!-- end tab-content -->
                    </div><!-- end tabbable-bordered -->

                  </div><!-- end portlet-body -->

                </div><!-- end portlet light -->

              </div><!-- end col-md-12 -->

            </div><!-- end row -->

          </div><!-- end inbox -->

        </div><!-- end page-content-inner -->

      </div><!-- end container-fluid -->

    </div><!-- end page-content -->

  </div><!-- end page-content-wrapper -->

</div><!-- end page-container-fluid -->

@stop
