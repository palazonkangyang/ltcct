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



	    <section id="section-left">
				<header></header>
				@include('receipt.section-left')
	    </section><!-- end section-left -->

	    <section id="section-right">


				@if($module['mod_id'] == Session::get('module.xiaozai_id'))
				<header></header>
				@elseif($module['mod_id'] == Session::get('module.qifu_id') || $module['mod_id'] == Session::get('module.kongdan_id'))

				@endif

				@include('receipt.section-right')
	    </section><!-- end section-right -->

	  </section>
	@endforeach

</body>
</html>

<script type="text/javascript">
</script>
