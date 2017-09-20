@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>Income Statement Report</h1>

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
                  <span>Income Statement Report</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="portlet-body">

                      <div class="form-body">

                        <h4 class="text-center" style="font-weight: bold;">{{ $year }} Income Statement Summary</h4><br />

                        <div class="col-md-12">
                          <button type="button" class="btn blue print-btn">Print</button>
                        </div><!-- end col-md-12 -->

                        <div class="col-md-12">
                          <table border="1" class="table table-bordered table-striped" id="income-year-report">
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
                                <td style="font-weight: bold; text-decoration: underline">REVENUE 收入</td>
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

                              @for($i = 0; $i < count($income); $i++)

                              @if($income[$i]->type_name != 'Donation(non-member) 香油-非會員' && $income[$i]->type_name != 'Donation(Member) 香油-會員')
                              <tr>
                                <td>{{ $income[$i]->type_name }}</td>
                                <td>{{ number_format($income[$i]->Jan, 2) }}</td>
                                <td>{{ number_format($income[$i]->Feb, 2) }}</td>
                                <td>{{ number_format($income[$i]->Mar, 2) }}</td>
                                <td>{{ number_format($income[$i]->Apr, 2) }}</td>
                                <td>{{ number_format($income[$i]->May, 2) }}</td>
                                <td>{{ number_format($income[$i]->Jun, 2) }}</td>
                                <td>{{ number_format($income[$i]->July, 2) }}</td>
                                <td>{{ number_format($income[$i]->Aug, 2) }}</td>
                                <td>{{ number_format($income[$i]->Sep, 2) }}</td>
                                <td>{{ number_format($income[$i]->Oct, 2) }}</td>
                                <td>{{ number_format($income[$i]->Nov, 2) }}</td>
                                <td>{{ number_format($income[$i]->December, 2) }}</td>
                              </tr>
                              @endif

                              @endfor

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

                              <tr>
                                <td>Donation (Member) 香油-會員</td>
                                <td>{{ number_format($donation_members[0]->Jan, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Feb, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Mar, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Apr, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->May, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Jun, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->July, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Aug, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Sep, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Oct, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->Nov, 2) }}</td>
                                <td>{{ number_format($donation_members[0]->December, 2) }}</td>
                              </tr>
                              <tr>
                                <td>Donation (non-member) 香油-非會員</td>
                                <td>{{ number_format($donation_non_members[0]->Jan, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Feb, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Mar, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Apr, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->May, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Jun, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->July, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Aug, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Sep, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Oct, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->Nov, 2) }}</td>
                                <td>{{ number_format($donation_non_members[0]->December, 2) }}</td>
                              </tr>
                              <tr>
                                <td>Total</td>
                                <td>{{ number_format($total_generaldonation[0]->Jan, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Feb, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Mar, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Apr, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->May, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Jun, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->July, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Aug, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Sep, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Oct, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->Nov, 2) }}</td>
                                <td>{{ number_format($total_generaldonation[0]->December, 2) }}</td>
                              </tr>
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

  $(function() {

    function printData()
    {
      var divToPrint=document.getElementById("income-year-report");
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
