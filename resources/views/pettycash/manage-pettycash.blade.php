@extends('layouts.backend.app')

@section('main-content')

<div class="page-container-fluid">

  <div class="page-content-wrapper">

    <div class="page-head">

      <div class="container-fluid">

        <div class="page-title">

          <h1>Petty Cash</h1>

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
            <span>Petty Cash</span>
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
                          <a href="#tab_pettycashlist" data-toggle="tab">Petty Cash List</a>
                        </li>
                        <li>
                          <a href="#tab_newpettycash" data-toggle="tab">New Petty Cash</a>
                        </li>
                        <li id="pettycash-detail" class="disabled">
                          <a href="#tab_pettycashdetail" data-toggle="tab">Petty Cash Detail</a>
                        </li>
                      </ul>

                      <div class="tab-content">

                        <div class="tab-pane active" id="tab_pettycashlist">

                          <div class="form-body">

                            <div class="form-group">

                              <table class="table table-bordered" id="pettycash-table">
                                <thead>
                                  <tr id="filter">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                  <tr>
                                    <th>Voucher No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>description</th>
                                    <th>Payee</th>
                                    <th>Total Debit Amount</th>
                                    <th>Total Credit Amount</th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @foreach($pettycash_voucher as $data)
                                  <tr>
                                    <td><a href="#tab_pettycashdetail" data-toggle="tab"
                                      class="edit-item" id="{{ $data->pettycash_voucher_id }}">{{ $data->voucher_no }}</a></td>
                                      <td>{{ \Carbon\Carbon::parse($data->date)->format("d/m/Y") }}</td>
                                      <td>{{ $data->supplier }}</td>
                                      <td>{{ $data->description }}</td>
                                      <td>{{ $data->payee }}</td>
                                      <td>S$ {{ number_format($data->total_debit_amount, 2) }}</td>
                                      <td>S$ {{ number_format($data->total_debit_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                              </table>

                            </div><!-- end form-group -->

                          </div><!-- end form-body -->

                        </div><!-- end tab-pane tab_pettycashlist -->

                        <div class="tab-pane" id="tab_newpettycash">

                          <div class="form-body">

                            <form method="post" action="{{ URL::to('/pettycash/new-pettycash') }}"
                            class="form-horizontal form-bordered">

                            {!! csrf_field() !!}

                            <div class="col-md-6">

                              <div class="form-group">
                                <label class="col-md-3">Voucher No *</label>
                                <div class="col-md-8">
                                  <input type="text" class="form-control" name="voucher_no" value="{{ $voucher_no }}" id="voucher_no">
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Date *</label>
                                <div class="col-md-8">
                                  <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide='datepicker' data-date-format='dd/mm/yyyy' id="date">
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Supplier *</label>
                                <div class="col-md-8">
                                  <input type="hidden" name="supplier_id" value="" id="supplier_id">
                                  <input type="text" class="form-control" name="supplier_name" value="{{ old('supplier_name') }}" id="supplier_name">
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Description</label>
                                <div class="col-md-8">
                                  <input type="text" class="form-control" name="description" value="{{ old('description') }}" id="description">
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Payee *</label>
                                <div class="col-md-8">
                                  <input type="text" class="form-control" name="payee" value="{{ old('payee') }}" id="payee">
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Job *</label>
                                <div class="col-md-8">
                                  <select class="form-control" name="job_id" id="job_id">
                                    <option value="">Please Select</option>
                                    @foreach($job as $j)
                                    <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
                                    @endforeach
                                  </select>
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <label class="col-md-3">Remark</label>
                                <div class="col-md-8">
                                  <textarea name="remark" class="form-control" rows="3" id="remark">{{ old('remark') }}</textarea>
                                </div><!-- end col-md-8 -->
                              </div><!-- end form-group -->

                              <div class="form-group">
                                <p>&nbsp;</p>
                              </div><!-- end form-group -->

                              <div class="clearfix">
                              </div><!-- end clearfix -->

                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                            </div><!-- end col-md-6 -->

                            <div class="col-md-12">
                              <table class="table table-bordered" id="new-pettycash-table">
                                <thead>
                                  <tr>
                                    <th width="2%">#</th>
                                    <th width="40%">GL Account</th>
                                    <th width="20%">Debit</th>
                                    <th width="20%">Credit</th>
                                    <th width="5%"></th>
                                  </tr>
                                </thead>

                                <tbody>
                                  <tr>
                                    <td></td>
                                    <td>
                                      <input type="text" class="form-control" value="{{ $cash_in_hand[0]->type_name }}" style="width: 80%;" id="cash_in_hand" readonly>
                                      <input type="hidden" name="glcode_id[]" value="{{ $cash_in_hand[0]->glcode_id }}" id="hidden_cash_in_hand">
                                    </td>
                                    <td class="debit_amount_col">
                                      <input type="text" class="form-control debit_amount" name="debit_amount[]" value="" style="width: 50%;">
                                    </td>
                                    <td class="credit_amount_col">
                                      <input type="text" class="form-control credit_amount" name="credit_amount[]" value="" style="width: 50%;">
                                    </td>
                                    <td></td>
                                  </tr>
                                  <tr id="append-pettycash-row">
                                    <td></td>
                                    <td></td>
                                    <td>S$ <span id="total_debit">0</span></td>
                                    <td>S$ <span id="total_credit">0</span></td>
                                  </tr>
                                </tbody>
                              </table>

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
                                    <button type="submit" class="btn blue" id="confirm_btn">Confirm
                                    </button>
                                    <button type="button" class="btn default">Cancel</button>
                                  </div><!-- end form-actions -->
                                </div><!-- end col-md-8 -->

                              </div><!-- end form-group -->
                            </div><!-- end col-md-12 -->

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

                        </div><!-- end form-body -->

                        <div class="clearfix"></div><!-- end clearfix -->

                      </div><!-- end tab-pane tab_newpettycash -->

                      <div class="tab-pane" id="tab_pettycashdetail">

                        <div class="form-body">

                          <div class="col-md-6">

                            <div class="form-group">
                              <label class="col-md-3">Voucher No</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_voucher_no" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Date</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_date" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Supplier</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_supplier" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Description</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_description" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Payee</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_payee" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Job</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" id="show_job" readonly>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                            <div class="form-group" style="margin-bottom: 30px;">
                              <label class="col-md-3">Remark</label>
                              <div class="col-md-8">
                                <textarea class="form-control" id="show_remark" readonly></textarea>
                              </div><!-- end col-md-8 -->
                            </div><!-- end form-group -->

                          </div><!-- end col-md-6 -->

                          <div class="col-md-6">

                          </div><!-- end col-md-6 -->

                          <div class="col-md-12">

                            <table class="table table-bordered" id="pettycash-detail-table">
                              <thead>
                                <tr>
                                  <th width="30%">GL Account</th>
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

                      </div><!-- end tab-pane tab_paymentdetail -->

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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>

<script type="text/javascript">
$(function() {

  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));
  });

  function getParameter(theParameter) {
    var params = window.location.search.substr(1).split('&');

    for (var i = 0; i < params.length; i++) {

      var p=params[i].split('=');
      if (p[0] == theParameter) {
        return decodeURIComponent(p[1]);
      }

    }
    return false;
  }

  if(window.location.search.length)
  {
    var queryString = window.location.search;
    var pettycash_voucher_id = getParameter('pettycash_voucher_id');

    localStorage.setItem('activeTab', '#tab_pettycashdetail');

    if(pettycash_voucher_id)
    {
      $("#pettycash-detail-table tbody").find("tr:not(:last)").remove();

      $("#show_voucher_no").val('');
      $("#show_date").val('');
      $("#show_supplier").val('');
      $("#show_description").val('');
      $("#show_payee").val('');
      $("#show_job").val('');
      $("#show_remark").val('');

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        pettycash_voucher_id: pettycash_voucher_id
      };

      $.ajax({
        type: 'GET',
        url: "/pettycash/pettycash-voucher-detail",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          $("#show_voucher_no").val(response.pettycash_voucher[0]['voucher_no']);
          $("#show_date").val(response.pettycash_voucher[0]['date']);
          $("#show_supplier").val(response.pettycash_voucher[0]['supplier']);
          $("#show_description").val(response.pettycash_voucher[0]['description']);
          $("#show_payee").val(response.pettycash_voucher[0]['payee']);
          $("#show_job").val(response.pettycash_voucher[0]['job_name']);
          $("#show_remark").val(response.pettycash_voucher[0]['remark']);

          $("#show_total_debit").text(response.pettycash_voucher[0]['total_debit_amount'].toFixed(2));
          $("#show_total_credit").text(response.pettycash_voucher[0]['total_credit_amount'].toFixed(2));

          $.each(response.pettycash_voucher, function(index, data) {

            $( "<tr><td><label>" + data.type_name + "</label></td>" +
            "<td><input class='form-control' value='" + (data.debit_amount !=null ? data.debit_amount.toFixed(2) : '') + "' style='width: 50%;' type='text' readonly></td>" +
            "<td><input class='form-control' value='" + (data.credit_amount !=null ? data.credit_amount.toFixed(2) : '') + "' style='width: 50%;' type='text' readonly></td>" +
            "</tr>" ).insertBefore( $( "#appendRow" ) );
          });
        },

        error: function (response) {
          console.log(response);
        }
      });
    }
  }

  var strDate = $.datepicker.formatDate('dd/mm/yy', new Date());
  $("#date").val(strDate);

  $("#supplier_name").autocomplete({
    source: "/expenditure/search/supplier",
    minLength: 1,
    select: function(event, ui) {
      $('#supplier_name').val(ui.item.value);
      $('#supplier_id').val(ui.item.id);
    }
  });

  $("body").delegate('.debit_amount_col', 'focus', function() {

    $(".debit_amount").on("input", function(evt) {
      var self = $(this);
      self.val(self.val().replace(/[^0-9\.]/g, ''));
      if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
      {
        evt.preventDefault();
      }
    });

  });

  $("body").delegate('.credit_amount_col', 'focus', function() {

    $(".credit_amount").on("input", function(evt) {
      var self = $(this);
      self.val(self.val().replace(/[^0-9\.]/g, ''));
      if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
      {
        evt.preventDefault();
      }
    });
  });

  // Disabled Edit Tab
  $(".nav-tabs > li").click(function(){
    if($(this).hasClass("disabled"))
    return false;
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
    console.log(activeTab);
  }

  // DataTable
  var table = $('#pettycash-table').removeAttr('width').DataTable( {
    "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
    columnDefs: [
      { "width": "120px", "targets": 0 },
      { "width": "120px", "targets": 1 },
      { "width": "180px", "targets": 2 },
      { "width": "200px", "targets": 3 },
      { "width": "200px", "targets": 4 },
      { "width": "200px", "targets": 5 },
      { "width": "200px", "targets": 6 }
    ]
  } );

  $('#pettycash-table thead tr#filter th').each( function () {
    var title = $('#pettycash-table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
  });

  // Apply the filter
  $("#pettycash-table thead input").on( 'keyup change', function () {
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

  $("#addRow").click(function() {
    $("#append-row").clone().insertBefore("#append-pettycash-row");
    $('#append-pettycash-row tr:last').prev().removeAttr('id');
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

  $("#new-pettycash-table").on('click', '.removeRow', function() {
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

  $("#pettycash-table").on('click','.edit-item',function(e) {

    var pettycash_voucher_id = $(this).attr("id");

    $("#pettycash-detail-table tbody").find("tr:not(:last)").remove();

    $("#show_voucher_no").val('');
    $("#show_date").val('');
    $("#show_supplier").val('');
    $("#show_description").val('');
    $("#show_payee").val('');
    $("#show_job").val('');
    $("#show_remark").val('');

    $(".nav-tabs > li:first-child").removeClass("active");
    $("#pettycash-detail").addClass("active");

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      pettycash_voucher_id: pettycash_voucher_id
    };

    $.ajax({
      type: 'GET',
      url: "/pettycash/pettycash-voucher-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#show_voucher_no").val(response.pettycash_voucher[0]['voucher_no']);
        $("#show_date").val(response.pettycash_voucher[0]['date']);
        $("#show_supplier").val(response.pettycash_voucher[0]['supplier']);
        $("#show_description").val(response.pettycash_voucher[0]['description']);
        $("#show_payee").val(response.pettycash_voucher[0]['payee']);
        $("#show_job").val(response.pettycash_voucher[0]['job_name']);
        $("#show_remark").val(response.pettycash_voucher[0]['remark']);

        $("#show_total_debit").text(response.pettycash_voucher[0]['total_debit_amount'].toFixed(2));
        $("#show_total_credit").text(response.pettycash_voucher[0]['total_credit_amount'].toFixed(2));

        $.each(response.pettycash_voucher, function(index, data) {

          $( "<tr><td><label>" + data.type_name + "</label></td>" +
          "<td><input class='form-control' value='" + (data.debit_amount !=null ? data.debit_amount.toFixed(2) : '') + "' style='width: 50%;' type='text' readonly></td>" +
          "<td><input class='form-control' value='" + (data.credit_amount !=null ? data.credit_amount.toFixed(2) : '') + "' style='width: 50%;' type='text' readonly></td>" +
          "</tr>" ).insertBefore( $( "#appendRow" ) );
        });
      },

      error: function (response) {
        console.log(response);
      }
    });
  });

  $("#confirm_btn").click(function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var voucher_no = $("#voucher_no").val();
    var date = $("#date").val();
    var supplier_name = $("#supplier_name").val();
    var payee = $("#payee").val();
    var job_id = $("#job_id").val();
    var total_debit = $("#total_debit").text();
    var total_credit = $("#total_credit").text();

    if ($.trim(voucher_no).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Voucher No field is empty.";
    }

    if ($.trim(date).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Date field is empty.";
    }

    if ($.trim(supplier_name).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Supplier Name field is empty.";
    }

    if ($.trim(payee).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Payee field is empty.";
    }

    if ($.trim(job_id).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Job field is empty.";
    }

    $("#new-pettycash-table select").each(function() {
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

});
</script>


@stop
