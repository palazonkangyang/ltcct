@extends('admin.layouts.app')

@section('main-content')

  <div class="page-container">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
            <h1>Add New Address</h1>
          </div><!-- end page-title -->

        </div><!-- end container-fluid -->

      </div><!-- end page-head -->

      <div class="page-content">

        <div class="container-fluid">

          <ul class="page-breadcrumb breadcrumb">
              <li>
                  <a href="/operator/index">Home</a>
                  <i class="fa fa-circle"></i>
              </li>
              <li>
                  <span>Add New Address</span>
              </li>
          </ul>

          <div class="validation-error">
          </div><!-- end validation-error -->

          @if($errors->any())

              <div class="alert alert-danger">

                  @foreach($errors->all() as $error)
                      <p>{{ $error }}</p>
                  @endforeach

              </div>

          @endif

          @if(Session::has('success'))
              <div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
          @endif

           @if(Session::has('error'))
              <div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
          @endif

          <div class="page-content-inner">

            <div class="row">

              <div class="col-md-12">

                <div class="portlet light">

                  <div class="portlet-title">

                      <div class="caption font-red-sunglo">
                          <i class="icon-settings font-red-sunglo"></i>
                          <span class="caption-subject bold uppercase"> Add New Address</span>
                      </div><!-- end caption font-red-sunglo -->

                  </div><!-- end portlet-title -->

                  <div class="portlet-body form">

                    <form role="form" method="post" action="{{ URL::to('/admin/add-address') }}">
                        {!! csrf_field() !!}

                      <div class="form-body">

                        <div class="form-group">

                          <div class="col-md-6">
                            <label>Chinese Street Name</label>
                            <input name="chinese" class="form-control" placeholder="" type="text"
                            value="{{ old('chinese') }}" id="chinese">
                          </div><!-- end end-col-md-6 -->

                          <div class="col-md-6">
                          </div><!-- end col-md-6 -->

                          <div class="clearfix">
                          </div>

                        </div><!-- end form-group -->

                        <div class="form-group">

                          <div class="col-md-6">
                            <label>English Street Name</label>
                            <input name="english" class="form-control" placeholder="" type="text"
                            value="{{ old('english') }}" id="english">
                          </div><!-- end end-col-md-6 -->

                          <div class="col-md-6">
                          </div><!-- end col-md-6 -->

                          <div class="clearfix">
                          </div>

                        </div><!-- end form-group -->

                        <div class="form-group">

                          <div class="col-md-6">
                            <label>Address House No</label>
                            <input name="address_houseno" class="form-control" placeholder="" type="text"
                            value="{{ old('address_houseno') }}" id="address_houseno">
                          </div><!-- end end-col-md-6 -->

                          <div class="col-md-6">
                          </div><!-- end col-md-6 -->

                          <div class="clearfix">
                          </div>

                        </div><!-- end form-group -->

                        <div class="form-group">

                          <div class="col-md-6">
                            <label>Address Postal</label>
                            <input name="address_postal" class="form-control" placeholder="" type="text"
                            value="{{ old('address_postal') }}" id="address_postal">
                          </div><!-- end end-col-md-6 -->

                          <div class="col-md-6">
                          </div><!-- end col-md-6 -->

                          <div class="clearfix">
                          </div>

                        </div><!-- end form-group -->

                      </div><!-- end form-body -->

                      <div class="form-actions">
                        <button type="submit" class="btn blue" id="create-address-btn">Create</button>
                        <a href="/admin/address-street-lists" class="btn default">Cancel</a>
                      </div><!-- end form-actions -->

                    </form>

                  </div><!-- end portlet-body form -->

                </div><!-- end porlet light -->

              </div><!-- end col-md-12 -->

            </div><!-- end row -->

          </div><!-- end page-content-inner -->

        </div><!-- end container-fluid -->

      </div><!-- end page-content -->

    </div><!-- end page-content-wrapper -->

  </div><!-- end page-container -->

@stop

@section('script-js')

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">

    $(function() {

      $("#address_postal").keypress(function (e) {
         //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            return false;
        }
      });

      $("#create-address-btn").click(function() {

        $(".alert-success").remove();
        $(".validation-error").empty();

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var chinese = $("#chinese").val();
        var english = $("#english").val();
        var address_houseno = $("#address_houseno").val();
        var address_postal = $("#address_postal").val();

        if ($.trim(chinese).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Chinese Address field is empty."
        }

        if ($.trim(english).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "English Address field is empty."
        }

        if ($.trim(address_houseno).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Address House No field is empty."
        }

        if ($.trim(address_postal).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Address Postal field is empty."
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

        else
        {
          $(".validation-error").removeClass("bg-danger alert alert-error")
          $(".validation-error").empty();
        }
      });

    });

  </script>

@stop
