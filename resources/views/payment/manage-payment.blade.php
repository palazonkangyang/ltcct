@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Payment Voucher</h1>

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
                  <span>Payment Voucher</span>
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
                            <a href="#tab_paymentlist" data-toggle="tab">Payment List</a>
                          </li>
                          <li>
                            <a href="#tab_newpayment" data-toggle="tab">New Payment</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_paymentlist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="payment-table">
                                  <thead>
                                    <tr id="filter">
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    <tr>
                                      <th>Reference No</th>
                                      <th>Date</th>
                                      <th>Expenditure No</th>
                                      <th>Expenditure Total</th>
                                      <th>Cheque No</th>
                                      <th>Cheque Account</th>
                                      <th>Issuing Banking</th>
                                      <th>Cheque From</th>
                                      <th>Cheque Amount</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    @foreach($payment_voucher as $pv)
                                      <tr>
                                        <td>{{ $pv->reference_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($pv->date)->format("d/m/Y") }}</td>
                                        <td>{{ $pv->expenditure_reference_no }}</td>
                                        <td>{{ $pv->expenditure_total }}</td>
                                        <td>{{ $pv->cheque_no }}</td>
                                        <td>{{ $pv->cheque_account }}</td>
                                        <td>{{ $pv->issuing_banking }}</td>
                                        <td>{{ $pv->cheque_from }}</td>
                                        <td>{{ $pv->cheque_amount }}</td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>

                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_paymentlist -->

                          <div class="tab-pane" id="tab_newpayment">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/payment/new-payment') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide='datepicker' data-date-format='dd/mm/yyyy' id="date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure No *</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="expenditure_id" id="expenditure_id">
                                          <option value="">Please Select</option>
                                          @foreach($expenditure as $e)

                                          @if($e->outstanding_total != 0)
                                          <option value="{{ $e->expenditure_id }}">{{ $e->reference_no }}</option>
                                          @endif

                                          @endforeach
                                        </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Supplier</label>
                                    <div class="col-md-9">
                                      <input type="hidden" name="supplier_id" value="" id="supplier_id">
                                      <input type="text" class="form-control" name="supplier_name" value="{{ old('supplier_name') }}" id="supplier_name" readonly>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="description" value="{{ old('description') }}" id="description">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Expenditure Total</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="expenditure_total" value="{{ old('expenditure_total') }}" id="expenditure_total" readonly>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Outstanding Total</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="outstanding_total" value="{{ old('outstanding_total') }}" id="outstanding_total" readonly>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Cheque No *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="cheque_no" value="{{ old('cheque_no') }}" id="cheque_no">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Cheque Account *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="cheque_account" id="cheque_account">
                                        <option value="">Please Select</option>
                                        <option value="7">OCBC A/C NO. 665700217001 华侨银行第一户</option>
                                        <option value="8">OCBC A/C NO. 665700225001 华侨银行第二户</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <input type="hidden" value="" id="bank_amount">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Issuing Banking</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="issuing_banking" value="{{ old('issuing_banking') }}" id="issuing_banking">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Cheque From</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="cheque_from" value="{{ old('cheque_from') }}" id="cheque_from">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Cheque Amount *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="cheque_amount" value="{{ old('cheque_amount') }}" id="cheque_amount">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Job</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="job_id" id="job_id">
                                        <option value="">Please Select</option>
                                        @foreach($job as $j)
                                        <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
                                        @endforeach
                                      </select>
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

                                  <div class="clearfix">
                                  </div><!-- end clearfix -->

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
                                        <input type="password" class="form-control" name="authorized_password" id="authorized_password" autocomplete="new-password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-4 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="confirm_btn">Confirm
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

                          </div><!-- end tab-pane tab_newpayment -->

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

    var d = new Date();
    var strDate = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();

    $("#date").val(strDate);

    $("#cheque_amount").keypress(function (e) {
       //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          return false;
      }
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
      console.log(activeTab);
    }

    // DataTable
    var table = $('#payment-table').removeAttr('width').DataTable( {
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
        columnDefs: [
          { "width": "90px", "targets": 0 },
          { "width": "100px", "targets": 1 },
          { "width": "100px", "targets": 2 },
          { "width": "120px", "targets": 3 },
          { "width": "90px", "targets": 4 },
          { "width": "110px", "targets": 5 },
          { "width": "100px", "targets": 6 },
          { "width": "100px", "targets": 7 },
          { "width": "100px", "targets": 8 }
        ]
    } );

    $('#payment-table thead tr#filter th').each( function () {
      var title = $('#payment-table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#payment-table thead input").on( 'keyup change', function () {
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

    $("#cheque_account").change(function() {
      var glcode_id = $(this).val();

      if(glcode_id)
      {
        var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          glcode_id: glcode_id
        };

        $.ajax({
            type: 'GET',
            url: "/payment/get-balance",
            data: formData,
            dataType: 'json',
            success: function(response)
            {
              $("#bank_amount").val(response.glcode['balance']);
            },

            error: function (response) {
              console.log(response);
            }
        });
      }
      else
      {
        $("#bank_amount").val('');
      }
    });

    $("#expenditure_id").change(function() {
      var expenditure_id = $(this).val();

      if(expenditure_id)
      {
        var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          expenditure_id: expenditure_id
        };

        $.ajax({
            type: 'GET',
            url: "/pettycash/supplier-name",
            data: formData,
            dataType: 'json',
            success: function(response)
            {
              $("#supplier_id").val(response.supplier['ap_vendor_id']);
              $("#supplier_name").val(response.supplier['vendor_name']);

              $("#expenditure_total").val(response.expenditure['credit_total']);
              $("#outstanding_total").val(response.outstanding_total);
            },

            error: function (response) {
              console.log(response);
            }
        });
      }
      else
      {
        $("#supplier_id").val('');
        $("#supplier_name").val('');

        $("#expenditure_total").val('');
        $("#outstanding_total").val('');
      }
    });

    $("#confirm_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var date = $("#date").val();
      var expenditure_id = $("#expenditure_id").val();
      var outstanding_total = $("#outstanding_total").val();
      var cheque_no = $("#cheque_no").val();
      var cheque_account = $("#cheque_account").val();
      var issuing_banking= $("#issuing_banking").val();
      var cheque_from = $("#cheque_from").val();
      var cheque_amount = $("#cheque_amount").val();
      var job_id = $("#job_id").val();
      var authorized_password = $("#authorized_password").val();

      if ($.trim(date).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Date field is empty.";
      }

      if ($.trim(expenditure_id).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Expenditure No field is empty.";
      }

      if(parseInt(outstanding_total) < parseInt(cheque_amount))
      {
        validationFailed = true;
        errors[count++] = "Cheque Amount should not be greater than Outstanding Total.";
      }

      if($.trim(cheque_no).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Cheque No field is empty.";
      }

      if($.trim(cheque_account).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Cheque Account field is empty.";
      }

      if($.trim(job_id).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Job field is empty.";
      }

      if($.trim(authorized_password).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Unauthorised User Access !! Changes will NOT be Saved !! Please re-enter Authorised User Access to save Changes !!";
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
