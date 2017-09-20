@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Trial Balance Report</h1>

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
                  <span>Trial Balance Report</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="portlet-body">

                      <div class="form-body">

                        <h4 class="text-center" style="font-weight: bold;">
                          Trial Balance Report
                        </h4><br />

                        <div class="col-md-12">
                          <button type="button" class="btn blue print-btn">Print</button>
                        </div><!-- end col-md-12 -->

                        <div class="col-md-12">
                          <table border="1" class="table table-bordered table-striped" id="trialbalance-table">
                            <thead>
                     					<tr>
                                <th width="4%">Account</th>
                     						<th width="5%">Description</th>
                                <th width="3%">Debit</th>
                                <th width="3%">Credit</th>
                     					</tr>
                     				</thead>

                            <tbody>
                              @for($i = 0; $i < count($expenses); $i++)
                              <tr>
                                <td>{{ $expenses[$i]->accountcode }}</td>
                                <td>{{ $expenses[$i]->type_name }}</td>
                                @if(isset($expenses[$i]->total))
                                <td>{{ number_format($expenses[$i]->total, 2) }}</td>
                                @else
                                <td>{{ number_format(0, 2) }}</td>
                                @endif
                                <td></td>
                              </tr>
                              @endfor

                              @for($i = 0; $i < count($income); $i++)
                              <tr>
                                <td>{{ $income[$i]->accountcode }}</td>
                                <td>{{ $income[$i]->type_name }}</td>
                                <td></td>
                                @if(isset($income[$i]->total))
                                <td>{{ number_format($income[$i]->total, 2) }}</td>
                                @else
                                <td>{{ number_format(0, 2) }}</td>
                                @endif
                              </tr>
                              @endfor
                            </tbody>
                          </table>
                        </div><!-- end col-md-12 -->

                        <div class="clearfix">
                        </div><!-- end clearfix -->

                      </div><!-- end form-body -->

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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>

<script type="text/javascript">

  $(function(){

    function printData()
    {
      var divToPrint=document.getElementById("trialbalance-table");
      newWin= window.open("");
      newWin.document.write(divToPrint.outerHTML);
      newWin.print();
      newWin.close();
    }

    $('button').on('click',function(){
      printData();
    });

  });

</script>

@stop
