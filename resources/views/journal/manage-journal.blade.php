@extends('layouts.backend.app')

@section('main-content')

<div class="page-container-fluid">

  <div class="page-content-wrapper">

    <div class="page-head">

      <div class="container-fluid">

        <div class="page-title">
          <h1>Journal</h1>
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
            <span>Journal</span>
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
                          <a href="#tab_journallist" data-toggle="tab">Journal List</a>
                        </li>
                        <li id="journaldetail" class="disabled">
                          <a href="#tab_journaldetail" data-toggle="tab">Journal Detail</a>
                        </li>
                      </ul>

                      <div class="tab-content">

                        <div class="tab-pane active" id="tab_journallist">

                          <div class="form-body">

                            <div class="form-group">

                              <table class="table table-bordered" id="journal-table">
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
                                    <th>Journal No</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Paid By</th>
                                    <th>Devotee ID</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @foreach($journal as $j)
                                  <tr>
                                    <td><a href="#tab_journaldetail" data-toggle="tab"
                                      class="edit-item" id="{{ $j->journalentry_id }}">{{ $j->journalentry_no }}</a></td>
                                      <td>{{ \Carbon\Carbon::parse($j->date)->format("d/m/Y") }}</td>
                                      <td>{{ $j->description }}</td>
                                      <td>{{ $j->paidby }}</td>
                                      <td>{{ $j->devotee_id }}</td>
                                      <td>S$ {{ number_format($j->total_debit_amount, 2) }}</td>
                                      <td>S$ {{ number_format($j->total_debit_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>

                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_journallist -->

                          <div class="tab-pane" id="tab_journaldetail">

                            <div class="form-body">

                              <div class="col-md-12">

                                <div class="form-group">
                                  <label class="col-md-2">Journal No</label>
                                  <div class="col-md-3">
                                    <input type="text" class="form-control" id="journal_no" readonly>
                                  </div><!-- end col-md-3 -->
                                </div><!-- end form-group -->

                                <div class="form-group">
                                  <label class="col-md-2">Paid By</label>
                                  <div class="col-md-3">
                                    <input type="text" class="form-control" id="show_paidby" readonly>
                                  </div><!-- end col-md-3 -->
                                </div><!-- end form-group -->

                                <div class="form-group">
                                  <label class="col-md-2">Devotee ID</label>
                                  <div class="col-md-3">
                                    <input type="text" class="form-control" id="show_devotee_id" readonly>
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

                                <table class="table table-bordered" id="journal-detail-table">
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

                                <div class="form-group">
                                  <p>&nbsp;</p>
                                </div><!-- end form-group -->

                              </div><!-- end col-md-12 -->

                            </div><!-- end form-body -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_journaldetail -->

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

    // Disabled Edit Tab
    $(".nav-tabs > li").click(function(){
      if($(this).hasClass("disabled"))
      return false;
    });

    // DataTable
    var table = $('#journal-table').removeAttr('width').DataTable( {
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
      "order": [[ 0, "desc" ]],
      columnDefs: [
        { "width": "200px", "targets": 0 },
        { "width": "200px", "targets": 1 },
        { "width": "300px", "targets": 2 },
        { "width": "200px", "targets": 3 },
        { "width": "200px", "targets": 4 },
        { "width": "200px", "targets": 5 },
        { "width": "200px", "targets": 6 }
      ],
      fixedColumns: true
    });

    $('#journal-table thead tr#filter th').each( function () {
      var title = $('#journal-table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#journal-table thead input").on( 'keyup change', function () {
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

    $("#journal-table").on('click','.edit-item',function(e) {

      $("#journal-detail-table tbody").find("tr:not(:last)").remove();

      $("#journal_no").val('');
      $("#show_paidby").val('');
      $("#show_devotee_id").val('');
      $("#show_date").val('');
      $("#show_description").val('');

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#journaldetail").addClass("active");

      var journalentry_id = $(this).attr("id");

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        journalentry_id: journalentry_id
      };

      $.ajax({
        type: 'GET',
        url: "/journal/journal-detail",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          $("#journal_no").val(response.journalentry[0]['journalentry_no']);
          $("#show_paidby").val(response.journalentry[0]['paidby']);
          $("#show_devotee_id").val(response.journalentry[0]['devotee_id']);
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
