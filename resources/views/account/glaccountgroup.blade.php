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
                                <a href="#tab_glaccountlistgroup" data-toggle="tab">GL Account Group List</a>
                              </li>

                              <li>
                                <a href="#tab_newglaccountgroup" data-toggle="tab">New GL Account Group</a>
                              </li>
                              <li id="edit-glaccountgroup" class="disabled">
                                <a href="#tab_editglaccountgroup" data-toggle="tab">Edit GL Account Group</a>
                              </li>
                            </ul>

                            <div class="tab-content">

                              <div class="tab-pane active" id="tab_glaccountlistgroup">

                                <div class="form-body">

                                  <div class="form-group">

                                    <table class="table table-bordered" id="glaccountgroup-table">
                                      <thead>
                                          <tr id="filter">
                                              <th>Account Group Code</th>
                                              <th>Account Group Description</th>
                                              <th>Balancing Side</th>
                                              <th>Account Group Status</th>
                                          </tr>
                                          <tr>
                                              <th>Account Group Code</th>
                                              <th>Account Group Description</th>
                                              <th>Balancing Side</th>
                                              <th>Account Group Status</th>
                                          </tr>
                                      </thead>

                                      <tbody>
                                        @if(count($glaccountgroup))

                                          @foreach($glaccountgroup as $gl)
                                          <tr>
                                            <td>{{ $gl->name }}</td>
                                            <td>{{ $gl->description }}</td>
                                            <td class="text-uppercase">{{ $gl->balancesheet_side }}</td>
                                            <td class="text-capitalize">{{ $gl->status }}</td>
                                          </tr>
                                          @endforeach

                                        @endif
                                      </tbody>
                                    </table>
                                  </div><!-- end form-group -->

                                </div><!-- end form-body -->

                              </div><!-- end tab-pane tab-glaccount-group-list -->

                              <div class="tab-pane" id="tab_newglaccountgroup">

                                <div class="form-body">

                                  <div class="col-md-6">

                                    <form method="post" action="{{ URL::to('/account/new-glaccountgroup') }}"
                                      class="form-horizontal form-bordered">

                                      {!! csrf_field() !!}

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Group Name *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Description *</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="description" rows="4" id="description">{{ old('description') }}</textarea>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Balancing Side *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="balancesheet_side">
                                              <option value="ap">AP</option>
                                              <option value="ar">AR</option>
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Group Status *</label>
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
                                            <button type="submit" class="btn blue" id="confirm_gl_btn">Confirm
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

                              <div class="tab-pane" id="tab_editglaccountgroup">

                                <div class="form-body">

                                  <div class="col-md-6">

                                    <form method="post" action="{{ URL::to('/account/update-glaccountgroup') }}"
                                      class="form-horizontal form-bordered">

                                      {!! csrf_field() !!}

                                      <div class="form-group">
                                        <input type="hidden" name="glcodegroup_id" value="" id="edit_glcodegroup_id">
                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Group Name *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_name" value="{{ old('edit_name') }}" id="edit_name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Description *</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="edit_description" rows="4" id="edit_description">{{ old('edit_description') }}</textarea>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Balancing Side *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="balancesheet_side" id="edit_balancesheet_side" disabled>
                                              <option value="ap">AP</option>
                                              <option value="ar">AR</option>
                                          </select>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Group Status *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="status" id="edit_status" disabled>
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
                                            <button type="submit" class="btn blue" id="update_gl_btn">Update
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

                              </div><!-- end tab-pane tab_newglaccountgroup -->

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

      $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
      });

      function getParameter(theParameter) {
        var params = window.location.search.substr(1).split('&');

        for (var i = 0; i < params.length; i++) {

          var p=params[i].split('=');
        	if (p[0] == theParameter) {
        	  return decodeURIComponent(p[1]);
        	}

        }
        return false;
      }

      if(window.location.search.length)
      {
        var queryString = window.location.search;
        var glaccountgroup_id = getParameter('glaccountgroup_id');

        localStorage.setItem('activeTab', '#tab_editglaccountgroup');

        if(glaccountgroup_id)
        {
          var formData = {
              _token: $('meta[name="csrf-token"]').attr('content'),
              glaccountgroup_id: glaccountgroup_id
          };

          $("#edit_name").val('');
          $("#edit_description").val('');

          $.ajax({
              type: 'GET',
              url: "/account/edit-glaccountgroup",
              data: formData,
              dataType: 'json',
              success: function(response)
              {
                $("#edit_glcodegroup_id").val(response.glaccountgroup['glcodegroup_id']);
                $("#edit_name").val(response.glaccountgroup['name']);
                $("#edit_description").val(response.glaccountgroup['description']);
                $("#edit_balancesheet_side").val(response.glaccountgroup['balancesheet_side']);
                $("#edit_status").val(response.glaccountgroup['status']);

                localStorage.setItem('glcodegroup_id', response.glaccountgroup['glcodegroup_id']);
                localStorage.setItem('balancesheet_side', response.glaccountgroup['balancesheet_side']);
                localStorage.setItem('status', response.glaccountgroup['status']);
              },

              error: function (response) {
                  console.log(response);
              }
          });
        }
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
          localStorage.removeItem('glcodegroup_id');
          localStorage.removeItem('balancesheet_side');
          localStorage.removeItem('status');
      }

      else
      {
          var glcodegroup_id = localStorage.getItem('glcodegroup_id');
          var balancesheet_side = localStorage.getItem('balancesheet_side');
          var status = localStorage.getItem('status');
      }

      if(glcodegroup_id)
      {
        $("#edit_glcodegroup_id").val(glcodegroup_id);
        $("#edit_balancesheet_side").val(balancesheet_side);
        $("#edit_status").val(status);
      }

      $("#update_gl_btn").click(function() {
        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var name = $("#edit_name").val();
        var description = $("#edit_description").val();
        var authorized_password = $("#edit_authorized_password").val();

        if ($.trim(name).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Group name is empty."
        }

        if ($.trim(description).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Group description is empty."
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

      // DataTable
      var table = $('#glaccountgroup-table').DataTable({
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
      });

      $('#glaccountgroup-table thead tr#filter th').each( function () {
            var title = $('#glaccountgroup-table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
      });

      // Apply the filter
      $("#glaccountgroup-table thead input").on( 'keyup change', function () {
          table
              .column( $(this).parent().index()+':visible' )
              .search( this.value )
              .draw();
      });

      function stopPropagation(evt) {
        if (evt.stopPropagation !== undefined) {
          evt.stopPropagation();
        } else {
          evt.cancelBubble = true;
        }
      }

      $("#confirm_gl_btn").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var name = $("#name").val();
        var description = $("#description").val();
        var authorized_password = $("#authorized_password").val();

        if ($.trim(name).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Group name is empty."
        }

        if ($.trim(description).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Group description is empty."
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
