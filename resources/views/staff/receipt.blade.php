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

	<section class="sheet padding-10mm">

	    <!-- Write HTML just like a web page -->

			<header>

			</header>

			<article>
				<div id="leftcontent">

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
								<td>{{ $festiveevent->end_at }}</td>
								<td>{{ $festiveevent->lunar_date }}</td>
							</tr>
						</tbody>

					</table>

				</div><!-- end rightcontent -->
			</article>

	  </section>

</body>
</html>
