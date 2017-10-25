@extends('layouts.backend.app')

@section('main-content')

@php
	$date = \Carbon\Carbon::now()->subDays(365);
	$now = \Carbon\Carbon::now();
@endphp

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

        	<div class="page-title">
              <h1>FAHUI - KONGDAN</h1>
          </div><!-- end page-title -->

        </div><!-- end container-fluid -->

      </div><!-- end page-head -->

      <div class="page-content">

        <div class="container-fluid">

          <ul class="page-breadcrumb breadcrumb">
            <li>
              <a href="/operator/index" class="hylink">Home</a>
              <i class="fa fa-circle"></i>
            </li>
            <li>
              <span>Fahui</span>
            </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                @include('layouts.partials.focus-devotee-sidebar')

                <div class="col-md-9">

                  <div class="form-horizontal form-row-seperated">

                    <div class="portlet">

                      <div class="validation-error">
                      </div><!-- end validation-error -->

                      @if($errors->any())

                      <div class="alert alert-danger">

                        @foreach($errors->all() as $error)
                          <p>{{ $error }}</p>
                        @endforeach

                      </div><!-- end alert -->

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
                              <a href="#tab_kongdan" data-toggle="tab">Kongdan <br>孔诞</a>
                            </li>

                            <li class="pull-right">
                              <a href="#tab_kongdan_transactiondetail" data-toggle="tab">Transaction <br> 交易详情</a>
                            </li>
                            <li class="pull-right">
                              <a href="#tab_relative_friends" data-toggle="tab">Relative & Friends <br> 亲戚朋友</a>
                            </li>
                            <li class="pull-right">
                              <a href="#tab_samefamily" data-toggle="tab">Same Family Code <br> 同址善信</a>
                            </li>
                          </ul>

                          <div class="tab-content">

                            <div class="tab-pane active" id="tab_kongdan">
                              @include('layouts.partials.tab-kongdan')
                            </div><!-- end tab-pane tab_kongdan -->

                            <div class="tab-pane" id="tab_samefamily">
                              @include('layouts.partials.tab-kongdan-samefamily')
                            </div><!-- end tab-pane tab_samefamily -->

                            <div class="tab-pane" id="tab_relative_friends">
															@include('layouts.partials.tab-kongdan-relative-friends')
                            </div><!-- end tab-pane tab_relative_friends -->

                            <div class="tab-pane" id="tab_kongdan_transactiondetail">
															@include('layouts.partials.tab-kongdan-transactiondetail')
                            </div><!-- end tab-pane tab_kongdan_transactiondetail -->

                          </div><!-- end tab-content -->

                        </div><!-- end tabbable-bordered -->

                      </div><!-- end portlet-body -->

                    </div><!-- end portlet -->

                  </div><!-- end form-horizontal -->

                </div><!-- end col-md-9 -->

              </div><!-- end row -->

            </div><!-- end inbox -->

          </div><!-- end page-content-inner -->

        </div><!-- end container-fluid -->

      </div><!-- end page-content -->

    </div><!-- end page-content-wrapper-->

  </div><!-- end page-container-fluid -->

@stop

@section('custom-js')

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{asset('js/custom/common.js')}}"></script>
<script src="{{asset('js/custom/kongdan-samefamily-setting.js')}}"></script>
<script src="{{asset('js/custom/search-kongdan-relative-friends.js')}}"></script>
<script src="{{asset('js/custom/kongdan.js')}}"></script>
<script src="{{asset('js/custom/kongdan-transactiondetail.js')}}"></script>

<script type="text/javascript">

	$(function() {
		$("#kongdan_trans_wrap1").hide();
		$("#kongdan_trans_wrap2").hide();
		$("#kongdan_trans_wrap3").hide();
		$("#kongdan_trans_wrap4").hide();
		$("#kongdan_trans_wrap5").hide();
		$("#kongdan_trans_wrap6").hide();
		$("#kongdan_trans_wrap7").hide();
		$("#kongdan_trans_wrap8").hide();
	});

</script>

@stop
