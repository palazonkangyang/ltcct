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
                       						<th width="10%">Date</th>
                                  <th width="30%">Type Name</th>
                                  <th width="10%">Cash</th>
                                  <th width="10%">Cheque</th>
                                  <th width="10%">Nets</th>
                                  <th width="10%">Amount</th>
                                  <th width="10%">Attended By</th>
                       					</tr>
                       				</thead>

                              <tbody>
                                @foreach($result as $data)
                                <tr>
                                  <td>{{ $date }}</td>
                                  <td>{{ $data->type_name }}</td>
                                  @if(isset($data->cash) && $data->cash != 0.0)
                                  <td>{{ number_format($data->cash, 2) }}</td>
                                  @else
                                  <td></td>
                                  @endif
                                  @if(isset($data->cheque) && $data->cheque != 0.0)
                                  <td>{{ number_format($data->cheque, 2) }}</td>
                                  @else
                                  <td></td>
                                  @endif
                                  @if(isset($data->nets) && $data->nets != 0.0)
                                  <td>{{ number_format($data->nets, 2) }}</td>
                                  @else
                                  <td></td>
                                  @endif
                                  @if($data->amount != 0)
                                  <td>{{ number_format($data->amount, 2) }}</td>
                                  @else
                                  <td></td>
                                  @endif
                                  @if($data->amount != 0)
                                  <td>{{ $attendedby }}</td>
                                  @else
                                  <td></td>
                                  @endif
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div><!-- end col-md-6 -->

                          <div class="col-md-6" style="font-size: 13px;">

                            <p>
                              Total for all cash Voucher : SGD {{ number_format($total_cash, 2) }} <br />
                              Total for all cheque Voucher : SGD {{ number_format($total_cheque, 2) }} <br />
                              Total for all nets Voucher : SGD {{ number_format($total_nets, 2) }} <br />
                              Total for all Vouchers ({{ Carbon\Carbon::parse($todaydate)->format('d M Y ') }} - {{ Carbon\Carbon::parse($todaydate)->format('d M Y ') }}) : SGD {{ number_format($total_amount, 2) }} <br />
                            </p>

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
