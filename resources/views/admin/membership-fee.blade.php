@extends('admin.layouts.app')

@section('main-content')

<div class="page-container">

      <div class="page-content-wrapper">

          <div class="page-head">

              <div class="container-fluid">

                <div class="page-title">

                      <h1>Membership Fee</h1>

                  </div><!-- end page-title -->

              </div><!-- end container -->

          </div><!-- end page-head -->

          <div class="page-content">

              <div class="container-fluid">

                  <ul class="page-breadcrumb breadcrumb">
                      <li>
                          <a href="/operator/index">Home</a>
                          <i class="fa fa-circle"></i>
                      </li>
                      <li>
                          <span>Membership Fee</span>
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
                                          <span class="caption-subject bold uppercase">Membership Fee</span>
                                      </div><!-- end caption font-red-sunglo -->

                                  </div><!-- end portlet-title -->


                                  <div class="portlet-body form">

                                    <form role="form" method="post" action="{{ URL::to('/admin/update-membership-fee') }}">
                                        {!! csrf_field() !!}

                                        <div class="form-body">

                                          <div class="form-group">
                                              <input type="hidden" name="membership_fee_id" value="{{ $membership[0]->membership_fee_id }}">
                                          </div><!-- end form-group -->

                                          <div class="form-group">
                                            <div class="col-md-2">
                                              <label>Membership Fee</label>

                                              <input type="text" class="form-control" name="membership_fee"
                                                value="{{ old( 'membership_fee', $membership[0]->membership_fee) }}" id="membership_fee">
                                            </div><!-- end col-md-2 -->

                                            <div class="col-md-10">
                                            </div><!-- end col-md-10 -->

                                            <div class="clearfix">
                                            </div><!-- end clearfix -->
                                          </div><!-- end form-group -->

                                        </div><!-- end form-body -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn blue" id="update">Update</button>
                                            <button type="button" class="btn default" id="cancel">Cancel</button>
                                        </div><!-- end form-actions -->

                                    </form>

                                  </div><!-- end portlet-body form -->

                              </div><!-- end portlet light -->

                          </div><!-- end col-md-6 -->

                      </div><!-- end row -->

                  </div><!-- end page-content-inner -->

              </div><!-- end container -->

          </div><!-- end page-content -->

      </div><!-- end page-content-wrapper -->

  </div><!-- end page-container -->

@stop

@section('script-js')

  <script type="text/javascript">
    $(function() {

      $("#update").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        $(".alert-success").remove();
        $(".validation-error").empty();

        var membership_fee = $("#membership_fee").val();

        if ($.trim(membership_fee).length <= 0)
        {
          validationFailed = true;
          errors[count++] = "Membership Fee field is empty."
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
