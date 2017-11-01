@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Settlement Report</h1>

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
                  <span>Settlement Report</span>
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
                          Settlement Statement <br />
                        </h4><br />

                        <div class="col-md-12">
                          <button type="button" class="btn blue print-btn">Print</button>
                        </div><!-- end col-md-12 -->

                        <div class="col-md-12" id="cashflow-report">

                          <div class="col-md-12">
                            <table border="1" class="table table-bordered table-striped" id="cashflow-monthly-report">
                              <thead>
                                <tr>
                                  <th width="10%">Receipt</th>
                                  <th width="10%">Manual Receipt</th>
                                  <th width="10%">Date</th>
                                  <th width="20%">Item Code</th>
                                  <th width="15%">Customer</th>
                                  <th width="10%">Paid</th>
                                  <th width="10%">Method</th>
                                  <th width="15%">Attended By</th>
                       					</tr>
                       				</thead>

                              <tbody>

                                @if(count($result) > 0)

                                  @for($i = 0; $i < count($result); $i++)

                                  <tr>
                                    <td>{{ $result[$i]->xy_receipt }}</td>
                                    <td>{{ $result[$i]->manualreceipt }}</td>
                                    <td>{{ $result[$i]->trans_date }}</td>
                                    <td>{{ $result[$i]->type_name }}</td>
                                    <td>{{ $result[$i]->chinese_name }}</td>
                                    <td>{{ number_format($result[$i]->amount, 2) }}</td>
                                    <td>{{ $result[$i]->mode_payment }}</td>
                                    <td>{{ $result[$i]->user_name }}</td>
                                  </tr>

                                  @endfor

                                @else

                                <tr>
                                  <td colspan="8">No Result Found!</td>
                                </tr>

                                @endif
                              </tbody>
                            </table>
                          </div><!-- end col-md-6 -->

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

<script src="{{asset('js/custom/common.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
      var divToPrint=document.getElementById("cashflow-report");
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
