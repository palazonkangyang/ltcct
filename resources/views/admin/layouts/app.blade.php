<!DOCTYPE html>
<html>

@section('htmlheader')
    @include('admin.layouts.partials.head')
@show

<body class="page-container-bg-solid">

	<div class="page-wrapper">

		<div class="page-wrapper-row">

			<div class="page-wrapper-top">

				<div class="page-header">

					<div class="page-header-top">

						<div class="container">

							<div class="page-logo">
                                <!--<a href="/admin/dashboard">
                                    <img src="{{ URL::asset('/images/logo-small.jpg') }}" alt="logo" class="logo-default">
                                </a> -->
                            </div><!-- end page-logo -->

                            <!-- RESPONSIVE MENU TOGGLER -->
                            <a href="javascript:;" class="menu-toggler"></a>

                            <div class="top-menu">

                            	<ul class="nav navbar-nav pull-right">




                                    <!-- BEGIN INBOX DROPDOWN -->
                                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark" id="header_inbox_bar">


                                        <ul

                                            <li>

                                                <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">




                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>

                                    <li class="dropdown dropdown-user dropdown-dark">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <img alt="" class="img-circle" src="{{ URL::asset('/images/avatar9.jpg') }}">
                                            <span class="username username-hide-mobile">{{ Auth::user()->user_name }}</span>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-default">
                                          <li>
                                            <a href="#">
                                              <span style="display: inline-block; width: 80px;">User</span>
                                              {{ Auth::user()->user_name }}
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#">
                                              <span style="display: inline-block; width: 80px;">User Level</span>
                                              {{ Auth::user()->role }}
                                            </a>
                                          </li>
                                          @if(isset(Auth::user()->last_login))
                                          <li>
                                            <a href="#">
                                              <span style="display: inline-block; width: 80px;">Last Login</span>
                                              {{ \Carbon\Carbon::parse(Auth::user()->last_login)->format("d/m/Y H:i") }} hr
                                            </a>
                                          </li>
                                          @else
                                          <li>
                                            <a href="#"><span style="display: inline-block; width: 80px;">Last Login</span> -</a>
                                          </li>
                                          @endif
                                          <li id="logout">
                                              <a href="{{ URL::to('/auth/logout') }}"><i class="icon-key"></i> Log Out 登出 </a>
                                          </li>
                                        </ul>
                                   </li>

                            	</ul><!-- end nav navbar-nav pull-right -->

                            </div><!-- end top-menu -->

						</div><!-- end container -->

					</div><!-- end page-header-top -->

					<div class="page-header-menu">

						<div class="container-fluid">

              <div class="hor-menu">
                <ul class="nav navbar-nav">

                  @if(Auth::user()->role != 4)
                  <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                          <a href="/operator/index" class="hylink"> Main Page 主页
                              <span class="arrow"></span>
                          </a>
                      </li>

                      <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                          <a href="/staff/donation" class="nav-link hylink"> General Donation 乐捐/月捐
                              <span class="arrow"></span>
                          </a>
                      </li>

                      <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                          <a href="/staff/create-festive-event" class="hylink"> Event Calendar 庆典节目表
                              <span class="arrow"></span>
                          </a>
                      </li>
                      @endif

                      @if(Auth::user()->role != 3)

                      <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown">
                              <a href="javascript:;"> Fund Account
                                  <span class="arrow"></span>
                              </a>
                              <ul class="dropdown-menu" style="min-width: 710px">
                                  <li>
                                      <div class="mega-menu-content">
                                          <div class="row">
                                              <div class="col-md-4">
                                                  <ul class="mega-menu-submenu">
                                                      <li>
                                                        <h3>Income & Expenditure</h3>
                                                      </li>
                                                      <li>
                                                        <a href="#" class="hylink">Income</a>
                                                      </li>
                                                      <li>
                                                        <a href="/expenditure/manage-expenditure" class="hylink">Purchase/ Expenditure</a>
                                                      </li>
                                                      <li>
                                                        <a href="/vendor/manage-ap-vendor" class="hylink">AP Vendor</a>
                                                      </li>
                                                      <li>
                                                        <a href="/payment/manage-payment" class="hylink">Payment Voucher</a>
                                                      </li>
                                                      <li>
                                                        <a href="/pettycash/manage-pettycash" class="hylink">Petty Cash Voucher</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="col-md-4">
                                                  <ul class="mega-menu-submenu">
                                                      <li>
                                                          <h3>Setting</h3>
                                                      </li>
                                                      <li>
                                                          <a href="/income/income-lists" class="hylink">Fiscal Year</a>
                                                      </li>
                                                      <li>
                                                          <a href="/job/manage-job" class="hylink">Jobs</a>
                                                      </li>
                                                      <li>
                                                          <a href="#" class="hylink">Cost Center</a>
                                                      </li>
                                                      <li>
                                                          <a href="/account/new-glaccountgroup" class="hylink">GL Account Group</a>
                                                      </li>
                                                      <li>
                                                          <a href="/account/new-glaccount" class="hylink">GL Accounts</a>
                                                      </li>
                                                      <li>
                                                          <a href="/account/chart-all-accounts" class="hylink">Chart All Accounts</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="col-md-4">
                                                  <ul class="mega-menu-submenu">
                                                      <li>
                                                          <h3>Finance</h3>
                                                      </li>
                                                      <li>
                                                          <a href="/journalentry/manage-journalentry" class="hylink">Journal Entry</a>
                                                      </li>
                                                      <li>
                                                          <a href="/report/income-report" class="hylink">Income Statement Report</a>
                                                      </li>
                                                      <li>
                                                          <a href="/report/trialbalance-report" class="hylink">Trial Balance Report</a>
                                                      </li>
                                                      <li>
                                                          <a href="/report/cashflow-report" class="hylink">Cashflow Statement Report</a>
                                                      </li>
                                                      <li>
                                                          <a href="/report/settlement-report" class="hylink">Settlement Report</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                  </li>
                              </ul>
                          </li>

                      @endif

                          @if(Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 4)
                          <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                              <a href="javascript:;"> Staffs 员工
                                  <span class="arrow"></span>
                              </a>
                              <ul class="dropdown-menu pull-left">
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/all-accounts">All Staffs 员工列表</a>
                                  </li>
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/add-account">Add New Staff 新增员工</a>
                                  </li>
                              </ul>
                          </li>
                          @endif

                          @if(Auth::user()->role == 1)

                          <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                              <a href="javascript:;"> System Settings
                                  <span class="arrow"></span>
                              </a>
                              <ul class="dropdown-menu pull-left">
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/prelogin-note">Prelogin Notes</a>
                                  </li>
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/all-dialects">Dialect</a>
                                  </li>
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/all-race">Race</a>
                                  </li>
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/membership-fee">Membership Fee</a>
                                  </li>
                                  <li aria-haspopup="true" class=" ">
                                      <a href="/admin/minimum-amount">Minimum Amount</a>
                                  </li>
                                  <li aria-haspopup="true">
                                    <a href="/admin/address-street-lists" class="hylink">Address</a>
                                  </li>
                              </ul>
                          </li>
                          @endif

                  </ul><!-- end nav navbar-nav -->
              </div><!-- end hor-menu -->

						</div><!-- end container -->

					</div><!-- end page-header-menu -->

				</div><!-- end page-header -->

			</div><!-- end page-wrapper-top -->

		</div><!-- end page-wrapper-row -->

		<div class="page-wrapper-row full-height">

            <div class="page-wrapper-middle">

            	@yield('main-content')

            </div><!-- end page-wrapper-middle -->

        </div><!-- end page-wrapper-row full-height -->

		<div class="page-wrapper-row">

            <div class="page-wrapper-bottom">

                @include('admin.layouts.partials.footer')

            </div><!-- end page-wrapper-bottom -->

        </div><!-- end page-wrapper-row -->

	</div><!-- end page-wrapper -->

	@section('scripts')

        @include('admin.layouts.partials.scripts')

    @show

    <script>
        $(document).ready(function()
        {
            $('#clickmewow').click(function() {

                $('#radio1003').attr('checked', 'checked');
            });
        })
    </script>

    @yield('script-js')


</body>
</html>
