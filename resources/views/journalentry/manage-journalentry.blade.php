@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Journal Entry</h1>

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
                  <span>Journal Entry</span>
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
                            <a href="#tab_journalentrylist" data-toggle="tab">Journal Entry List</a>
                          </li>
                          <li>
                            <a href="#tab_newjournalentry" data-toggle="tab">New Journal Entry</a>
                          </li>
                          <li id="edit-journalentry" class="disabled">
                            <a href="#tab_editjournalentry" data-toggle="tab">Edit Journal Entry</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_journalentrylist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="journalentry-table">
                                  <thead>
                                      <tr id="filter">
                                          <th>Journal Entry No</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                          <th>Debit</th>
                                          <th>Credit</th>
                                      </tr>
                                      <tr>
                                          <th>Journal Entry No</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                          <th>Debit</th>
                                          <th>Credit</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                      @if(count($journalentry))

                                        @foreach($journalentry as $je)
                                        <tr>
                                          <td><a href="#tab_editjournalentry" data-toggle="tab"
                                              class="edit-item" id="{{ $je->journalentry_id }}">{{ $je->journalentry_no }}</a>
                                          </td>
                                          <td>{{ \Carbon\Carbon::parse($je->date)->format("d/m/Y") }}</td>
                                          <td>{{ $je->description }}</td>
                                          <td>{{ $je->debit_account }}</td>
                                          <td>{{ $je->credit_account }}</td>
                                        </tr>
                                        @endforeach

                                      @endif
                                  </tbody>
                                </table>
                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_journalentrylist -->

                          <div class="tab-pane" id="tab_newjournalentry">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/journalentry/new-journalentry') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Journal Entry No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="journalentry_no" value="{{ old('journalentry_no') }}" id="journalentry_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description *</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" rows="3" id="description">{{ old('description') }}</textarea>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Debit *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="debit">
                                        @foreach($glcode as $gl)

                                          @if($gl->balancesheet_side == 'ap')
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endif

                                        @endforeach
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Credit *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="credit">
                                        @foreach($glcode as $gl)

                                          @if($gl->balancesheet_side == 'ar')
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endif

                                        @endforeach
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

                          </div><!-- end tab-pane tab_newjournalentry -->

                          <div class="tab-pane" id="tab_editjournalentry">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/journalentry/update-journalentry') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <input type="hidden" name="edit_journalentry_id" id="edit_journalentry_id" value="">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Journal Entry No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="edit_journalentry_no" value="{{ old('edit_journalentry_no') }}" id="edit_journalentry_no" readonly>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="edit_date" value="{{ old('edit_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description *</label>
                                    <div class="col-md-9">
                                        <textarea name="edit_description" class="form-control" rows="3" id="edit_description">{{ old('edit_description') }}</textarea>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Debit *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="edit_debit" id="edit_debit">
                                        @foreach($glcode as $gl)

                                          @if($gl->balancesheet_side == 'ap')
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endif

                                        @endforeach
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Credit *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="edit_credit" id="edit_credit">
                                        @foreach($glcode as $gl)

                                          @if($gl->balancesheet_side == 'ar')
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endif

                                        @endforeach
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
                                        <input type="password" class="form-control" name="edit_authorized_password" id="edit_authorized_password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="update_journalentry_btn">Update
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

                          </div><!-- end tab-pane tab_editjournalentry -->

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

    // DataTable
    var table = $('#journalentry-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
    });

    $('#journalentry-table thead tr#filter th').each( function () {
          var title = $('#journalentry-table thead th').eq( $(this).index() ).text();
          $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#journalentry-table thead input").on( 'keyup change', function () {
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
      if(localStorage.getItem('journalentry_id'))
      {
        var journalentry_id = localStorage.getItem('journalentry_id');
        var debit = localStorage.getItem('debit');
        var credit = localStorage.getItem('credit');
      }

      $("#edit_journalentry_id").val(journalentry_id);
      $("#edit_debit").val(debit);
      $("#edit_credit").val(credit);

      var activeTab = localStorage.getItem('activeTab');
    }

    if (activeTab) {
        $('a[href="' + activeTab + '"]').tab('show');
        console.log(activeTab);
    }

    $("#journalentry-table").on('click','.edit-item',function(e) {

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#edit-journalentry").addClass("active");

      var journalentry_id = $(this).attr("id");

      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          journalentry_id: journalentry_id
      };

      $.ajax({
          type: 'GET',
          url: "/journalentry/journalentry-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            localStorage.setItem('journalentry_id', response.journalentry['journalentry_id']);
            localStorage.setItem('debit', response.journalentry['debit']);
            localStorage.setItem('credit', response.journalentry['credit']);

            if(localStorage.getItem('journalentry_id'))
            {
                var journalentry_id = localStorage.getItem('journalentry_id');
                var debit = localStorage.getItem('debit');
                var credit = localStorage.getItem('credit');
            }

            console.log(debit);
            console.log(credit);

            $("#edit_journalentry_id").val(journalentry_id);
            $("#edit_journalentry_no").val(response.journalentry['journalentry_no']);
            $("#edit_date").val(response.journalentry['date']);
            $("#edit_description").val(response.journalentry['description']);
            $("#edit_debit").val(debit);
            $("#edit_credit").val(credit);
          },

          error: function (response) {
              console.log(response);
          }
      });

    });

    $("#update_journalentry_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var journalentry_no = $("#edit_journalentry_no").val();
      var date = $("#edit_date").val();
      var description = $("#edit_description").val();
      var authorized_password = $("#edit_authorized_password").val();

      if ($.trim(journalentry_no).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Journal Reference No field is empty."
      }

      if ($.trim(date).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Date field is empty."
      }

      if ($.trim(description).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Description field is empty."
      }

      if ($.trim(authorized_password).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Authorized pasword field is empty."
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
