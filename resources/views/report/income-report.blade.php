@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Income Report</h1>

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
                  <span>Income Report</span>
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

                        <div class="col-md-12">
                            <div class="form-group">

                              <label class="col-md-1 control-label">From</label>

                              <div class="col-md-2">
                                <input type="text" class="form-control" name="from_date" value="{{ old('from_date') }}"
                                data-provide="datepicker" data-date-format="dd/mm/yyyy" id="from_date">
                              </div><!-- end -->

                              <div class="col-md-1">
                                <label class="col-md-1 control-label">To</label>
                              </div><!-- end -->

                              <div class="col-md-2">
                                <input type="text" class="form-control" name="to_date" value="{{ old('to_date') }}"
                                data-provide="datepicker" data-date-format="dd/mm/yyyy" id="to_date">
                              </div><!-- end -->

                              <div class="col-md-2">
                                <button type="button" class="btn blue" id="report">Report</button>
                              </div><!-- end -->
                            </div><!-- end form-group -->
                        </div><!-- end col-md-12 -->

                        <div class="col-md-12">
                          <table class="table table-bordered table-striped" id="report-table">
                            <thead>
                     					<tr>
                     						<th></th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>May</th>
                                <th>Jun</th>
                                <th>July</th>
                                <th>Aug</th>
                                <th>Sep</th>
                                <th>Oct</th>
                                <th>Nov</th>
                                <th>Dec</th>
                     					</tr>
                     				</thead>

                            <tbody>

                            </tbody>
                          </table>
                        </div><!-- end col-md-12 -->

                        <div class="clearfix">
                        </div><!-- end clearfix -->

                      </div><!-- end form-body -->

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

    $("#report").click(function() {
      var from_date = $("#from_date").val();
      var to_date = $("#to_date").val();

      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          from_date: from_date,
          to_date: to_date
      };

      $.ajax({
          type: 'GET',
          url: "/report/report-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            if(response.donation_member.length != 0)
            {
              count = 1;

              $("<tr>").appendTo("#report-table tbody");

              // alert(response.donation_member[0].total_amount);

              for($i = 0; $i < 12; $i++)
              {
                $(response.donation_member[count].month == count ? '<td>S$ ' + response.donation_member[count].total_amount + '</td>': '<td>S$ 0</td>').appendTo("#report-table tbody");
              }

              // $.each(response.donation_member, function(index, data) {
              //   alert(data.month);
              //   $(data.month == count ? '<td>S$ ' + data.total_amount + '</td>': '<td>S$ 0</td>').appendTo("#report-table tbody");
              //
              //   count++;
              // });

              $("</tr>").appendTo("#report-table tbody");
            }
          },

          error: function (response) {
              console.log(response);
          }
      });

    });
  });
</script>

@stop
