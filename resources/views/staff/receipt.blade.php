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
	    <article>


			</article>

			<header>

			</header>

			<article>
				<div class="leftcontent">
					<p>Left Content</p>
				</div><!-- end leftcontent -->

				<div class="rightcontent">
					<p>Right Content</p>
				</div><!-- end rightcontent -->
			</article>

	  </section>

</body>
</html>
