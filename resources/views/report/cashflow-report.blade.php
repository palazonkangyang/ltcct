@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Cashflow Statement Report</h1>

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
                  <span>Cashflow Statement Report</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="validation-error">
                    </div><!-- end validation-error -->

                    <div class="portlet-body">

                      <div class="form-body">

                        <div class="col-md-2">

                          <form action="{{ URL::to('/report/cashflow-report-detail') }}" method="post">
                            {!! csrf_field() !!}

                            <div class="form-group">
                              <label style="padding:0;">Year</label>
                              <input type="text" class="form-control" name="year" value="{{ old('year') }}"
                              data-provide="datepicker" data-date-format="mm" id="year">
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label style="padding:0;">Month</label>
                              <input type="text" class="form-control" name="month" value="{{ old('month') }}"
                              data-provide="datepicker" data-date-format="yyyy" id="month">
                            </div><!-- end form-group -->

                              <div class="form-group">
                                <button type="submit" class="btn blue" id="report">Report</button>
                                <button type="button" class="btn default">Clear</button>
                              </div><!-- end form-group -->
                          </form>

                        </div><!-- end col-md-2 -->
                      </div><!-- end form-body -->

                      <div class="clearfix">
                      </div><!-- end clearfix -->

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

<script type="text/javascript">

  $(function() {

    var path = window.location.pathname;

		$('.navbar-nav li a').each(function() {
	    if ($(this).attr('href') == path) {

				$(this).parent().addClass('active');
				$(this).closest(".mega-menu-dropdown" ).addClass('active');
	    }
   });

   $("#year").val((new Date).getFullYear());

    $("#year").datepicker( {
      format: "yyyy",
      viewMode: "years",
      minViewMode: "years"
    });

    var monthNum = (new Date).getMonth();
    var shortName = moment.monthsShort(monthNum);

    $("#month").val(shortName);

    $("#month").datepicker( {
      format: "M",
      viewMode: "months",
      minViewMode: "months"
    });

    $("#report").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var month = $("#month").val();
      var year = $("#year").val();

      if($.trim(month).length > 0)
      {
        if($.trim(year).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Year is empty."
        }
      }

      if($.trim(year).length <= 0 && $.trim(month).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Please choose month or year to generate income report."
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
