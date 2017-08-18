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
      min-height: 410px;
      overflow: hidden;
      margin: 10px auto 10px;
      padding: 10px 30px 30px;
    }

    .version{
      overflow: hidden;
      background: #fff;
      padding: 20px;
      width: 100%;
      margin: 10px 0;
    }
    </style>

</head>

<body class="login">

@yield('main-content')

</body>
</html>

<script type="text/javascript">

  $(function() {


    alert('here');
  });

</script>

<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">

    $(function(){

      $("#login_btn").click(function() {

        $(".validation-error").empty();
        $(".alert-danger").remove();

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var user_name = $("#user_name").val();
        var password = $("#password").val();

        if ($.trim(user_name).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Username is empty."
        }

        if ($.trim(password).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Password is empty."
        }

        if ( $('.acknowledge').children().length > 1 ) {

          if($('#terms').prop('checked') == false)
          {
            validationFailed = true;
            errors[count++] = "Read and Acknowledge field is empty."
          }

        }

        if (validationFailed)
        {
            var errorMsgs = '';

            for(var i = 0; i < count; i++)
            {
                errorMsgs = errorMsgs + errors[i] + "<br/>";
            }

            $('html,body').animate({ scrollTop: 0 }, 'slow');

            $(".validation-error").addClass("bg-danger alert alert-error")
            $(".validation-error").html(errorMsgs);

            return false;
        }

        else {
            $(".validation-error").removeClass("bg-danger alert alert-error")
            $(".validation-error").empty();
        }

      });

    });
</script>
