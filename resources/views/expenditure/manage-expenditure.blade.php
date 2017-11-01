@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Expenditure</h1>

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
                  <span>Expenditure</span>
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
                            <a href="#tab_expenditurelist" data-toggle="tab">Expenditure Lists</a>
                          </li>
                          <li>
                            <a href="#tab_newexpenditure" data-toggle="tab">New Expenditure</a>
                          </li>
                          <li id="edit-expenditure" class="disabled">
                            <a href="#tab_editexpenditure" data-toggle="tab">Edit Expenditure</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_expenditurelist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="expenditure-table">
                                  <thead>
                                      <tr id="filter">
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
                                        <th>Supplier</th>
                                        <th>Description</th>
                                        <th>Credit Total</th>
                                        <th>Status</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                      @if(count($expenditure))

                                        @foreach($expenditure as $exp)
                                        <tr>
                                          <td><a href="#tab_editexpenditure" data-toggle="tab"
                                              class="edit-item" id="{{ $exp->expenditure_id }}">{{ $exp->reference_no }}</a>
                                          </td>
                                          <td>{{ \Carbon\Carbon::parse($exp->date)->format("d/m/Y") }}</td>
                                          <td>{{ $exp->vendor_name }}</td>
                                          <td>
                                            <span style="display: inline-block; width: 300px; overflow-wrap: break-word;">{{ $exp->description }}</span>
                                          </td>
                                          <td>S$ {{ $exp->credit_total }}</td>
                                          <td class="text-capitalize">{{ $exp->status }}</td>
                                        </tr>
                                        @endforeach

                                      @endif
                                  </tbody>
                                </table>
                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab-expenditurelist -->

                          <div class="tab-pane" id="tab_newexpenditure">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/expenditure/new-expenditure') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <label class="col-md-3">Date *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="date" value="{{ old('date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Vendor *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="supplier" value="{{ old('supplier') }}" id="supplier">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <input type="hidden" name="supplier_id" value="" id="supplier_id">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Description *</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" rows="3" id="description">{{ old('description') }}</textarea>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Glcode *</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="glcode_id" id="glcode_id">
                                          @foreach($glcode as $gl)
                                            <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                                          @endforeach
                                        </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Credit Total *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="credit_total" value="{{ old('credit_total') }}" id="credit_total">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Status *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="status">
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <p>&nbsp;</p>
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="confirm_expenditure_btn">Confirm
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

                          </div><!-- end tab-pane tab_newexpenditure -->

                          <div class="tab-pane" id="tab_editexpenditure">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/expenditure/update-expenditure') }}"
                                  class="form-horizontal form-bordered" id="expenditure-form">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <input type="hidden" name="edit_expenditure_id" id="edit_expenditure_id" value="">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Reference No *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_reference_no" value="{{ old('edit_reference_no') }}" id="edit_reference_no" readonly>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Date *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_date" value="{{ old('edit_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_date">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Vendor *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_supplier" value="{{ old('edit_supplier') }}" id="edit_supplier">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Description *</label>
                                    <div class="col-md-9">
                                      <textarea name="edit_description" class="form-control" rows="3" id="edit_description">{{ old('edit_description') }}</textarea>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Credit Total *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_credit_total" value="{{ old('edit_credit_total') }}" id="edit_credit_total">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3">Status *</label>
                                    <div class="col-md-9">
                                      <select class="form-control" name="edit_status" id="edit_status">
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                      </select>
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <p>&nbsp;</p>
                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="update_expenditure_btn">Update
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

                          </div><!-- end tab-pane tab_editexpenditure -->

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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<script type="text/javascript">

  $(function() {

    var d = new Date();
    var strDate = d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear();

    $("#date").val(strDate);

    $("#supplier").autocomplete({
      source: "/expenditure/search/supplier",
      minLength: 1,
      select: function(event, ui) {
        $('#supplier').val(ui.item.value);
        $('#supplier_id').val(ui.item.id);
      }
    });

    $("#expenditure-table").on('click', '.delete-item', function() {
      if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
        return false;
      }
    });

    $("#credit_total").keypress(function (e) {
       //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          return false;
      }
    });

    $("#edit_credit_total").keypress(function (e) {
       //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          return false;
      }
    });

    // DataTable
    var table = $('#expenditure-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
      "columnDefs": [
        { "width": "15%", "targets": 0 },
        { "width": "15%", "targets": 1 },
        { "width": "20%", "targets": 2 },
        { "width": "20%", "targets": 3 },
        { "width": "15%", "targets": 4 },
        { "width": "15%", "targets": 5 }
      ]
    });

    $('#expenditure-table thead tr#filter th').each( function () {
      var title = $('#expenditure-table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#expenditure-table thead input").on( 'keyup change', function () {
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

    // $("#filter input[type=text]:last").css("display", "none");

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

        localStorage.removeItem('expenditure_id');
        localStorage.removeItem('status');
    }

    else
    {
      if(localStorage.getItem('expenditure_id'))
      {
        var expenditure_id = localStorage.getItem('expenditure_id');
        var status = localStorage.getItem('status');
      }

      if(status == 'posted')
      {
        $('#expenditure-form input').attr('readonly', 'readonly');
        $('#expenditure-form textarea').attr('readonly', 'readonly');
        $('#expenditure-form select').attr('disabled', true);
        $('#expenditure-form #update_expenditure_btn').attr('disabled', true);
      }

      $("#edit_expenditure_id").val(expenditure_id);
      $("#edit_status").val(status);

      var activeTab = localStorage.getItem('activeTab');
    }

    if (activeTab) {
        $('a[href="' + activeTab + '"]').tab('show');
        console.log(activeTab);
    }

    $("#expenditure-table").on('click','.edit-item',function(e) {

      $(".alert-success").remove();

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#edit-expenditure").addClass("active");

      var expenditure_id = $(this).attr("id");

      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          expenditure_id: expenditure_id
      };

      $.ajax({
          type: 'GET',
          url: "/expenditure/expenditure-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            localStorage.setItem('expenditure_id', response.expenditure[0]['expenditure_id']);
            localStorage.setItem('status', response.expenditure[0]['status']);

            if(localStorage.getItem('expenditure_id'))
            {
              var expenditure_id = localStorage.getItem('expenditure_id');
              var status = localStorage.getItem('status');
            }

            if(status == 'posted')
            {
              $('#expenditure-form input').attr('readonly', 'readonly');
              $('#expenditure-form textarea').attr('readonly', 'readonly');
              $('#expenditure-form select').attr('disabled', true);
              $('#expenditure-form #update_expenditure_btn').attr('disabled', true);
            }

            else {
              $("#edit_expenditure_id").attr('readonly', 'readonly');
              $("#edit_date").removeAttr('readonly', 'readonly');
              $("#edit_supplier").removeAttr('readonly', 'readonly');
              $("#edit_description").removeAttr('readonly', 'readonly');
              $("#edit_credit_total").removeAttr('readonly', 'readonly');
              $('#expenditure-form textarea').removeAttr('readonly', 'readonly');
              $('#expenditure-form select').attr('disabled', false);
              $("#edit_authorized_password").removeAttr('readonly', 'readonly');
              $('#expenditure-form #update_expenditure_btn').attr('disabled', false);
            }

            $("#edit_expenditure_id").val(expenditure_id);
            $("#edit_reference_no").val(response.expenditure[0]['reference_no']);
            $("#edit_date").val(response.expenditure[0]['date']);
            $("#edit_supplier").val(response.expenditure[0]['supplier']);
            $("#edit_description").val(response.expenditure[0]['description']);
            $("#edit_credit_total").val(response.expenditure[0]['credit_total']);
            $("#edit_status").val(status);
          },

          error: function (response) {
              console.log(response);
          }
      });

    });

    $("#confirm_expenditure_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var reference_no = $("#reference_no").val();
      var date = $("#date").val();
      var supplier = $("#supplier").val();
      var description = $("#description").val();
      var credit_total = $("#credit_total").val();

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

      if ($.trim(credit_total).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Credit Total field is empty."
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

    $("#update_expenditure_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var date = $("#edit_date").val();
      var supplier = $("#edit_supplier").val();
      var description = $("#edit_description").val();
      var credit_total = $("#edit_credit_total").val();

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

      if ($.trim(credit_total).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Credit Total field is empty."
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
