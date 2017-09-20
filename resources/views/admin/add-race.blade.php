@extends('admin.layouts.app')

@section('main-content')

  <div class="page-container">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
            <h1>Add New Race</h1>
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
                  <span>Add New Race</span>
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
                          <span class="caption-subject bold uppercase"> Add New Race</span>
                      </div><!-- end caption font-red-sunglo -->

                  </div><!-- end portlet-title -->

                  <div class="portlet-body form">

                    <form role="form" method="post" action="{{ URL::to('/admin/add-race') }}">
                        {!! csrf_field() !!}

                      <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-6">
                              <label>Race Name</label>
                              <input name="race_name" class="form-control" placeholder="" type="text"
                              value="{{ old('race_name') }}" id="race_name">
                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                            </div><!-- end col-md-6 -->

                            <div class="clearfix">
                            </div><!-- end clearfix -->

                        </div><!-- end form-group -->
                      </div><!-- end form-body -->

                      <div class="form-actions">
                        <button type="submit" class="btn blue" id="create-race-btn">Create</button>
                        <a href="/admin/all-race" class="btn default">Cancel</a>
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
      $("#create-race-btn").click(function() {

        $(".alert-success").remove();
        $(".validation-error").empty();

        var count = 0;
        var errors = new Array();
        var validationFailed = false;
        var race_name = $("#race_name").val();
        var alphanumers = /^[a-zA-Z0-9_ -]+$/;

        if(race_name != "")
        {
          if(!alphanumers.test(race_name)){
            validationFailed = true;
            errors[count++] = "Race Name cannot be special Character.";
          }
        }

        if ($.trim(race_name).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Race Name is empty."
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
