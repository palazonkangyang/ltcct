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
                          <li id="edit-job" class="disabled">
                            <a href="#tab_editjob" data-toggle="tab">Edit Job</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_joblist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="joblist-table">
                                  <thead>
                                      <tr id="filter">
                                          <th>Job Reference No</th>
                                          <th>Name</th>
                                          <th>Description</th>
                                      </tr>
                                      <tr>
                                          <th>Job Reference No</th>
                                          <th>Name</th>
                                          <th>Description</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                    @if(count($job))

                                      @foreach($job as $j)
                                      <tr>
                                        <td><a href="#tab_editjob" data-toggle="tab"
                                            class="edit-item" id="{{ $j->job_id }}">{{ $j->job_reference_no }}
                                        </td>
                                        <td>{{ $j->job_name }}</td>
                                        <td>{{ $j->job_description }}</td>
                                      </tr>
                                      @endforeach

                                    @endif
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
                                        <input type="text" class="form-control" name="job_name" value="{{ old('job_name') }}" id="job_name">
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Description *</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="job_description" rows="4" id="job_description">{{ old('job_description') }}</textarea>
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

                          </div><!-- end tab-pane tab_newjob -->

                          <div class="tab-pane" id="tab_editjob">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/job/update-job') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <input type="hidden" name="edit_job_id" id="edit_job_id" value="">
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Reference No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="edit_job_reference_no" value="{{ old('edit_job_reference_no') }}" id="edit_job_reference_no" readonly>
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Name *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="edit_job_name" value="{{ old('edit_job_name') }}" id="edit_job_name">
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label">Job Description *</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="edit_job_description" rows="4" id="edit_job_description">{{ old('edit_job_description') }}</textarea>
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
                                        <input type="password" class="form-control" name="edit_authorized_password" value="" id="edit_authorized_password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="update_job_btn">Update
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

                          </div><!-- end tab-pane tab_editjob -->

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

    // Disabled Edit Tab
    $(".nav-tabs > li").click(function(){
        if($(this).hasClass("disabled"))
            return false;
    });

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

    if ( $('.alert-success').children().length > 0 ) {
        localStorage.removeItem('activeTab');
    }

    else
    {
      if(localStorage.getItem('job_id'))
      {
        var job_id = localStorage.getItem('job_id');
      }

      $("#edit_job_id").val(job_id);

      var activeTab = localStorage.getItem('activeTab');
    }

    if (activeTab) {
        $('a[href="' + activeTab + '"]').tab('show');
        console.log(activeTab);
    }

    // DataTable
    var table = $('#joblist-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
    });

    $('#joblist-table thead tr#filter th').each( function () {
          var title = $('#joblist-table thead th').eq( $(this).index() ).text();
          $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#joblist-table thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    });

    function stopPropagation(evt) {
      if (evt.stopPropagation !== undefined) {
        evt.stopPropagation();
      } else {
        evt.cancelBubble = true;
      }
    }

    $("#joblist-table").on('click','.edit-item',function(e) {

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#edit-job").addClass("active");

      var job_id = $(this).attr("id");

      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          job_id: job_id
      };

      $.ajax({
          type: 'GET',
          url: "/job/job-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {

            localStorage.setItem('job_id', response.job['job_id']);

            if(localStorage.getItem('job_id'))
            {
                var job_id = localStorage.getItem('job_id');
            }

            $("#edit_job_id").val(job_id);
            $("#edit_job_reference_no").val(response.job['job_reference_no']);
            $("#edit_job_name").val(response.job['job_name']);
            $("#edit_job_description").val(response.job['job_description']);
          },

          error: function (response) {
              console.log(response);
          }
      });

    });

    $("#update_job_btn").click(function() {
      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var job_name = $("#edit_job_name").val();
      var job_description = $("#edit_job_description").val();

      if ($.trim(job_name).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Job Name field is empty."
      }

      if ($.trim(job_description).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Job Description field is empty."
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
