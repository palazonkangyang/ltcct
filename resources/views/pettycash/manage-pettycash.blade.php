@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Petty Cash Voucher</h1>

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
                  <span>Petty Cash Voucher</span>
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
                                    </tr>
                                    <tr>
                                      <th>Reference No</th>
                                      <th>Date</th>
                                      <th>Expenditure No</th>
                                      <th>Cash Amount</th>
                                      <th>Payee</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    @foreach($pettycash as $data)
                                    <tr>
                                      <td>{{ $data->reference_no }}</td>
                                      <td>{{ \Carbon\Carbon::parse($data->date)->format("d/m/Y") }}</td>
                                      <td>{{ $data->expenditure_reference_no }}</td>
                                      <td>S$ {{ $data->cash_amount }}</td>
                                      <td>{{ $data->cash_payee }}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>

                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_pettycashlist -->

                          <div class="tab-pane" id="tab_newpettycash">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/pettycash/new-pettycash') }}"
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
                                    <input type="hidden" value="{{ $glcode[0] }}" id="cash_in_hand">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Cash Amount *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="cash_amount" value="{{ old('cash_amount') }}" id="cash_amount">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Payee *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="cash_payee" value="{{ old('cash_payee') }}" id="cash_payee">
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

                          </div><!-- end tab-pane tab_newpettycash -->

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

    $("#cash_amount").keypress(function (e) {
       //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          return false;
      }
    });

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
      console.log(activeTab);
    }

    // DataTable
    var table = $('#pettycash-table').removeAttr('width').DataTable( {
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
        columnDefs: [
          { "width": "150px", "targets": 0 },
          { "width": "150px", "targets": 1 },
          { "width": "200px", "targets": 2 },
          { "width": "200px", "targets": 3 },
          { "width": "200px", "targets": 4 }
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
      var cash_amount = $("#cash_amount").val();
      var cash_in_hand = $("#cash_in_hand").val();
      var cash_payee = $("#cash_payee").val();
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

      if(parseInt(outstanding_total) < parseInt(cash_amount))
      {
        validationFailed = true;
        errors[count++] = "Cash Amount should not be greater than Outstanding Total.";
      }

      if ($.trim(cash_amount).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Cash Amount field is empty.";
      }

      if(parseInt(cash_in_hand) < parseInt(cash_amount))
      {
        validationFailed = true;
        errors[count++] = "Cash In Hand Amount is insufficient.";
      }

      if ($.trim(cash_payee).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Payee field is empty.";
      }

      if ($.trim(job_id).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Job field is empty.";
      }

      if ($.trim(authorized_password).length <= 0)
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
