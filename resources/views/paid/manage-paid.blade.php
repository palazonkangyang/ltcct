@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Paid</h1>

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
                  <span>Paid</span>
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
                            <a href="#tab_paidlist" data-toggle="tab">Paid List</a>
                          </li>
                          <li>
                            <a href="#tab_newpaid" data-toggle="tab">New Paid</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_paidlist">

                          </div><!-- end tab-pane tab_paidlist -->

                          <div class="tab-pane" id="tab_newpaid">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/paid/new-paid') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Reference No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Supplier *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure Total *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Outstanding Total *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Amount *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Status *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="status">
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Type *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="type">
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div id="cash">

                                  </div>

                                  <div class="cheque">

                                  </div>

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
                                        <input type="password" class="form-control" name="authorized_password" id="authorized_password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="confirm_journalentry_btn">Confirm
                                        </button>
                                        <button type="button" class="btn default">Cancel</button>
                                      </div><!-- end form-actions -->
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                </form>

                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end form-body -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_paidlist -->

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

@section('custom-js')

<script src="{{asset('js/custom/common.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

  $(function() {

  });

</script>

@stop
