@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Settlement Report</h1>

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
                  <span>Settlement Report</span>
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

                        <div class="col-md-3">

                          <form action="{{ URL::to('/report/settlement-report-detail') }}" method="post">
                            {!! csrf_field() !!}

                            <div class="form-group">
                              <label style="padding:0;">Attended By</label>
                              <select class="form-control" name="staff_id" id="staff_id">
                                <option value="">Please Select</option>
                                @foreach($attendedby as $user)
                                <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                                @endforeach
                              </select>
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label style="padding:0;">Date</label>
                              <input type="text" class="form-control" name="date" value="{{ old('date') }}"
                              data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label style="padding:0;">Type</label>
                              <select class="form-control" name="type" id="type">
                                <!-- <option value="">Please Select</option> -->
                                <option value="0">All</option>
                                @foreach($glcode as $g)
                                <option value="{{ $g->glcode_id }}">{{ $g->type_name }}</option>
                                @endforeach
                              </select>
                            </div><!-- end form-group -->

                            <div class="form-group">
                            </div><!-- end form-group -->

                            <div class="form-group">
                            </div><!-- end form-group -->

                              <div class="form-group">
                                <button type="submit" class="btn blue" id="report">Report</button>
                                <button type="button" class="btn default" onClick="window.location.reload('true')">Clear</button>
                              </div><!-- end form-group -->
                          </form>

                        </div><!-- end col-md-3 -->
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

   var d = new Date();
   var date = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();

   $("#date").val(date);

    $("#report").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var staff_id = $("#staff_id").val();
      var date = $("#date").val();
      var type = $("#type").val();

      if(staff_id == "")
      {
        validationFailed = true;
        errors[count++] = "Attended By field is empty.";
      }

      if($.trim(date).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Date field is empty.";
      }

      if(type == "")
      {
        validationFailed = true;
        errors[count++] = "Type field is empty.";
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
