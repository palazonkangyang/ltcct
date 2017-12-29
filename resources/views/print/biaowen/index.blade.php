<!DOCTYPE html>
<html>
<head>
	<title>Biao Wen Print Preview</title>

  <link href="{{ asset('/css/normalize.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/paper.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/print.css') }}" rel="stylesheet" type="text/css" />

	<style type="text/css">
		.right{
			text-align: right;
		}

		@page { size: A3 landscape }
	</style>

</head>
<body class="A3 landscape">
	@foreach($display_name_list as $index=>$display_name)
		@if($index == 0 || $index % 15 == 0)
			<section class="sheet padding-5mm">
				<div class="vertical-orientation">
					<table>
		@endif
						<tr>
								<td style="padding:3mm; width:21mm; height:40mm;"><p><span style="font-size:25px;">{{ $display_name_list[$index] }}</span></p></td>
								<td style="padding:3mm; width:21mm; height:251mm;"><p><span style="font-size:25px;">{{ $display_address_list[$index] }}</span></p></td>
						</tr>

		@if($index != 0 && ($index+1) % 15 == 0)
				</table>
			</div>
		</section>
		@endif
	 @endforeach
</body>
</html>

<script type="text/javascript">
</script>
