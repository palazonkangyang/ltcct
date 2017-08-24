@extends('admin.layouts.app')

@section('main-content')

  <div class="page-container">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
            <h1>Add New Dialect</h1>
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
                  <span>Add New Dialect</span>
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
                          <span class="caption-subject bold uppercase"> Add New Dialect</span>
                      </div><!-- end caption font-red-sunglo -->

                  </div><!-- end portlet-title -->

                  <div class="portlet-body form">

                    <form role="form" method="post" action="{{ URL::to('/admin/add-dialect') }}">
                        {!! csrf_field() !!}

                      <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-6">
                              <label>Dialect Name</label>
                              <input name="dialect_name" class="form-control" placeholder="" type="text"
                              value="{{ old('dialect_name') }}" id="dialect_name">
                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                            </div><!-- end col-md-6 -->

                            <div class="clearfix">
                            </div><!-- end clearfix -->

                        </div><!-- end form-group -->
                      </div><!-- end form-body -->

                      <div class="form-actions">
                          <button type="submit" class="btn blue" id="create-dialect-btn">Create</button>
                          <button type="button" class="btn default" id="cancel-dialect-btn">Cancel</button>
                      </div><!-- end form-actions -->

                      <div id="dialog-box" title="System Alert" style="display:none;">
                          You have NOT Saved this Dialect Record
                          Do you want to Cancel this record?
                      </div>

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
      $("#create-dialect-btn").click(function() {

        $(".alert-success").remove();
        $(".validation-error").empty();

        var count = 0;
        var errors = new Array();
        var validationFailed = false;
        var dialect_name = $("#dialect_name").val();

        if ($.trim(dialect_name).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Dialect Name is empty."
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

      $( "#dialog-box" ).dialog({
        autoOpen: false
      });

      $("#dialog-box").dialog({
       autoOpen: false,
       modal: true,
       buttons : {
            "No, Mistake" : function() {
                $(this).dialog("close");
            },
            "Yes, Cancel" : function() {
              window.location.reload(true);
              $(this).dialog("close");
            }
          }
      });

      $("#cancel-dialect-btn").on("click", function(e) {
          e.preventDefault();
          $("#dialog-box").dialog("open");
      });
    });

  </script>

@stop
