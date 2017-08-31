@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Paid</h1>

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
                  <span>Paid</span>
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
                            <a href="#tab_paidlist" data-toggle="tab">Paid List</a>
                          </li>
                          <li>
                            <a href="#tab_newpaid" data-toggle="tab">New Paid</a>
                          </li>
                          <li id="edit-paid" class="disabled">
                            <a href="#tab_editpaid" data-toggle="tab">Edit Paid</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_paidlist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="paid-table">
                                  <thead>
                                      <tr id="filter">
                                          <th>Reference No</th>
                                          <th>Date</th>
                                          <th>Expenditure No</th>
                                          <th>Supplier</th>
                                          <th>Description</th>
                                          <th>Expenditure Total</th>
                                          <th>Outstanding Total</th>
                                          <th>Status</th>
                                      </tr>
                                      <tr>
                                          <th>Reference No</th>
                                          <th>Date</th>
                                          <th>Expenditure No</th>
                                          <th>Supplier</th>
                                          <th>Description</th>
                                          <th>Expenditure Total</th>
                                          <th>Outstanding Total</th>
                                          <th>Status</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                      @if(count($paid))

                                        @foreach($paid as $p)
                                        <tr>
                                          <td><a href="#tab_editpaid" data-toggle="tab"
                                              class="edit-item" id="{{ $p->paid_id }}">{{ $p->reference_no }}</td>
                                          <td>{{ \Carbon\Carbon::parse($p->date)->format("d/m/Y") }}</td>
                                          <td>{{ $p->expenditure_reference_no }}</td>
                                          <td>{{ $p->supplier }}</td>
                                          <td>{{ $p->description }}</td>
                                          <td>S$ {{ $p->expenditure_total }}</td>
                                          <td>S$ {{ $p->outstanding_total }}</td>
                                          <td>{{ $p->status }}</td>
                                        </tr>
                                        @endforeach

                                      @endif
                                  </tbody>
                                </table>
                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_paidlist -->

                          <div class="tab-pane" id="tab_newpaid">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/paid/new-paid') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Reference No *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="reference_no" value="{{ old('reference_no') }}" id="reference_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure No *</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="expenditure_id" id="expenditure_id">
                                          @foreach($expenditure as $exp)
                                          <option value="{{ $exp->expenditure_id }}">{{ $exp->reference_no }}</option>
                                          @endforeach
                                        </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Supplier *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="supplier" value="{{ old('supplier') }}" id="supplier">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description *</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" rows="3" id="description">{{ old('description') }}</textarea>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure Total *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="expenditure_total" value="{{ old('expenditure_total') }}" id="expenditure_total">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Outstanding Total *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="outstanding_total" value="{{ old('outstanding_total') }}" id="outstanding_total">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Amount *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="amount" value="{{ old('amount') }}" id="amount">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Status *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="status" id="status">
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Type *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="type" id="type">
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div id="cash">

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Voucher No</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cash_voucher_no" value="{{ old('cash_voucher_no') }}" id="cash_voucher_no">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Transaction Date</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="transaction_date" value="{{ old('transaction_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="transaction_date">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cash Account</label>
                                      <div class="col-md-9">
                                          <select class="form-control" name="cash_account" id="cash_account">
                                            <option value="cash">Cash</option>
                                          </select>
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cash Amount</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cash_amount" value="{{ old('cash_amount') }}" id="cash_amount">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Payee</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cash_payee" value="{{ old('cash_payee') }}" id="cash_payee">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                  </div><!-- end cash -->

                                  <div id="cheque" style="display: none;">

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cheque No</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cheque_no" value="{{ old('cheque_no') }}" id="cheque_no">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cheque Account</label>
                                      <div class="col-md-9">
                                          <select class="form-control" name="cheque_account" id="cheque_account">
                                            @foreach($glcode as $gl)
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                            @endforeach
                                          </select>
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">receipt</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cheque_receipt" value="{{ old('cheque_receipt') }}" id="cheque_receipt">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Issuing Banking</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="issuing_banking" value="{{ old('issuing_banking') }}" id="issuing_banking">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">from</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cheque_from" value="{{ old('cheque_from') }}" id="cheque_from">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cheque Amount</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cheque_amount" value="{{ old('cheque_amount') }}" id="cheque_amount">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cheque Date</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cheque_date" value="{{ old('cheque_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="cheque_date">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Currency</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="currency" value="{{ old('currency') }}" id="currency">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Customer</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="customer" value="{{ old('customer') }}" id="customer">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                    <div class="form-group">
                                      <label class="col-md-3 control-label">Cash Date</label>
                                      <div class="col-md-9">
                                          <input type="text" class="form-control" name="cash_date" value="{{ old('cash_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="cash_date">
                                      </div><!-- end col-md-9 -->
                                    </div><!-- end form-group -->

                                  </div><!-- end cheque -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Job</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="job_id" id="job_id">
                                          @foreach($job as $j)
                                          <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
                                          @endforeach
                                        </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Gl Description</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="gl_description" value="{{ old('gl_description') }}" id="gl_description">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Remark</label>
                                    <div class="col-md-9">
                                        <textarea name="remark" class="form-control" rows="3" id="remark">{{ old('remark') }}</textarea>
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
                                        <button type="submit" class="btn blue" id="confirm_paid_btn">Confirm
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

                          </div><!-- end tab-pane tab_newpaid -->

                          <div class="tab-pane" id="tab_editpaid">

                            @include('layouts.partials.edit-paid')

                          </div><!-- end tab-pane tab_editpaid -->

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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/custom/common.js')}}"></script>
<script src="{{asset('js/custom/edit-paid.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

  $(function() {

    // Disabled Edit Tab
    $(".nav-tabs > li").click(function(){
        if($(this).hasClass("disabled"))
            return false;
    });

    // DataTable
    var table = $('#paid-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
    });

    $('#paid-table thead tr#filter th').each( function () {
          var title = $('#paid-table thead th').eq( $(this).index() ).text();
          $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#paid-table thead input").on( 'keyup change', function () {
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

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

    if ( $('.alert-success').children().length > 0 ) {
        localStorage.removeItem('activeTab');

        localStorage.removeItem('expenditure_id');
        localStorage.removeItem('status');
    }

    else
    {
      if(localStorage.getItem('paid_id'))
      {
        var paid_id = localStorage.getItem('paid_id');
        var expenditure_id = localStorage.getItem('expenditure_id');
        var job_id = localStorage.getItem('job_id');
        var status = localStorage.getItem('status');
        var type = localStorage.getItem('type');
        var cheque_account = localStorage.getItem('cheque_account');

        if(type == 'cash')
        {
          $("#edit_cash").show();
          $("#edit_cheque").hide();
        }

        else
        {
          $("#edit_cheque").show();
          $("#edit_cash").hide();
        }

        $("#edit_paid_id").val(paid_id);
        $("#edit_expenditure_id").val(expenditure_id);
        $("#edit_cheque_account").val(cheque_account);
        $("#edit_status").val(status);
        $("#edit_type").val(type);
        $("#edit_job_id").val(job_id);
      }

      var activeTab = localStorage.getItem('activeTab');
    }

    if (activeTab) {
        $('a[href="' + activeTab + '"]').tab('show');
        console.log(activeTab);
    }

    var type = $("#type").val();

    if(type == 'cash')
    {
      $("#cash").show();
      $("#cheque").hide();
    }

    else
    {
      $("#cheque").show();
      $("#cash").hide();
    }


    $("#type").on('change', function() {
       var type = $(this).val();

       if(type == 'cash')
       {
         $("#cash").show();
         $("#cheque").hide();
       }

       else
       {
         $("#cheque").show();
         $("#cash").hide();
       }
    });

    $("#confirm_paid_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var reference_no = $("#reference_no").val();
      var date = $("#date").val();
      var supplier = $("#supplier").val();
      var description = $("#description").val();
      var expenditure_total = $("#expenditure_total").val();
      var outstanding_total = $("#outstanding_total").val();
      var amount = $("#amount").val();
      var type = $("#type").val();

      var voucher_no = $("#cash_voucher_no").val();
      var payee = $("#cash_payee").val();
      var transaction_date = $("#transaction_date").val();
      var cash_amount = $("#cash_amount").val();

      var cheque_no = $("#cheque_no").val();
      var cheque_account = $("#cheque_account").val();
      var cheque_receipt = $("#cheque_receipt").val();
      var issuing_banking = $("#issuing_banking").val();
      var cheque_from = $("#cheque_from").val();
      var cheque_amount = $("#cheque_amount").val();
      var cheque_date = $("#cheque_date").val();
      var currency = $("#currency").val();
      var cash_date = $("#cash_date").val();

      var gl_description = $("#gl_description").val();
      var remark = $("#remark").val();

      if ($.trim(reference_no).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Reference No field is empty."
      }

      if ($.trim(date).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Date field is empty."
      }

      if ($.trim(supplier).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Supplier field is empty."
      }

      if ($.trim(description).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Description field is empty."
      }

      if ($.trim(expenditure_total).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Expenditure Total field is empty."
      }

      if ($.trim(outstanding_total).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Outstanding Total field is empty."
      }

      if ($.trim(amount).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Amount field is empty."
      }

      if(type == 'cash')
      {
        if ($.trim(voucher_no).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Voucher No field is empty."
        }

        if ($.trim(transaction_date).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Transaction Date field is empty."
        }

        if ($.trim(cash_amount).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cash Amount field is empty."
        }

        if ($.trim(payee).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Payee field is empty."
        }
      }

      if(type == 'cheque')
      {
        if ($.trim(cheque_no).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque No field is empty."
        }

        if ($.trim(cheque_account).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque Account field is empty."
        }

        if ($.trim(cheque_receipt).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque Receipt field is empty."
        }

        if ($.trim(issuing_banking).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Issuing Banking field is empty."
        }

        if ($.trim(cheque_from).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque From field is empty."
        }

        if ($.trim(cheque_amount).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque Amount field is empty."
        }

        if ($.trim(cheque_date).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cheque Date field is empty."
        }

        if ($.trim(cash_date).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Cash Date field is empty."
        }
      }

      if ($.trim(gl_description).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Gl Description field is empty."
      }

      if ($.trim(remark).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Remark field is empty."
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
