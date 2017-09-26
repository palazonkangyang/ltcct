@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Income</h1>

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
                  <span>Income</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="validation-error">
                    </div><!-- end validation-error -->

                    <div class="portlet-body">

                      <div class="form-body">

                        <div class="col-md-12">

                          <table class="table table-striped table-bordered" id="income-table">
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
                                <th>XY Receipt</th>
                                <th>Trans Date</th>
                                <th>Transaction</th>
                                <th>Description</th>
                                <th>Paid By</th>
                                <th>Devotee ID</th>
                                <th>HJ/ GR</th>
                                <th>Amount</th>
                                <th>Manual Receipt</th>
                              </tr>
                            </thead>

                            <tbody>
                                @foreach($receipts as $receipt)
                                <tr>
                                  @if(isset($receipt->cancelled_date))
                                  <td class="text-danger">{{ $receipt->receipt_no }}</td>
                                  @else
                                  <td>{{ $receipt->receipt_no }}</td>
                                  @endif
                                  <td>{{ \Carbon\Carbon::parse($receipt->trans_at)->format("d/m/Y") }}</td>
                                  <td>{{ $receipt->trans_no }}</td>
                                  <td>{{ $receipt->description }}</td>
                                  <td>{{ $receipt->chinese_name }}</td>
                                  <td>{{ $receipt->focusdevotee_id }}</td>
                                  <td>
                                    @if($receipt->hjgr == "hj")
                                    合家
                                    @else
                                    个人
                                    @endif
                                  </td>
                                  <td>{{ $receipt->total_amount }}</td>
                                  <td>{{ $receipt->manualreceipt }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>

                        </div><!-- end col-md-12 -->
                      </div><!-- end form-body -->

                      <div class="clearfix">
                      </div><!-- end clearfix -->

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

    // DataTable
    var table = $('#income-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
      "order": [[ 1, "desc" ]],
      dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
       "<'row'<'col-sm-12'tr>>" +
       "<'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    $('#income-table thead tr#filter th').each( function () {
      var title = $('#income-table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#income-table thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value, true, false )
            .draw();
    });

    function stopPropagation(evt) {
      if (evt.stopPropagation !== undefined) {
        evt.stopPropagation();
      } else {
        evt.cancelBubble = true;
      }
    }

    var path = window.location.pathname;

		$('.navbar-nav li a').each(function() {
	    if ($(this).attr('href') == path) {

				$(this).parent().addClass('active');
				$(this).closest(".mega-menu-dropdown" ).addClass('active');
	    }
   });

   $("#income-table").on('click', '.transaction-id', function() {

     var trans_no = $(this).attr("id");

     localStorage.setItem('transno', trans_no);
     localStorage.setItem('data', 1);

     window.location.href = "http://" + location.host + "/staff/donation#tab_transactiondetail";
     var hash = document.location.hash;

     if (hash) {
      $('.nav-tabs a[href="'+hash+'"]').tab('show');
     }

   });

  });
</script>

@stop
