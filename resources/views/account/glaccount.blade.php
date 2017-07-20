@extends('layouts.backend.app')

@section('main-content')

    <div class="page-container-fluid">

      <div class="page-content-wrapper">

        <div class="page-head">

            <div class="container-fluid">

                <div class="page-title">

                    <h1>GL Account</h1>

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
                    <span>GL Account</span>
                </li>
            </ul>

            <div class="page-content-inner">

              <div class="inbox">

                <div class="row">

                  <div class="col-md-12">

                    <div class="portlet light">

                      <div class="validation-error">
                      </div><!-- end validation-error -->

                      @if($errors->any())

                          <div class="alert alert-danger">

                              @foreach($errors->all() as $error)
                                  <p>{{ $error }}</p>
                              @endforeach

                          </div><!-- end alert alert-danger -->

                      @endif

                      @if(Session::has('success'))
                          <div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
                      @endif

                      @if(Session::has('error'))
                          <div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
                      @endif

                      <div class="portlet-body">

                        <div class="tabbable-bordered">
                            <ul class="nav nav-tabs">
                              <li class="active">
                                <a href="#tab_glaccountlist" data-toggle="tab">GL Account List</a>
                              </li>
                              <li>
                                <a href="#tab_newglaccount" data-toggle="tab">New GL Account</a>
                              </li>
                              <li id="edit-glaccount" class="disabled">
                                <a href="#tab_editglaccount" data-toggle="tab">Edit GL Account Group</a>
                              </li>
                            </ul>

                            <div class="tab-content">

                              <div class="tab-pane active" id="tab_glaccountlist">

                                <div class="form-body">

                                  <div class="form-group">

                                    <table class="table table-bordered" id="glaccount-table">
                                      <thead>
                                          <tr>
                                              <th>Account Code</th>
                                              <th>Account Group</th>
                                              <th>Account Description</th>
                                              <th>Account Group Status</th>
                                          </tr>
                                      </thead>

                                      <tbody>
                                          @if(count($glaccount))

                                            @foreach($glaccount as $gl)
                                            <tr>
                                              <td>{{ $gl->accountcode }}</td>
                                              <td>{{ $gl->glcodegroup_name }}</td>
                                              <td>{{ $gl->description }}</td>
                                              <td class="text-capitalize">{{ $gl->status }}</td>
                                            </tr>
                                            @endforeach

                                          @endif
                                      </tbody>
                                    </table>
                                  </div><!-- end form-group -->

                                </div><!-- end form-body -->

                              </div><!-- end tab-pane tab-glaccount-group-list -->

                              <div class="tab-pane" id="tab_newglaccount">

                                <div class="form-body">

                                  <div class="col-md-6">

                                    <form method="post" action="{{ URL::to('/account/new-glaccount') }}"
                                      class="form-horizontal form-bordered">

                                      {!! csrf_field() !!}

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Code *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="accountcode" value="{{ old('accountcode') }}" id="accountcode">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Description *</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="description" rows="4" id="description">{{ old('description') }}</textarea>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Group *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="glcodegroup_id">
                                              @foreach($glaccountgroup as $gl)
                                              <option value="{{ $gl->glcodegroup_id }}">{{ $gl->name }}</option>
                                              @endforeach
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Status *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="status">
                                              <option value="active">Active</option>
                                              <option value="inactive">Inactive</option>
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">
                                        <p>&nbsp;</p>
                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <div class="col-md-6">
                                          <p>
                                            If you have made Changes to the above. You need to CONFIRM to save the Changes.
                                            To Confirm, please enter authorized password to proceed.
                                          </p>
                                        </div><!-- end col-md-6 -->

                                        <div class="col-md-6">
                                          <label class="col-md-6">Authorized Password</label>
                                          <div class="col-md-6">
                                            <input type="password" class="form-control" name="authorized_password" value="" id="authorized_password">
                                          </div><!-- end col-md-6 -->
                                        </div><!-- end col-md-6 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label"></label>
                                        <div class="col-md-9">
                                          <div class="form-actions pull-right">
                                            <button type="submit" class="btn blue" id="confirm_glcode_btn">Confirm
                                            </button>
                                            <button type="button" class="btn default">Cancel</button>
                                          </div><!-- end form-actions -->
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                    </form>

                                  </div><!-- end col-md-6 -->

                                  <div class="col-md-6">
                                  </div><!-- end col-md-6 -->

                                </div><!-- end form-group -->

                                <div class="clearfix"></div><!-- end clearfix -->

                              </div><!-- end tab-pane tab_newglaccount -->

                              <div class="tab-pane" id="tab_editglaccount">

                                <div class="form-body">

                                  <div class="col-md-6">

                                    <form method="post" action="{{ URL::to('/account/update-glaccount') }}"
                                      class="form-horizontal form-bordered">

                                      {!! csrf_field() !!}

                                      <div class="form-group">
                                        <input type="hidden" name="glcode_id" value="" id="edit_glcode_id">
                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Code *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="accountcode" value="{{ old('accountcode') }}" id="edit_accountcode">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Description *</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="description" rows="4" id="edit_description">{{ old('description') }}</textarea>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Group *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="edit_glcodegroup_id" id="edit_glcodegroup_id" disabled>
                                              @foreach($glaccountgroup as $gl)
                                              <option value="{{ $gl->glcodegroup_id }}">{{ $gl->name }}</option>
                                              @endforeach
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Status *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="edit_status" id="edit_status" disabled>
                                              <option value="active">Active</option>
                                              <option value="inactive">Inactive</option>
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">
                                        <p>&nbsp;</p>
                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <div class="col-md-6">
                                          <p>
                                            If you have made Changes to the above. You need to CONFIRM to save the Changes.
                                            To Confirm, please enter authorized password to proceed.
                                          </p>
                                        </div><!-- end col-md-6 -->

                                        <div class="col-md-6">
                                          <label class="col-md-6">Authorized Password</label>
                                          <div class="col-md-6">
                                            <input type="password" class="form-control" name="authorized_password" value="" id="edit_authorized_password">
                                          </div><!-- end col-md-6 -->
                                        </div><!-- end col-md-6 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label"></label>
                                        <div class="col-md-9">
                                          <div class="form-actions pull-right">
                                            <button type="submit" class="btn blue" id="update_glcode_btn">Update
                                            </button>
                                            <button type="button" class="btn default">Cancel</button>
                                          </div><!-- end form-actions -->
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                    </form>

                                  </div><!-- end col-md-6 -->

                                  <div class="col-md-6">
                                  </div><!-- end col-md-6 -->

                                </div><!-- end form-group -->

                                <div class="clearfix"></div><!-- end clearfix -->

                              </div><!-- end tab-pane tab_editglaccount -->

                            </div><!-- end tab-content -->
                        </div><!-- end tabbable-bordered -->

                      </div><!-- end portlet-body -->

                    </div><!-- end portlet light -->

                  </div><!-- end col-md-12 -->

                </div><!-- end row -->

              </div><!-- end inbox -->

            </div><!-- end page-content-inner -->

          </div><!-- end container-fluid -->

        </div><!-- end page-content -->

      </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop

