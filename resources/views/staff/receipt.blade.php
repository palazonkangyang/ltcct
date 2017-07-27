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
							<div class="label-left">Receipt No :</div><!-- end label-left -->
							<div class="label-right">{{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->
						
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
							<div class="label-right">{{ $receipt[0]->description }}</div><!-- end label-right -->
						</div><!-- end label-wrapper -->

						<div class="label-wrapper">
							<div class="label-left">Donation for next Event :</div><!-- end label-left -->
							<div class="label-right"></div><!-- end label-right -->
						</div><!-- end label-wrapper -->
					</div>

					<div class="receipt-list">

						<table class="receipt-table">
							<thead>
								<tr>
									<th>S/No</th>
									<th>Chinese Name</th>
									<th>Devotee</th>
									<th>Block</th>
									<th>Address</th>
									<th>Unit</th>
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
									<td>{{ $donation_devotee->address_houseno }}</td>
									<td>{{ $donation_devotee->address_street }}</td>
									<td>{{ $donation_devotee->address_unit1 }} {{ $donation_devotee->address_unit2 }}</td>
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
					<h4>Event: {{ $festiveevent->event }}</h4>

					<table class="receipt-table">
						<thead>
							<tr>
								<th>Date To</th>
								<th>Lunar Date</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>{{ \Carbon\Carbon::parse($festiveevent->end_at)->format("d/m/Y") }}</td>
								<td>{{ $festiveevent->lunar_date }}</td>
							</tr>
						</tbody>

					</table>

				</div><!-- end rightcontent -->
			</article>

	  </section>

</body>
</html>

<script type="text/javascript">

		window.print();

</script>
