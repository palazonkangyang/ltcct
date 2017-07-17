<!DOCTYPE html>
<html>
<head>
	<title>Transaction Detail</title>

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

					<div class="label-right">{{ $receipt[0]->chinese_name }} (D - {{ $receipt[0]->focusdevotee_id }})</div><!-- end label-right -->

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

		<div class="clearfix"></div>

		<br>

		<div id="bottom-area">

			<h3>Same address Devotee 同址善信</h3>

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
						<th>Pay Till</th>
						<th>Receipt</th>
						<th>Amount</th>
					</tr>
				</thead>

				<tbody>

					@php 

						$count = 1; 
						$sum= 0;
						$receipt_id = $generaldonation_items[0]->xy_receipt;

					@endphp

					@foreach($generaldonation_items as $generaldonation_item)

						@if($receipt_id == $generaldonation_item->xy_receipt)

							<tr>
								<td>{{ $count }}</td>
								<td>{{ $generaldonation_item->chinese_name }}</td>
								<td>{{ $generaldonation_item->devotee_id }}</td>
								<td>{{ $generaldonation_item->address_houseno }}</td>
								<td>{{ $generaldonation_item->address_street }}</td>
								<td>{{ $generaldonation_item->address_unit1 }} {{ $generaldonation_item->address_unit2 }}</td>
								<td>{{ $generaldonation_item->hjgr }}</td>
								<td>{{ \Carbon\Carbon::parse($generaldonation_item->paid_till)->format("d/m/Y") }}</td>
								<td>{{ $generaldonation_item->xy_receipt }}</td>
								<td>S$ {{ $generaldonation_item->amount }}</td>
							</tr>

						@else

							@php break; @endphp

						@endif

					@php $count++;  $sum += $generaldonation_item->amount; @endphp

					@endforeach
					</tbody>
				</table>

				<br><br>

				<h3>Relatives and Friends 亲戚朋友</h3>

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
						<th>Pay Till</th>
						<th>Receipt</th>
						<th>Amount</th>
					</tr>
				</thead>

				<tbody>

					@foreach($generaldonation_items as $generaldonation_item)

						@if($receipt_id != $generaldonation_item->xy_receipt)

							<tr>
								<td>{{ $count }}</td>
								<td>{{ $generaldonation_item->chinese_name }}</td>
								<td>{{ $generaldonation_item->devotee_id }}</td>
								<td>{{ $generaldonation_item->address_houseno }}</td>
								<td>{{ $generaldonation_item->address_street }}</td>
								<td>{{ $generaldonation_item->address_unit1 }} {{ $generaldonation_item->address_unit2 }}</td>
								<td>{{ $generaldonation_item->hjgr }}</td>
								<td>{{ \Carbon\Carbon::parse($generaldonation_item->paid_till)->format("d/m/Y") }}</td>
								<td>{{ $generaldonation_item->xy_receipt }}</td>
								<td>S$ {{ $generaldonation_item->amount }}</td>
							</tr>

						@else

							@php continue; @endphp

						@endif

					@php $count++;  $sum += $generaldonation_item->amount; @endphp

					@endforeach
				</tbody>
			</table>
				

		</div><!-- end bottom-area -->

		<div class="clearfix"></div>

		<br>

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

					<div class="label">
						<input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'hj'){ ?>checked="checked"<?php }?>>
						1 Receipt Printing for same address <br />

						<input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'gr'){ ?>checked="checked"<?php }?>> 
						Individual Receipt Printing
					</div><!-- end label-left -->

				</div><!-- end label-wrapper -->

				<div class="label-wrapper">

					<div class="label">
						<button onclick="reprint()">Re-Print Receipt</button>
					</div><!-- end label-left -->

				</div><!-- end label-wrapper -->
				
			</div><!-- end middle-left-area -->

		</div><!-- end bottom-area -->

		<div class="clearfix"></div><!-- end clearfix -->
		
	</page>

</body>
</html>

<script type="text/javascript">	

	function reprint() {
		window.print();
	}
	
</script>