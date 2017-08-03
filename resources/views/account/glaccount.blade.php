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
                                <a href="#tab_editglaccount" data-toggle="tab">Edit GL Account</a>
                              </li>
                            </ul>

                            <div class="tab-content">

                              <div class="tab-pane active" id="tab_glaccountlist">

                                <div class="form-body">

                                  <div class="form-group">

                                    <table class="table table-bordered" id="glaccount-table">
                                      <thead>
                                          <tr id="filter">
                                              <th>Account Code</th>
                                              <th>Account Name</th>
                                              <th>Type Name</th>
                                              <th>Chinese Name</th>
                                              <th>Account Status</th>
                                          </tr>
                                          <tr>
                                              <th>Account Code</th>
                                              <th>Account Group</th>
                                              <th>Type Name</th>
                                              <th>Chinese Name</th>
                                              <th>Account Status</th>
                                          </tr>
                                      </thead>

                                      <tbody>
                                          @if(count($glaccount))

                                            @foreach($glaccount as $gl)
                                            <tr>
                                              <td><a href="#tab_editglaccount" data-toggle="tab"
                                                  class="edit-item" id="{{ $gl->glcode_id }}">{{ $gl->accountcode }}</td>
                                              <td>{{ $gl->glcodegroup_name }}</td>
                                              <td>{{ $gl->type_name }}</td>
                                              <td>{{ $gl->chinese_name }}</td>
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

                                        <label class="col-md-3 control-label">Type Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="type_name" value="{{ old('type_name') }}" id="type_name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Chinese Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="chinese_name" value="{{ old('chinese_name') }}" id="chinese_name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Code *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="accountcode" value="{{ old('accountcode') }}" id="accountcode">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Price</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="price" rows="4" id="price" value="{{ old('price') }}">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Job *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="job_id">
                                              @foreach($job as $j)
                                              <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
                                              @endforeach
                                          </select>
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

                                        <label class="col-md-3 control-label">Next SN Number *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="next_sn_number" value="{{ old('next_sn_number') }}" id="next_sn_number">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Receipt Prefix *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="receipt_prefix" value="{{ old('receipt_prefix') }}" id="receipt_prefix">
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
                                        <input type="hidden" name="edit_glcode_id" value="" id="edit_glcode_id">
                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Type Name *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_type_name" value="{{ old('edit_type_name') }}" id="edit_type_name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Chinese Name *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_chinese_name" value="{{ old('edit_chinese_name') }}" id="edit_chinese_name">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Account Code *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_accountcode" value="{{ old('edit_accountcode') }}"
                                            id="edit_accountcode" readonly>
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Price *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_price" value="{{ old('edit_price') }}" id="edit_price">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Job *</label>
                                        <div class="col-md-9">
                                          <select class="form-control" name="edit_job_id" id="edit_job_id" disabled>
                                              @foreach($job as $j)
                                              <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
                                              @endforeach
                                          </select>
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

                                        <label class="col-md-3 control-label">Next SN Number *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_next_sn_number" value="{{ old('edit_next_sn_number') }}" id="edit_next_sn_number">
                                        </div><!-- end col-md-9 -->

                                      </div><!-- end form-group -->

                                      <div class="form-group">

                                        <label class="col-md-3 control-label">Receipt Prefix *</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="edit_receipt_prefix" value="{{ old('edit_receipt_prefix') }}" id="edit_receipt_prefix">
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

    console.log(getParameter('glcode_id'));

    if(window.location.search.length)
    {
      var queryString = window.location.search;
      var glaccount_id = getParameter('glcode_id');

      localStorage.setItem('activeTab', '#tab_editglaccount');

      if(glaccount_id)
      {

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
              $("#edit_type_name").val(response.glaccount['type_name']);
              $("#edit_chinese_name").val(response.glaccount['chinese_name']);
              $("#edit_price").val(response.glaccount['price']);
              $("#edit_job_id").val(response.glaccount['job_id']);
              $("#edit_next_sn_number").val(response.glaccount['next_sn_number']);
              $("#edit_receipt_prefix").val(response.glaccount['receipt_prefix']);

              localStorage.setItem('glocodeid', response.glaccount['glcode_id']);
              localStorage.setItem('glcodegroup_id', response.glaccount['glcodegroup_id']);
              localStorage.setItem('job_id', response.glaccount['job_id']);

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
        localStorage.removeItem('glocodeid');
        localStorage.removeItem('glcodegroup_id');
        localStorage.removeItem('job_id');

        localStorage.removeItem('edit_glcode_id');
        localStorage.removeItem('edit_glcodegroup_id');
        localStorage.removeItem('edit_job_id');
    }

    else
    {
      if(localStorage.getItem('edit_glcode_id'))
      {
        var edit_glcode_id = localStorage.getItem('edit_glcode_id');
        var edit_glcodegroup_id = localStorage.getItem('edit_glcodegroup_id');
        var edit_job_id = localStorage.getItem('edit_job_id');

        console.log(edit_glcode_id);

        $("#edit_glcode_id").val(edit_glcode_id);
        $("#edit_job_id").val(edit_job_id);
        $("#edit_glcodegroup_id").val(edit_glcodegroup_id);
      }

      if(localStorage.getItem('glocodeid'))
      {
        var glocodeid = localStorage.getItem('glocodeid');
        var glcodegroup_id = localStorage.getItem('glcodegroup_id');
        var job_id = localStorage.getItem('job_id');

        console.log(glocodeid);

        $("#edit_glcode_id").val(glocodeid);
        $("#edit_glcodegroup_id").val(glcodegroup_id);
        $("#edit_job_id").val(job_id);
      }
    }

    $("#glaccount-table").on('click','.edit-item',function(e) {

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#edit-glaccount").addClass("active");

      var glcode_id = $(this).attr("id");

      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          glcode_id: glcode_id
      };

      $.ajax({
          type: 'GET',
          url: "/account/glcode-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            alert(JSON.stringify(response.glcode));

            localStorage.setItem('edit_glcode_id', response.glcode['glcode_id']);
            localStorage.setItem('edit_glcodegroup_id', response.glcode['glcodegroup_id']);
            localStorage.setItem('edit_job_id', response.glcode['job_id']);

            if(localStorage.getItem('edit_glcode_id'))
            {
                var edit_glcode_id = localStorage.getItem('edit_glcode_id');
                var edit_glcodegroup_id = localStorage.getItem('edit_glcodegroup_id');
                var edit_job_id = localStorage.getItem('edit_job_id');
            }

            $("#edit_glcode_id").val(edit_glcode_id);
            $("#edit_type_name").val(response.glcode['type_name']);
            $("#edit_chinese_name").val(response.glcode['chinese_name']);
            $("#edit_accountcode").val(response.glcode['accountcode']);
            $("#edit_price").val(response.glcode['price']);
            $("#edit_job_id").val(edit_job_id);
            $("#edit_glcodegroup_id").val(edit_glcodegroup_id);
            $("#edit_next_sn_number").val(response.glcode['next_sn_number']);
            $("#edit_receipt_prefix").val(response.glcode['receipt_prefix']);
          },

          error: function (response) {
              console.log(response);
          }
      });

    });

    $("#update_glcode_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var type_name = $("#edit_type_name").val();
      var chinese_name = $("#edit_chinese_name").val();
      var accountcode = $("#edit_accountcode").val();
      var price = $("#edit_price").val();
      var next_sn_number = $("#edit_next_sn_number").val();
      var receipt_prefix = $("#edit_receipt_prefix").val();
      var authorized_password = $("#edit_authorized_password").val();

      if ($.trim(type_name).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Type Name field is empty."
      }

      if ($.trim(chinese_name).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Chinese name field is empty."
      }

      if ($.trim(accountcode).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Account code field is empty."
      }

      if ($.trim(price).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Price field is empty."
      }

      if ($.trim(next_sn_number).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Next sn number field is empty."
      }

      if ($.trim(receipt_prefix).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Receipt prefix field is empty."
      }

      if ($.trim(authorized_password).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Authorized pasword field is empty."
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
    var table = $('#glaccount-table').DataTable({
      "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
    });

    $('#glaccount-table thead tr#filter th').each( function () {
          var title = $('#glaccount-table thead th').eq( $(this).index() ).text();
          $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#glaccount-table thead input").on( 'keyup change', function () {
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

    $("#confirm_glcode_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var accountcode = $("#accountcode").val();
      var type_name = $("#type_name").val();
      var chinese_name = $("#chinese_name").val();
      var price = $("#price").val();
      var next_sn_number = $("#next_sn_number").val();
      var receipt_prefix = $("#receipt_prefix").val();
      var authorized_password = $("#authorized_password").val();

      if ($.trim(type_name).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Type name field is empty."
      }

      if ($.trim(chinese_name).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Chinese name field is empty."
      }

      if ($.trim(accountcode).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Account code is empty."
      }

      if ($.trim(price).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Price field is empty."
      }

      if ($.trim(next_sn_number).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Next SN Number field is empty."
      }

      if ($.trim(receipt_prefix).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Receipt Prefix field is empty."
      }

      if ($.trim(authorized_password).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Authorized Pasword field is empty."
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
