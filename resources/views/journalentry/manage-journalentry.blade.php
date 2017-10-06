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
                          <!-- <li id="edit-journalentry" class="disabled">
                            <a href="#tab_editjournalentry" data-toggle="tab">Edit Journal Entry</a>
                          </li> -->
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
                                      <td>{{ $j->journalentry_no }}</td>
                                      <td>{{ \Carbon\Carbon::parse($j->date)->format("d/m/Y") }}</td>
                                      <td>{{ $j->description }}</td>
                                      <td>{{ $j->total_debit_amount }}</td>
                                      <td>{{ $j->total_credit_amount }}</td>
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

                                  <div class="form-group" style="margin-bottom: 30px;">
                                    <label class="col-md-1 control-label">Date *</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group" style="margin-bottom: 30px;">
                                    <label class="col-md-1 control-label">Description</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}" id="description">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <table class="table table-bordered" id="new-journalentry-table">
                                    <thead>
                                      <tr>
                                        <th width="2%">#</th>
                                        <th width="30%">Journal Entry</th>
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
                                        <td>
                                          <input type="hidden" value="" class="balance">
                                        </td>
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
                                    <input type="hidden" name="total_debit_amount" value="" id="total_debit_amount">
                                    <input type="hidden" name="total_credit_amount" value="" id="total_credit_amount">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <button type="button" class="btn green" style="width: 100px; margin: 0 25px 0 10px;" id="addRow">Add New
                                    </button>
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <div class="col-md-6">
                                      <p>
                                        If you have made Changes to the above. You need to CONFIRM to save the Changes.
                                        To Confirm, please enter authorized password to proceed.
                                      </p>
                                    </div><!-- end col-md-6 -->

                                    <div class="col-md-2">
                                    </div><!-- end col-md-2 -->

                                    <div class="col-md-4">
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
                                      <td>
                                        <input type="hidden" value="" class="balance">
                                      </td>
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
                                        <input type="password" class="form-control" name="edit_authorized_password" id="edit_authorized_password" autocomplete="new-password">
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

<!-- <script src="{{asset('js/custom/common.js')}}"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
  $(function() {

    $("body").delegate('#glcode_id', 'change', function() {

      var glcode_id = $(this).val();

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        glcode_id: glcode_id
      };

      $.ajax({
          type: 'GET',
          url: "/journalentry/get-balance",
          data: formData,
          context: this,
          dataType: 'json',
          success: function(response)
          {
            $(this).closest("tr").find(".balance").val(response.glcode['balance']);

            var debit_amount = $(this).closest("tr").find(".debit_amount").val();
            var credit_amount = $(this).closest("tr").find(".credit_amount").val();
            var balance = $(this).closest("tr").find(".balance").val();

            if(parseInt(balance) < parseInt(debit_amount))
            {
              $(this).closest("tr").find(".debit_amount").parent().addClass('has-error');
            }

            else
            {
              $(this).closest("tr").find(".debit_amount").parent().removeClass('has-error');
            }

            if(parseInt(balance) < parseInt(credit_amount))
            {
              $(this).closest("tr").find(".credit_amount").parent().addClass('has-error');
            }

            else
            {
              $(this).closest("tr").find(".credit_amount").parent().removeClass('has-error');
            }
          },

          error: function (response) {
            console.log(response);
          }
      });

    });

    $("body").delegate('.debit_amount', 'change', function() {

      var debit_amount = $(this).val();
      var balance = $(this).closest("tr").find(".balance").val();

      if(parseInt(balance) < parseInt(debit_amount))
      {
        $(this).parent().addClass('has-error');
      }

      else
      {
        $(this).parent().removeClass('has-error');
      }
    });

    $("body").delegate('.credit_amount', 'change', function() {

      var credit_amount = $(this).val();
      var balance = $(this).closest("tr").find(".balance").val();

      if(parseInt(balance) < parseInt(credit_amount))
      {
        $(this).parent().addClass('has-error');
      }

      else
      {
        $(this).parent().removeClass('has-error');
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

      else{
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
      var authorized_password = $("#authorized_password").val();

      if($.trim(date).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Date is empty.";
      }

      $("#new-journalentry-table select").each(function() {
        var $optText = $(this).find('option:selected');

        if ($optText.val() == "") {
          validationFailed = true;
          errors[count++] = "Journal Entry fields are empty.";
          return false;
        }
      });

      if (total_debit != total_credit)
      {
        validationFailed = true;
        errors[count++] = "Debit and Credit are not the same.";
      }

      $('#new-journalentry-table tr td.debit_amount_col').each(function () {

        if($(this).hasClass("has-error"))
        {
          validationFailed = true;
          errors[count++] = "Debit Amount are invalid.";
          return false;
        }
      });

      $('#new-journalentry-table tr td.credit_amount_col').each(function () {

        if($(this).hasClass("has-error"))
        {
          validationFailed = true;
          errors[count++] = "Credit Amount is invalid.";
          return false;
        }
      });

      if ($.trim(authorized_password).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.";
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

    // $("#filter input[type=text]:last").css("display", "none");

  });
</script>


@stop
