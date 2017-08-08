<!DOCTYPE HTML>
<html>
<head>
  	<title>LTCCT - @yield('title')</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  	<meta name="csrf-token" content="{{ csrf_token() }}">
  	<link rel="shortcut icon" href="images/favicon.ico" />
  	<link rel="apple-touch-icon" href="images/favicon.png" />
  	<link rel="apple-touch-icon-precomposed" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="{{URL::asset('css/admin/login.css')}}">

	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/bootstrap/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/css/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/css/login.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />

    <style media="screen">
    .acknowledge{
      background: #fff;
      width: 100%;
      min-height: 300px;
      overflow: hidden;
      margin: 40px auto 10px;
      padding: 10px 30px 30px;
    }
    </style>

</head>

<body class="login">

@yield('main-content')

</body>
</html>
