@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

          <div class="container-fluid">

              <div class="page-title">

                  <h1>AP Vendor</h1>

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
                  <span>AP Vendor</span>
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
                            <a href="#tab_vendorlist" data-toggle="tab">AP Vendor List</a>
                          </li>
                          <li>
                            <a href="#tab_newvendor" data-toggle="tab">New AP Vendor</a>
                          </li>
                          <li id="edit-vendor" class="disabled">
                            <a href="#tab_editvendor" data-toggle="tab">Edit AP Vendor</a>
                          </li>
                        </ul>

                        <div class="tab-content">

                          <div class="tab-pane active" id="tab_vendorlist">

                            <div class="form-body">

                              <div class="form-group">

                                <table class="table table-bordered" id="vendor-table">
                                  <thead>
                                    <tr id="filter">
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                    </tr>
                                    <tr>
                                      <th>Vendor Name</th>
                                      <th>Description</th>
                                      <th>Payable</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    @foreach($vendor as $data)
                                    <tr>
                                      <td>
                                        <a href="#tab_editvendor" data-toggle="tab"
                                          class="edit-item" id="{{ $data->ap_vendor_id }}">{{ $data->vendor_name }}</a>
                                      </td>
                                      <td>{{ $data->description }}</td>
                                      <td>S$ {{ $data->payable }}</td>
                                      <!-- <td>
                                        <a href="{{ URL::to('/vendor/delete/' . $data->ap_vendor_id) }}" class="btn btn-outline btn-circle dark btn-sm black delete-item">
                                          <i class="fa fa-trash-o"></i> Delete
                                        </a>
                                      </td> -->
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>

                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_vendorlist -->

                          <div class="tab-pane" id="tab_newvendor">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/vendor/new-vendor') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group" style="margin-bottom: 30px;">
                                    <label class="col-md-3 control-label">Vendor Name *</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name') }}" id="vendor_name">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group" style="margin-bottom: 30px;">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}" id="description">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="clearfix">
                                  </div><!-- end clearfix -->

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
                                        <input type="password" class="form-control" name="authorized_password" id="authorized_password" autocomplete="new-password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-4 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="confirm_btn">Confirm
                                        </button>
                                        <button type="button" class="btn default">Cancel</button>
                                      </div><!-- end form-actions -->
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                </form>

                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end form-body -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_newvendor -->

                          <div class="tab-pane" id="tab_editvendor">

                            <div class="form-body">

                              <div class="col-md-6">

                                <form method="post" action="{{ URL::to('/vendor/update-vendor') }}"
                                  class="form-horizontal form-bordered">

                                  {!! csrf_field() !!}

                                  <div class="form-group">
                                    <input type="hidden" name="edit_ap_vendor_id" id="edit_ap_vendor_id" value="">
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Vendor Name *</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_vendor_name" value="{{ old('edit_vendor_name') }}" id="edit_vendor_name">
                                    </div><!-- end col-md-9 -->
                                  </div><!-- end form-group -->

                                  <div class="form-group">
                                    <label class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" name="edit_description" value="{{ old('edit_description') }}" id="edit_description">
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
                                        <input type="password" class="form-control" name="edit_authorized_password" id="edit_authorized_password" autocomplete="new-password">
                                      </div><!-- end col-md-6 -->
                                    </div><!-- end col-md-6 -->

                                  </div><!-- end form-group -->

                                  <div class="form-group">

                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                      <div class="form-actions pull-right">
                                        <button type="submit" class="btn blue" id="update_btn">Update
                                        </button>
                                        <button type="button" class="btn default">Cancel</button>
                                      </div><!-- end form-actions -->
                                    </div><!-- end col-md-9 -->

                                  </div><!-- end form-group -->

                                </form>

                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end form-body -->

                            <div class="clearfix"></div><!-- end clearfix -->

                          </div><!-- end tab-pane tab_editjournalentry -->

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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>

<script type="text/javascript">
  $(function() {

    if ( $('.alert-success').children().length > 0 ) {
      localStorage.removeItem('activeTab');
      localStorage.removeItem('ap_vendor_id');
    }

    else
    {
      if(localStorage.getItem('ap_vendor_id'))
      {
        var ap_vendor_id = localStorage.getItem('ap_vendor_id');
      }

      $("#edit_ap_vendor_id").val(ap_vendor_id);
    }

    $(".nav-tabs > li").click(function(){
      if($(this).hasClass("disabled"))
      return false;
    });

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

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

    // DataTable
    // var table = $('#vendor-table').DataTable({
    //   "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
    // });

    var table = $('#vendor-table').removeAttr('width').DataTable( {
        "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
        columnDefs: [
            { width: 500, targets: 0 },
            { width: 500, targets: 1 },
            { width: 200, targets: 2 }
        ]
    } );

    $('#vendor-table thead tr#filter th').each( function () {
          var title = $('#vendor-table thead th').eq( $(this).index() ).text();
          $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
    });

    // Apply the filter
    $("#vendor-table thead input").on( 'keyup change', function () {
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

    // $("#filter input[type=text]:last").css("display", "none");

    $("#confirm_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var vendor_name = $("#vendor_name").val();
      var authorized_password = $("#authorized_password").val();

      if($.trim(vendor_name).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Vendor Name is empty.";
      }

      if ($.trim(authorized_password).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.";
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

    $("#vendor-table").on('click','.edit-item',function(e) {

      $(".alert-success").remove();

      $(".nav-tabs > li:first-child").removeClass("active");
      $("#edit-vendor").addClass("active");

      var vendor_id = $(this).attr("id");

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        vendor_id: vendor_id
      };

      $.ajax({
          type: 'GET',
          url: "/vendor/vendor-detail",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            localStorage.setItem('ap_vendor_id', response.vendor['ap_vendor_id']);

            if(localStorage.getItem('ap_vendor_id'))
            {
              var ap_vendor_id = localStorage.getItem('ap_vendor_id');
            }

            $("#edit_ap_vendor_id").val(ap_vendor_id);
            $("#edit_vendor_name").val(response.vendor['vendor_name']);
            $("#edit_description").val(response.vendor['description']);
          },

          error: function (response) {
            console.log(response);
          }
      });

    });

    $("#update_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var vendor_name = $("#edit_vendor_name").val();
      var authorized_password = $("#edit_authorized_password").val();

      if($.trim(vendor_name).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Vendor Name is empty.";
      }

      if ($.trim(authorized_password).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.";
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

    $("#vendor-table").on('click', '.delete-item', function() {
      if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
        return false;
      }
    });

  });
</script>


@stop
