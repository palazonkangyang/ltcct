<!DOCTYPE html>
<html>
<head>
	<title>Receipt Print Preview</title>

  <link href="{{ asset('/css/normalize.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/paper.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/print.css') }}" rel="stylesheet" type="text/css" />

	<style type="text/css">
		.right{
			text-align: right;
		}

		@page { size: A5 landscape }
	</style>

</head>
<body class="A5 landscape">
	@foreach($paginate_receipts as $index=>$paginate_receipt)
	  <section class="sheet padding-5mm">

	    <header>

	    </header>

	    <div id="leftcontent">

	      <div style="width: 100%; border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 5px; font-size: 14px; font-weight: bold">
	        OFFICIAL RECEIPT - 正式收据
	      </div>

	      <div class="receipt-info">

	        <div class="label-wrapper">
	          <div class="label-left" style="font-weight: bold">Receipt Date <br />(日期)</div><!-- end label-left -->
	          <div class="label-right">{{ $transaction->trans_at }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-wrapper2">
	          <div class="label-left" style="font-weight: bold">Receipt No <br />(收据)</div><!-- end label-left -->
	          <div class="label-right2">
							@if($paginate_receipt['first_receipt_no'] == $paginate_receipt['last_receipt_no'])
								{{ $paginate_receipt['first_receipt_no'] }}
							@elseif($paginate_receipt['first_receipt_no'] != $paginate_receipt['last_receipt_no'])
								{{ $paginate_receipt['first_receipt_no'] }} - {{ $paginate_receipt['last_receipt_no'] }}
							@endif
						</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-wrapper">
	          <div class="label-left" style="font-weight: bold">Paid By <br />(付款者)</div><!-- end label-left -->
	          <div class="label-right">{{ $transaction->paid_by }} ({{ $transaction->focusdevotee_id }})</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-wrapper2">
	          <div class="label-left" style="font-weight: bold">Transaction No <br />(交易)</div><!-- end label-left -->
	          <div class="label-right2">{{ $transaction->trans_no }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-wrapper">
	          <div class="label-left" style="font-weight: bold">Description <br />(项目)</div><!-- end label-left -->
	          <div class="label-right">{{ $module->chinese_name }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-wrapper2">
	          <div class="label-left" style="font-weight: bold">Attended By <br />(接待者)</div><!-- end label-left -->
	          <div class="label-right2">{{ $transaction->attended_by }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

					<div class="label-mainwrapper">
						<div class="label-left" style="font-weight: bold">Address</div><!-- end label-left -->
	          <div class="">{{ $family_address}}</div>


					</div><!-- end label-wrapper -->

	      </div><!-- end receipt-info -->

	      <hr>

	      <div class="receipt-list">

	        <table class="receipt-table">
	          <thead>
	            <tr style="text-align:left;">
								<th width="1%">S/No</th>
								<th width="12%">Name</th>
	              <th width="85%">Description</th>
	              <th width="1%">Receipt</th>
	              <th width="1%">Amount</th>
	            </tr>
	          </thead>

	          <tbody>
							@foreach($paginate_receipt['receipt'] as $index=>$rct)
							<tr>
								<td style="text-align: left;">{{$rct['sn_no']}}</td>
							@if( $module['mod_id'] == Session::get('module.xiaozai_id') )
								@if($rct['is_receipt'] == false)
									<td colspan="4" style="text-align: left; font-weight:bold;">{{$rct['name']}} {{ $rct['item_description'] }}</td>
								@else
									<td style="text-align: left;">{{$rct['name']}}</td>
									<td style="text-align: left; font-size:0.8em;">{{ $rct['item_description'] }}</td>
								@endif

							@else
								<td style="text-align: left;">{{$rct['name']}}</td>
								<td style="text-align: left; font-size:0.8em;">{{ $rct['item_description'] }}</td>

							@endif
								<td style="text-align: left;">{{ $rct['receipt_no'] }}</td>
								<td style="text-align: right;">{{ $rct['amount'] }}</td>
							</tr>
							@endforeach

	          </tbody>
	        </table>

	      </div><!-- end receipt-list -->

	      <div style="overflow: hidden;">

	        <div style="float:left; width: 60%;">
	          <p><span style="font-weight:bold;">Payment Mode:</span>
							{{ $transaction->mode_payment }}
						<br /><span style="font-weight:bold;">(付款方式)</span></p>
	        </div>

	        <div class="float: right: width: 40%;">
	          <p><span style="font-weight: bold;">Total Amount:</span> S$ {{ $paginate_receipt['total_amount'] }}  <br /><span style="font-weight: bold;">(总额)</span></p>
	        </div>

	      </div>

	    </div><!-- end leftcontent -->

	    <div id="rightcontent">

	      <div style="border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 10px; font-weight: bold">
	        OFFICIAL RECEIPT - 正式收据
	      </div>

	      <div class="receipt-info">
	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Receipt Date (日期)</div><!-- end label-left -->
	          <div class="rightlabel-right">{{ $transaction->trans_at }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Description (项目)</div><!-- end label-left -->
	          <div class="rightlabel-right">{{ $module->chinese_name }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->
	      </div><!-- end receipt-info -->

	      <hr>
	      <hr>

	      <div class="receipt-info">
	        <div class="label-rightwrapper" style="font-weight: bold">
	          <div class="rightlabel-left">Next Event (下个法会)</div><!-- end label-left -->
	          <div class="rightlabel-right">{{ $next_event->event }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Event Date (法会日期)</div><!-- end label-left -->
	          <div class="rightlabel-right">{{ $next_event->start_at }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="color: #fff;">&nbsp;</div><!-- end label-left -->
	          <div class="rightlabel-right"></div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Time (时间)</div><!-- end label-left -->
	          <div class="rightlabel-right">{{ $time_now }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->
	      </div><!-- end receipt-info -->

	      <div class="receipt-info">
	        <div class="paidby" style="width: 49mm; float: left;">
	          <p style="font-weight: bold">Paid By (付款者)</p>
	          <p>{{ $transaction->paid_by }} ({{ $transaction->focusdevotee_id }})</p>
	          <p style="margin-top: 15px; font-weight: bold">No of Set(s) / 份数</p>
	        </div>

	        <div style="width: 22mm; float: left; border: 1px solid black; height: 2.7cm; line-height: 2.7cm; text-align: center; vertical-align: middle;">
	          <span style="font-size: 70px; font-weight: bold;">{{ $paginate_receipt['no_of_set'] }}</span>
	        </div>
	      </div><!-- end receipt-info -->

	      <div class="receipt-info">
	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Total Amount (总额)</div><!-- end label-left -->
	          <div class="rightlabel-right">S$ {{ $paginate_receipt['total_amount'] }}</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->

	        <div class="label-rightwrapper">
	          <div class="rightlabel-left" style="font-weight: bold">Receipt No (收据)</div><!-- end label-left -->
	          <div class="rightlabel-right">
							@if($paginate_receipt['first_receipt_no'] == $paginate_receipt['last_receipt_no'])
								{{ $paginate_receipt['first_receipt_no'] }}
							@elseif($paginate_receipt['first_receipt_no'] != $paginate_receipt['last_receipt_no'])
								{{ $paginate_receipt['first_receipt_no'] }} -{{ $paginate_receipt['last_receipt_no'] }} &nbsp;
							@endif
						</div><!-- end label-right -->
	        </div><!-- end label-wrapper -->
	      </div><!-- end receipt-info -->

	    </div><!-- end rightcontent -->

	  </section>
	@endforeach

</body>
</html>

<script type="text/javascript">
</script>
