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

                        <h4 class="text-center" style="font-weight: bold;">
                          Income Statement <br />
                          Printed on: {{ $today }}<br />
                          Period : {{ $month }} {{ $year }}
                        </h4><br />

                        <div class="col-md-12">
                          <table class="table table-bordered table-striped" id="report-table">
                            <thead>
                     					<tr>
                     						<th width="5%"></th>
                                <th width="3%">Current Period</th>
                                <th width="6%">%</th>
                     					</tr>
                     				</thead>

                            <tbody>
                              <tr>
                                <td style="font-weight: bold; text-decoration: underline">General Donation 香油</td>
                                <td colspan="12"></td>
                              </tr>
                              <tr>
                                <td>Entrance Fee 會員基金</td>
                                <td>{{ number_format($entrance_fees[0]->$month, 2) }}</td>
                                <td></td>
                              </tr>
                              <tr>
                                <td>Monthly Subscription 月捐</td>
                                <td>{{ number_format($monthly_subscriptions[0]->$month, 2) }}</td>
                                <td></td>
                              </tr>
                              <tr>
                                <td>Donation(Others) 香油-其他</td>
                                <td>{{ number_format($donation_others[0]->$month, 2) }}</td>
                                <td></td>
                              </tr>


                              <tr>
                                <td style="font-weight: bold; text-decoration: underline">General Donation 香油</td>
                                <td colspan="12"></td>
                              </tr>
                              <tr>
                                <td>Donation (Member) 香油-會員</td>
                                <td>{{ number_format($donation_members[0]->$month, 2) }}</td>
                                <td></td>
                              </tr>
                              <tr>
                                <td>Donation (non-member) 香油-非會員</td>
                                <td>{{ number_format($donation_non_members[0]->$month, 2) }}</td>
                                <td></td>
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

@stop
