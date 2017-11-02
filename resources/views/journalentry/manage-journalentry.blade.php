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
                          <a href="#tab_editjournalentry" data-toggle="tab">Journal Entry Detail</a>
                        </li>
                      </ul>

                      <div class="tab-content">

                        <div class="tab-pane active" id="tab_journalentrylist">

                          <div class="form-body">

                            <div class="form-group">

                              <table class="table table-bordered" id="journalentry-table">
                                <thead>
                                  <tr id="filter">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
                                  @foreach($journalentry as $j)
                                  <tr>
                                    <td><a href="#tab_editjournalentry" data-toggle="tab"
                                      class="edit-item" id="{{ $j->journalentry_id }}">{{ $j->journalentry_no }}</a></td>
                                      <td>{{ \Carbon\Carbon::parse($j->date)->format("d/m/Y") }}</td>
                                      <td>{{ $j->description }}</td>
                                      <td>S$ {{ number_format($j->total_debit_amount, 2) }}</td>
                                      <td>S$ {{ number_format($j->total_debit_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_journalentrylist -->

                          <div class="tab-pane" id="tab_newjournalentry">

                            <div class="form-body">

                              <div class="col-md-12">

                                <form method="post" action="{{ URL::to('/journalentry/new-journalentry') }}"
                                class="form-horizontal form-bordered">

                                {!! csrf_field() !!}

                                <div class="form-group">
                                  <label class="col-md-1">Date *</label>
                                  <div class="col-md-3">
                                    <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                                  </div><!-- end col-md-9 -->
                                </div><!-- end form-group -->

                                <div class="form-group" style="margin-bottom: 30px;">
                                  <label class="col-md-1">Description</label>
                                  <div class="col-md-3">
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" id="description">
                                  </div><!-- end col-md-9 -->
                                </div><!-- end form-group -->

                                <table class="table table-bordered" id="new-journalentry-table">
                                  <thead>
                                    <tr>
                                      <th width="2%">#</th>
                                      <th width="40%">Journal Entry</th>
                                      <th width="20%">Debit</th>
                                      <th width="20%">Credit</th>
                                      <th width="5%"></th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    <tr>
                                      <td></td>
                                      <td>
                                        <select class="form-control" name="glcode_id[]" style="width: 80%;" id="glcode_id">
                                          <option value="">Please Select</option>
                                          @foreach($glcode as $gl)
                                          <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endforeach
                                        </select>
                                      </td>
                                      <td class="debit_amount_col">
                                        <input type="text" class="form-control debit_amount" name="debit_amount[]" value="" style="width: 50%;">
                                      </td>
                                      <td class="credit_amount_col">
                                        <input type="text" class="form-control credit_amount" name="credit_amount[]" value="" style="width: 50%;">
                                      </td>
                                      <td></td>
                                    </tr>
                                    <tr id="append-journalentry">
                                      <td></td>
                                      <td></td>
                                      <td>S$ <span id="total_debit">0</span></td>
                                      <td>S$ <span id="total_credit">0</span></td>
                                    </tr>
                                  </tbody>
                                </table>

                                <div class="clearfix">
                                </div><!-- end clearfix -->

                                <div class="form-group">
                                  <input type="hidden" name="total_debit_amount" value="0" id="total_debit_amount">
                                  <input type="hidden" name="total_credit_amount" value="0" id="total_credit_amount">
                                </div><!-- end form-group -->

                                <div class="form-group">
                                  <button type="button" class="btn green" style="width: 100px; margin: 0 25px 0 10px;" id="addRow">Add New
                                  </button>
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

                              <table style="display: none;">
                                <tr id="append-row">
                                  <td><i class='fa fa-minus-circle removeRow' aria-hidden='true'></i></td>
                                  <td>
                                    <select class="form-control" name="glcode_id[]" style="width: 80%;" id="glcode_id">
                                      <option value="">Please Select</option>
                                      @foreach($glcode as $gl)
                                      <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                      @endforeach
                                    </select>
                                  </td>
                                  <td class="debit_amount_col">
                                    <input type="text" class="form-control debit_amount" name="debit_amount[]" value="" style="width: 50%;">
                                  </td>
                                  <td class="credit_amount_col">
                                    <input type="text" class="form-control credit_amount" name="credit_amount[]" value="" style="width: 50%;">
                                  </td>
                                  <td></td>
                                </tr>
                              </table>

                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                            </div><!-- end col-md-6 -->

                          </div><!-- end form-body -->

                          <div class="clearfix"></div><!-- end clearfix -->

                        </div><!-- end tab-pane tab_newjournalentry -->

                        <div class="tab-pane" id="tab_editjournalentry">

                          <div class="form-body">

                            <div class="col-md-12">

                              <div class="form-group">
                                <label class="col-md-2">Journal Entry No</label>
                                <div class="col-md-3">
                                  <input type="text" class="form-control" id="journalentry_no" readonly>
                                </div><!-- end col-md-3 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-2">Date</label>
                                <div class="col-md-3">
                                  <input type="text" class="form-control" id="show_date" readonly>
                                </div><!-- end col-md-3 -->
                              </div><!-- end form-group -->

                              <div class="form-group" style="margin-bottom: 30px;">
                                <label class="col-md-2">Description</label>
                                <div class="col-md-3">
                                  <input type="text" class="form-control" id="show_description" readonly>
                                </div><!-- end col-md-3 -->
                              </div><!-- end form-group -->

                              <table class="table table-bordered" id="journalentry-detail-table">
                                <thead>
                                  <tr>
                                    <th width="30%">Journal Entry</th>
                                    <th width="20%">Debit</th>
                                    <th width="20%">Credit</th>
                                    <th width="5%"></th>
                                  </tr>
                                </thead>

                                <tbody>
                                  <tr id="appendRow">
                                    <td></td>
                                    <td>S$ <span id="show_total_debit">0</span></td>
                                    <td>S$ <span id="show_total_credit">0</span></td>
                                  </tr>
                                </tbody>

                              </table>

                            </div><!-- end col-md-12 -->

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

<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$(function() {

  $(".hylink").click(function() {
    localStorage.removeItem('activeTab');
    localStorage.removeItem('tab');
    localStorage.removeItem('newtab');
  });

  $(".debit_amount").on("input", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9\.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
    {
      evt.preventDefault();
    }
  });

  $(".credit_amount").on("input", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9\.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
    {
      evt.preventDefault();
    }
  });

  $("#addRow").click(function() {
    $("#append-row").clone().insertBefore("#append-journalentry");
    $('#append-journalentry tr:last').prev().removeAttr('id');
  });

  $('body').on('input', '.debit_amount_col', function(){
    var sum = 0;

    $('.debit_amount').each(function(){

      sum += +$(this).val();

      $("#total_debit").text(sum);
      $("#total_debit_amount").val(sum);
    });
  });

  $('body').on('input', '.credit_amount_col', function(){
    var sum = 0;

    $('.credit_amount').each(function(){

      sum += +$(this).val();

      $("#total_credit").text(sum);
      $("#total_credit_amount").val(sum);
    });
  });

  $("#new-journalentry-table").on('click', '.removeRow', function() {
    if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
      return false;
    }

    else {
      $(this).parent().parent().remove();

      var debit_sum = 0;

      $('.debit_amount').each(function(){

        debit_sum += +$(this).val();

        $("#total_debit").text(debit_sum);
        $("#total_debit_amount").val(debit_sum);
      });

      var credit_sum = 0;

      $('.credit_amount').each(function(){

        credit_sum += +$(this).val();

        $("#total_credit").text(credit_sum);
        $("#total_credit_amount").val(credit_sum);
      });
    }

  });

  $("#confirm_journalentry_btn").click(function() {
    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var date = $("#date").val();
    var total_debit = $("#total_debit").text();
    var total_credit = $("#total_credit").text();

    if($.trim(date).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Date is empty.";
    }

    $("#new-journalentry-table select").each(function() {
      var $optText = $(this).find('option:selected');

      if ($optText.val() == "") {
        validationFailed = true;
        errors[count++] = "GL Account fields are empty.";
        return false;
      }
    });

    if (total_debit != total_credit)
    {
      validationFailed = true;
      errors[count++] = "Debit and Credit are not the same.";
    }

    if(total_debit == 0 && total_credit == 0)
    {
      validationFailed = true;
      errors[count++] = "The amount is empty.";
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

  // DataTable
  var table = $('#journalentry-table').removeAttr('width').DataTable( {
    "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
    "order": [[ 0, "desc" ]],
    columnDefs: [
      { "width": "200px", "targets": 0 },
      { "width": "200px", "targets": 1 },
      { "width": "300px", "targets": 2 },
      { "width": "200px", "targets": 3 },
      { "width": "200px", "targets": 4 }
    ],
    fixedColumns: true
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
    var activeTab = localStorage.getItem('activeTab');
  }

  if (activeTab) {
    $('a[href="' + activeTab + '"]').tab('show');
  }

  $("#journalentry-table").on('click','.edit-item',function(e) {

    $("#journalentry-detail-table tbody").find("tr:not(:last)").remove();

    $("#journalentry_no").val('');
    $("#show_date").val('');
    $("#show_description").val('');

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
        $("#journalentry_no").val(response.journalentry[0]['journalentry_no']);
        $("#show_date").val(response.journalentry[0]['date']);
        $("#show_description").val(response.journalentry[0]['description']);

        $("#show_total_debit").text(response.journalentry[0]['total_debit_amount']);
        $("#show_total_credit").text(response.journalentry[0]['total_credit_amount']);

        $.each(response.journalentry, function(index, data) {

          $( "<tr><td><label>" + data.type_name + "</label></td>" +
          "<td><input class='form-control' value='" + (data.debit_amount !=null ? data.debit_amount : '') + "' style='width: 50%;' type='text' readonly></td>" +
          "<td><input class='form-control' value='" + (data.credit_amount !=null ? data.credit_amount : '') + "' style='width: 50%;' type='text' readonly></td>" +
          "</tr>" ).insertBefore( $( "#appendRow" ) );
        });
      },

      error: function (response) {
        console.log(response);
      }
    });

  });

});
</script>


@stop
