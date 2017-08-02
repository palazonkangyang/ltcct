<!DOCTYPE html>
<html>
<head>
	<title>Receipt Print Preview</title>

	<link href="{{ asset('/css/normalize.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/receipt.css') }}" rel="stylesheet" type="text/css" />

	<style type="text/css">
		.right{
			text-align: right;
		}
	</style>

</head>
<body>

	<page size="A4">

		<div id="top-area">

			<div class="label-wrapper">

				<div class="label-left">Receipt No :</div><!-- end label-left -->

				<div class="label-right">{{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</div><!-- end label-right -->

			</div><!-- end label-wrapper -->

			<div class="label-wrapper">

				<div class="label-left">Transaction No :</div><!-- end label-left -->

				<div class="label-middle">{{ $receipt[0]->trans_no }}</div><!-- end label-middle -->

				<div class="label-middle">
					<a href="{{ URL::to('/staff/transaction/' . $receipt[0]->generaldonation_id) }}">Detail</a>
				</div><!-- end label-middle -->

			</div><!-- end label-wrapper -->

		</div><!-- end top-area -->

		<div id="middle-area">

			<div class="label-wrapper">

				<div class="label"><h3>Receipt Viewer : {{ $receipt[0]->chinese_name }}</h3></div><!-- end label -->

			</div><!-- end label-wrapper -->

			<div id="middle-left-area">

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

			</div><!-- end middle-left-area -->

			<div id="middle-left-area">

				<div class="label-wrapper">

					<div class="label-left">Receipt No :</div><!-- end label-left -->

					<div class="label-right">{{ $receipt[0]->xy_receipt }}</div><!-- end label-right -->

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<div class="label-left">Transaction No :</div><!-- end label-left -->

					<div class="label-right">{{ $receipt[0]->trans_no }}</div><!-- end label-right -->

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<div class="label-left">Attended By :</div><!-- end label-left -->

					<div class="label-right"></div><!-- end label-right -->

				</div><!-- end label-wrapper -->

			</div><!-- end middle-left-area -->

		</div><!-- end middle-area -->

		<div class="clearfix"></div><!-- end clearfix -->

		<div id="bottom-area">
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
		</div><!-- end bottom-area -->

		<div class="bottom-area">

			<div class="middle-left-area">

				<div class="label-wrapper">

					<div class="label-left">Payment Mode :</div><!-- end label-left -->

					<div class="label-right">{{ $generaldonation->mode_payment }}</div><!-- end label-right -->

				</div><!-- end label-wrapper -->

			</div><!-- end middle-left-area -->

			<div class="middle-left-area">

				<div class="label-wrapper">

					<div class="label-left">Total Amount :</div><!-- end label-left -->

					<div class="label-right">S$ {{ $sum }}</div><!-- end label-right -->

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<div class="label-left">Type of Receipt Printing :</div><!-- end label-left -->

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'hj'){ ?>checked="checked"<?php }?>>
				 1 Receipt Printing for same address <br />

				 <input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'gr'){ ?>checked="checked"<?php }?>>
				 Individual Receipt Printing

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<div class="label">
						<button onclick="reprint()">Re-Print Receipt</button>
					</div><!-- end label-left -->

				</div><!-- end label-wrapper -->

			</div><!-- end middle-left-area -->

		</div><!-- end bottom-area -->
	</page>

</body>
</html>

<script type="text/javascript">

	function reprint() {
		window.print();
	}

</script>