@section('custom-js')

  <script src="{{asset('js/custom/common.js')}}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

  <script type="text/javascript">

    $(function() {

      var url = window.location.href;
      var hash = url.substring(url.indexOf("#")+1);
      var tab = "#" + hash;

      var glaccount_id = "<?php echo $_GET['glcode_id'] ?>";

      if(glaccount_id)
      {
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', tab);
        });

        var formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            glaccount_id: glaccount_id
        };

        $.ajax({
            type: 'GET',
            url: "/account/edit-glaccount",
            data: formData,
            dataType: 'json',
            success: function(response)
            {

              $("#edit_glcode_id").val(response.glaccount['glcode_id']);
              $("#edit_accountcode").val(response.glaccount['accountcode']);
              $("#edit_description").val(response.glaccount['description']);
              $("#edit_glcodegroup_id").val(response.glaccount['glcodegroup_id']);
              $("#edit_status").val(response.glaccount['status']);

              localStorage.setItem('glocodeid', response.glaccount['glcode_id']);
              localStorage.setItem('glcodegroup_id', response.glaccount['glcodegroup_id']);
              localStorage.setItem('status', response.glaccount['status']);

            },

            error: function (response) {
                console.log(response);
            }
        });
      }

      else if ($('.alert-error').children().length > 0) {
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', tab);
        });
      }

      else {
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
      }

      if ( $('.alert-success').children().length > 0 ) {
          localStorage.removeItem('activeTab');
      }

      else
      {
          var activeTab = localStorage.getItem('activeTab');
      }

      if (activeTab) {
          $('a[href="' + activeTab + '"]').tab('show');
          console.log(activeTab);
      }

      // Disabled Edit Devotee Tab
      $(".nav-tabs > li").click(function(){
          if($(this).hasClass("disabled"))
              return false;
      });

      if ( $('.alert-success').children().length > 0 ) {
          localStorage.removeItem('glocodeid');
          localStorage.removeItem('glcodegroup_id');
          localStorage.removeItem('status');
      }

      else
      {
          var glocodeid = localStorage.getItem('glocodeid');
          var glcodegroup_id = localStorage.getItem('glcodegroup_id');
          var status = localStorage.getItem('status');
      }

      if(glocodeid)
      {
        $("#edit_glcode_id").val(glocodeid);
        $("#edit_glcodegroup_id").val(glcodegroup_id);
        $("#edit_status").val(status);
      }

      // $("#glaccount-table").on('click','.edit-glaccount',function(e) {
      //
      //   $(".nav-tabs > li:first-child").removeClass("active");
      //   $("#edit-glaccount").addClass("active");
      //
      //   var glaccount_id = $(this).attr("id");
      //
      //   var formData = {
      //       _token: $('meta[name="csrf-token"]').attr('content'),
      //       glaccount_id: glaccount_id
      //   };
      //
      //   $("#edit_accountcode").val('');
      //   $("#edit_description").val('');
      //
      //   $.ajax({
      //       type: 'GET',
      //       url: "/account/edit-glaccount",
      //       data: formData,
      //       dataType: 'json',
      //       success: function(response)
      //       {
      //
      //         $("#edit_glcode_id").val(response.glaccount['glcode_id']);
      //         $("#edit_accountcode").val(response.glaccount['accountcode']);
      //         $("#edit_description").val(response.glaccount['description']);
      //         $("#edit_glcodegroup_id").val(response.glaccount['glcodegroup_id']);
      //         $("#edit_status").val(response.glaccount['status']);
      //
      //         localStorage.setItem('glocodeid', response.glaccount['glcode_id']);
      //         localStorage.setItem('glcodegroup_id', response.glaccount['glcodegroup_id']);
      //         localStorage.setItem('status', response.glaccount['status']);
      //
      //       },
      //
      //       error: function (response) {
      //           console.log(response);
      //       }
      //   });
      //
      // });

      $("#update_glcode_btn").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var accountcode = $("#edit_accountcode").val();
        var description = $("#edit_description").val();
        var authorized_password = $("#edit_authorized_password").val();

        if ($.trim(accountcode).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Account code is empty."
        }

        if ($.trim(description).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Account description is empty."
        }

        if ($.trim(authorized_password).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Authorized Pasword is empty."
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

      $('#glaccount-table').DataTable( {
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
      });

      $("#confirm_glcode_btn").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var accountcode = $("#accountcode").val();
        var description = $("#description").val();
        var authorized_password = $("#authorized_password").val();

        if ($.trim(accountcode).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Account code is empty."
        }

        if ($.trim(description).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Account description is empty."
        }

        if ($.trim(authorized_password).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Authorized Pasword is empty."
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
