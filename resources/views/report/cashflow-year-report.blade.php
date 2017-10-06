@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Cashflow Statement Report</h1>

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
                  <span>Cashflow Statement Report</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="portlet-body">

                      <div class="form-body">

                        <h4 class="text-center" style="font-weight: bold;">{{ $year }} Cashflow Statement Summary</h4><br />

                        <div class="col-md-12">
                          <button type="button" class="btn blue print-btn">Print</button>
                        </div><!-- end col-md-12 -->

                        <div class="col-md-12">
                          <table border="1" class="table table-bordered table-striped" id="cashflow-year-report-table">
                            <thead>
                     					<tr>
                     						<th width="28%"></th>
                                <th width="6%">JAN</th>
                                <th width="6%">FEB</th>
                                <th width="6%">MAR</th>
                                <th width="6%">APR</th>
                                <th width="6%">MAY</th>
                                <th width="6%">JUN</th>
                                <th width="6%">JULY</th>
                                <th width="6%">AUG</th>
                                <th width="6%">SEP</th>
                                <th width="6%">OCT</th>
                                <th width="6%">NOV</th>
                                <th width="6%">DEC</th>
                     					</tr>
                     				</thead>

                            <tbody>
                              <tr>
                                <td style="font-weight: bold; text-decoration: underline">Expense 收入</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              @for($i = 0; $i < count($expenses); $i++)
                              <tr>
                                <td>{{ $expenses[$i]->type_name }}</td>
                                <td>{{ number_format($expenses[$i]->Jan, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Feb, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Mar, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Apr, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->May, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Jun, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->July, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Aug, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Sep, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Oct, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->Nov, 2) }}</td>
                                <td>{{ number_format($expenses[$i]->December, 2) }}</td>
                              </tr>
                              @endfor

                              <tr>
                                <td style="font-weight: bold; text-decoration: underline">Beginning Cash On Hand</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              <tr>
                                <td>{{ $ocbc_account[0]->type_name }}</td>
                                <td>{{ number_format($ocbc_account[0]->Jan, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Feb, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Mar, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Apr, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->May, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Jun, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->July, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Aug, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Sep, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Oct, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->Nov, 2) }}</td>
                                <td>{{ number_format($ocbc_account[0]->December, 2) }}</td>
                              </tr>

                              <tr>
                                <td>{{ $ocbc_account2[0]->type_name }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Jan, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Feb, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Mar, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Apr, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->May, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Jun, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->July, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Aug, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Sep, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Oct, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->Nov, 2) }}</td>
                                <td>{{ number_format($ocbc_account2[0]->December, 2) }}</td>
                              </tr>

                              <tr>
                                <td>{{ $cash_on_hand[0]->type_name }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Jan, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Feb, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Mar, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Apr, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->May, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Jun, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->July, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Aug, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Sep, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Oct, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->Nov, 2) }}</td>
                                <td>{{ number_format($cash_on_hand[0]->December, 2) }}</td>
                              </tr>

                              <tr>
                                <td style="font-weight: bold; text-decoration: underline">General Donation 香油</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>

                              @for($i = 0; $i < count($entrance_fees); $i++)
                              <tr>
                                <td>{{ $entrance_fees[$i]->type_name }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Jan, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Feb, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Mar, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Apr, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->May, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Jun, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->July, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Aug, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Sep, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Oct, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->Nov, 2) }}</td>
                                <td>{{ number_format($entrance_fees[$i]->December, 2) }}</td>
                              </tr>
                              @endfor

                              @for($i = 0; $i < count($monthly_subscription); $i++)
                              <tr>
                                <td>{{ $monthly_subscription[$i]->type_name }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Jan, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Feb, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Mar, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Apr, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->May, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Jun, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->July, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Aug, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Sep, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Oct, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->Nov, 2) }}</td>
                                <td>{{ number_format($monthly_subscription[$i]->December, 2) }}</td>
                              </tr>
                              @endfor

                              @for($i = 0; $i < count($donation_non_members); $i++)
                              <tr>
                                <td>{{ $donation_non_members[$i]->type_name }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Jan, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Feb, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Mar, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Apr, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->May, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Jun, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->July, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Aug, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Sep, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Oct, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->Nov, 2) }}</td>
                                <td>{{ number_format($donation_non_members[$i]->December, 2) }}</td>
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

  $(function() {

    function printData()
    {
      var divToPrint=document.getElementById("cashflow-year-report-table");
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
