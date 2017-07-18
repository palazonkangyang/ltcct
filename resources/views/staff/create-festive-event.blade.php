@extends('layouts.backend.app')

@section('main-content')

    <div class="page-container-fluid">

        <div class="page-content-wrapper">

          <div class="page-head">

              <div class="container-fluid">

                  <div class="page-title">

                      <h1>Event  Calendar 庆典节目表</h1>

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
                          <span>Event  Calendar</span>
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

                                            <div class="form-body">

                                                <form method="post" action="{{ URL::to('/staff/create-festive-event') }}" class="form-horizontal form-bordered">

                                                  {!! csrf_field() !!}

                                                <div class="form-group">

                                                    <h4>Event  Calendar 庆典节目表</h4>

                                                    <table class="table table-bordered" id="festive-event-table">
                                                        <thead>
                                                            <tr>
                                                                <th width='3%'>#</th>
                                                                <th width='15%'>Date From 阴历</th>
                                                                <th width='15%'>Date To 阴历</th>
                                                                <th width='15%'>Lunar Date 阳历</th>
                                                                <th width='15%'>Event 节日</th>
                                                                <th width='15%'>Time 时间</th>
                                                                <th width='15%'>Shuwen Title 文疏</th>
                                                                <th width='8%'>Display</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                        </tbody>
                                                    </table>

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <button type="button" class="btn green" style="width: 100px; margin: 0 25px 0 10px;" id="addEventRow">Add New
                                                    </button>
                                                </div><!-- end form-group -->

                                                <hr>

                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn blue" id="confirm_event_btn">Confirm</button>
                                                    <button type="button" class="btn default">Cancel</button>
                                                </div><!-- end form-actions -->

                                                </form>

                                                <div class="clearfix">
                                                </div><!-- end clearfix -->

                                            </div><!-- end form-body -->

                                        </div><!-- end portlet-body -->

                                  </div><!-- end portlet -->

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

    <script type="text/javascript">
        $(function() {

            $("#addEventRow").click(function() {

                $("#festive-event-table").append("<tr class='event-row'><td><i class='fa fa-minus-circle removeEventRow' aria-hidden='true'></i></td>" +
                    "<td><input type='text' class='form-control' name='start_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy' value=''></td>" +
                    "<td><input type='text' class='form-control' name='end_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy' value=''></td>" +
                    "<td><input type='text' class='form-control' name='lunar_date[]' data-provide='datepicker' data-date-format='dd/mm/yyyy' value=''></td>" +
                    "<td><input type='text' class='form-control' name='event[]' value=''></td>" +
                    "<td><div class='input-group'><input type='text' class='form-control timepicker timepicker-no-seconds' name='time[]' value=''></div></td>" +
                    "<td><input type='text' class='form-control' name='shuwen_title[]' value=''></td>" +
                    "<td><select class='form-control' name='display[]'><option value='Y'>Yes</option>" +
                    "<option value='N'>No</option></select></td></tr>");
            });

            $("#confirm_event_btn").click(function() {

                var count = 0;
                var errors = new Array();
                var validationFailed = false;

                $("input:text[name^='start_at']").each(function() {

                    if (!$.trim($(this).val()).length) {

                        validationFailed = true;
                        errors[count++] = "Date From fields are empty.";
                        return false;
                    }
                });

                $("input:text[name^='end_at']").each(function() {

                    if (!$.trim($(this).val()).length) {

                        validationFailed = true;
                        errors[count++] = "Date To fields are empty.";
                        return false;
                    }
                });

                $("input:text[name^='lunar_date']").each(function() {

                    if (!$.trim($(this).val()).length) {

                        validationFailed = true;
                        errors[count++] = "Lunar Date fields are empty.";
                        return false;
                    }
                });

                $("input:text[name^='event']").each(function() {

                    if (!$.trim($(this).val()).length) {

                        validationFailed = true;
                        errors[count++] = "Event fields are empty.";
                        return false;
                    }
                });

                $("input:text[name^='time']").each(function() {

                    if (!$.trim($(this).val()).length) {

                        validationFailed = true;
                        errors[count++] = "Time fields are empty.";
                        return false;
                    }
                });

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
            });
        });
    </script>

@stop