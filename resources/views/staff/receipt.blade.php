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

	<section class="sheet padding-5mm">

	    <!-- Write HTML just like a web page -->

			<header>

			</header>

			<article>
				<div id="leftcontent">

					<div class="receipt-info">
						<div class="label-wrapper">
							<div class="label-left">Receipt Date :</div><!-- end label-left -->
							<div class="label-right">{{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Paid By :</div><!-- end label-left -->
							<div class="label-right">{{ $receipt[0]->chinese_name }} (D - {{ $receipt[0]->devotee_id }})</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Description :</div><!-- end label-left -->
							<div class="label-right">香油</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Receipt No :</div><!-- end label-left -->
							<div class="label-right">{{ $receipt[0]->trans_no }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Transaction No :</div><!-- end label-left -->
							<div class="label-right">{{ $receipt[0]->trans_no }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Attended By (接待者)</div><!-- end label-left -->
							<div class="label-right">{{ $festiveevent->event }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->
					</div>

					<div class="receipt-list">

						<table class="receipt-table">
							<thead>
								<tr>
									<th>S/No</th>
									<th>Chinese Name</th>
									<th>Devotee</th>
									<th>Address</th>
									<th>HJ/ GR</th>
									<th>Receipt</th>
									<th>Amount</th>
								</tr>
							</thead>

							<tbody>

								@php $count = 1; $sum= 0; @endphp

								@foreach($donation_devotees as $donation_devotee)

								<tr>
									<td>{{ $count }}</td>
									<td>{{ $donation_devotee->chinese_name }}</td>
									<td>{{ $donation_devotee->devotee_id }}</td>
									<td>
										@if(isset($donation_devotee->oversea_addr_in_chinese))
											{{ $donation_devotee->oversea_addr_in_chinese }}
										@elseif(isset($donation_devotee->address_unit1) && isset($donation_devotee->address_unit2))
											{{ $donation_devotee->address_houseno }}, #{{ $donation_devotee->address_unit1 }}-{{ $donation_devotee->address_unit2 }}, {{ $donation_devotee->address_street }}, {{ $donation_devotee->address_postal }}
										@else
											{{ $donation_devotee->address_houseno }}, {{ $donation_devotee->address_street }}, {{ $donation_devotee->address_postal }}
										@endif
									</td>
									<td>{{ $donation_devotee->hjgr }}</td>
									<td>{{ $receipt[0]->xy_receipt }}</td>
									<td>S$ {{ $donation_devotee->amount }}</td>
								</tr>

								@php $count++;  $sum += $donation_devotee->amount; @endphp

								@endforeach
							</tbody>
						</table>

						<br />

						<div class="receipt-info">
							<div class="label-wrapper">
								<div class="label">Payment Mode : {{ $generaldonation->mode_payment }}</div><!-- end label -->
							</div><!-- end label-wrapper -->

							<div class="label-wrapper">
								<div class="label">Total Amount : S$ {{ $sum }}</div><!-- end label -->
							</div><!-- end label-wrapper -->

						</div><!-- end receipt-info -->

					</div><!-- end receipt-list -->

				</div><!-- end leftcontent -->

				<div id="rightcontent">

					<div class="receipt-info">
						<div class="label-wrapper">
							<div class="rightlabel-left">Receipt Date :</div><!-- end label-left -->
							<div class="rightlabel-right">{{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="rightlabel-left">Description :</div><!-- end label-left -->
							<div class="rightlabel-right">{{ $receipt[0]->description }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="rightlabel-left">Donation for next Event :</div><!-- end label-left -->
							<div class="rightlabel-right">{{ $festiveevent->event }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->
					</div>

					<div class="receipt-info">
						<div class="label-wrapper">
							<div class="rightlabel-left">Next Event (下个法会)</div><!-- end label-left -->
							<div class="rightlabel-right"></div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="rightlabel-left">Event Date (法会日期)</div><!-- end label-left -->
							<div class="rightlabel-right">{{ \Carbon\Carbon::parse($festiveevent->start_at)->format("d/m/Y") }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="rightlabel-left"></div><!-- end label-left -->
							<div class="rightlabel-right">{{ $festiveevent->lunar_date }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="rightlabel-left">Time (时间)</div><!-- end label-left -->
							<div class="rightlabel-right">{{ $festiveevent->time }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->
					</div>



				</div><!-- end rightcontent -->
			</article>

	  </section>

</body>
</html>

<script type="text/javascript">

		// window.print();

</script>
