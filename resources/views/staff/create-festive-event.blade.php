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
                          <a href="/operator/index" class="hylink">Home</a>
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
                                                                <th width='4%'>#</th>
                                                                <th width="16%">Job</th>
                                                                <th width='10%'>Job Date 阴历</th>
                                                                <th width='10%'>Date To 阴历</th>
                                                                <th width='15%'>Lunar Date 阳历</th>
                                                                <th width='18%'>Event 节日</th>
                                                                <th width='11%'>Time 时间</th>
                                                                <th width='11%'>Shuwen Title 文疏</th>
                                                                <th width='5%'>Display</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @if(count($events))

                                                                @foreach($events as $event)
                                                                <tr class="event-row">
                                                                    <td><i class='fa fa-minus-circle removeEventRow' aria-hidden='true'></i></td>
                                                                    <td>
                                                                      <select class="form-control" name="job_id[]" id="job_id">
                                                                        <option value="">Please Select</option>
                                                                        @foreach($jobs as $job)
                                                                        <option value="{{ $job->job_id }}" <?php if ($job->job_id == $event->job_id) echo "selected"; ?>>{{ $job->job_name }}</option>
                                                                        @endforeach
                                                                      </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control' name='start_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy'
                                                                          value='{{ \Carbon\Carbon::parse($event->start_at)->format("d/m/Y") }}' id="job_date">
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control' name='end_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy'
                                                                        value='{{ \Carbon\Carbon::parse($event->end_at)->format("d/m/Y") }}' id="end_at">
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control' name='lunar_date[]' value='{{ $event->lunar_date }}'>
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control' name='event[]' value='{{ $event->event }}'>
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control timepicker timepicker-no-seconds' data-provide='timepicker' name='time[]'
                                                                          value='{{ $event->time }}'>
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class='form-control' name='shuwen_title[]' value='{{ $event->shuwen_title }}'>
                                                                    </td>
                                                                    <td class="display-row">
                                                                        <input type='hidden' name='display_hidden[]' value='' class="display-hidden">
                                                                        <input type='checkbox' name='display[]' value='' class='no-height'
                                                                          <?php if ($event->display == '1'){ ?>checked="checked"<?php }?>>
                                                                    </td>
                                                                </tr>
                                                                @endforeach

                                                            @endif
                                                        </tbody>
                                                    </table>

                                                </div><!-- end form-group -->

                                                <div class="form-group">
                                                    <button type="button" class="btn green" style="width: 100px; margin: 0 25px 0 10px;" id="addEventRow">Add New
                                                    </button>
                                                </div><!-- end form-group -->

                                                <hr>

                                                <div class="form-actions pull-right">
                                                    <button type="submit" class="btn blue" id="confirm_event_btn">Update</button>
                                                    <button type="button" class="btn default">Cancel</button>
                                                </div><!-- end form-actions -->

                                                </form>

                                                <table style="display: none;">
                                                  <tr class="event-row" id="event">
                                                    <td><i class='fa fa-minus-circle removeEventRow' aria-hidden='true'></i></td>
                                                    <td>
                                                      <select class="form-control" name="job_id[]" id="job_id">
                                                        <option value="">Please Select</option>
                                                        @foreach($jobs as $job)
                                                          <option value="{{ $job->job_id }}">{{ $job->job_name }}</option>
                                                        @endforeach
                                                      </select>
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control' name='start_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy'
                                                        value='' id="job_date">
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control' name='end_at[]' data-provide='datepicker' data-date-format='dd/mm/yyyy'
                                                        value='' id="end_at">
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control' name='lunar_date[]' value=''>
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control' name='event[]' value=''>
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control timepicker timepicker-no-seconds' data-provide='timepicker' name='time[]'
                                                        value=''>
                                                    </td>
                                                    <td>
                                                      <input type='text' class='form-control' name='shuwen_title[]' value=''>
                                                    </td>
                                                    <td class="display-row">
                                                      <input type='hidden' name='display_hidden[]' value='' class="display-hidden">
                                                      <input type='checkbox' name='display[]' value='' class='no-height'>
                                                    </td>
                                                  </tr>
                                                </table>

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

    <script src="{{asset('js/custom/common.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
      $(function() {

        $("form").submit(function () {

          var this_master = $(this);

          this_master.find('input[type="checkbox"]').each( function () {
              var checkbox_this = $(this);
              var display_hidden = checkbox_this.closest('.display-row').find('.display-hidden');

              if( checkbox_this.is(":checked") == true ) {
                  display_hidden.attr('value','1');
              }

              else {
                  display_hidden.prop('checked', true);
                  //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
                  display_hidden.attr('value','0');
              }
          });
        });

        $("#addEventRow").click(function() {

          $("#event").clone().appendTo("#festive-event-table");
          $('#festive-event-table tr:last').removeAttr('id');
        });

        $("#festive-event-table").on('click', '.removeEventRow', function() {
          if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
            return false;
          }

          else{
            $(this).parent().parent().remove();
          }

        });

        $("#confirm_event_btn").click(function() {

            var count = 0;
            var errors = new Array();
            var validationFailed = false;

            $(".alert-success").remove();

            $("#festive-event-table select").each(function() {
              var $optText = $(this).find('option:selected');

              if ($optText.val() == "") {
                validationFailed = true;
                errors[count++] = "Job fields are empty.";
                return false;
              }
            });

            $("#festive-event-table input:text[name^='start_at']").each(function() {

              if (!$.trim($(this).val()).length) {
                validationFailed = true;
                errors[count++] = "Job Date fields are empty.";
                return false;
              }
            });

            $("#festive-event-table input:text[name^='end_at']").each(function() {

              if (!$.trim($(this).val()).length) {
                validationFailed = true;
                errors[count++] = "Date To fields are empty.";
                return false;
              }
            });

            $("#festive-event-table input:text[name^='lunar_date']").each(function() {

              if (!$.trim($(this).val()).length) {
                validationFailed = true;
                errors[count++] = "Lunar Date fields are empty.";
                return false;
              }
            });

            $("#festive-event-table input:text[name^='event']").each(function() {

              if (!$.trim($(this).val()).length) {
                validationFailed = true;
                errors[count++] = "Event fields are empty.";
                return false;
              }
            });

            $("#festive-event-table input:text[name^='time']").each(function() {

              if (!$.trim($(this).val()).length) {
                validationFailed = true;
                errors[count++] = "Time fields are empty.";
                return false;
              }
            });

            $('#festive-event-table .event-row td').each(function () {

              if($(this).hasClass("has-error"))
              {
                validationFailed = true;
                errors[count++] = "Date To should be greater than Job Date.";
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

            else
            {
              $(".validation-error").removeClass("bg-danger alert alert-error")
              $(".validation-error").empty();
            }
        });

        $("body").delegate('#job_date', 'focus', function() {

          $(this).on("changeDate",function (){
            var job_date = $(this).val();
            var end_at = $(this).closest("tr").find("#end_at").val();

            var start_date = job_date.split("/").reverse().join("-");
            var end_date = end_at.split("/").reverse().join("-");

            if(end_date == "")
            {
              $(this).closest("tr").find("#end_at").parent().removeClass('has-error');
            }

            else if(new Date(start_date) < new Date(end_date))
            {
              $(this).closest("tr").find("#end_at").parent().removeClass('has-error');
            }

            else
            {
              $(this).closest("tr").find("#end_at").parent().addClass('has-error');
            }
          });

        });

        $("body").delegate('#end_at', 'focus', function() {

          $(this).on("changeDate",function (){
            var job_date = $(this).closest("tr").find("#job_date").val();
            var end_at = $(this).val();

            var start_date = job_date.split("/").reverse().join("-");
            var end_date = end_at.split("/").reverse().join("-");

            if(end_at == "")
            {
              $(this).parent().removeClass("has-error");
            }

            else if(new Date(start_date) > new Date(end_date))
            {
              $(this).parent().addClass('has-error');
            }

            else
            {
              $(this).parent().removeClass("has-error");
            }
          });

        });
      });
    </script>

@stop
