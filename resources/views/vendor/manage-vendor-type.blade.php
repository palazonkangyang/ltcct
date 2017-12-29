@extends('layouts.backend.app')

@section('main-content')

<div class="page-container-fluid">

  <div class="page-content-wrapper">

    <div class="page-head">

      <div class="container-fluid">

        <div class="page-title">

          <h1>AP Vendor Type</h1>

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
            <span>AP Vendor Type</span>
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
                          <a href="#tab_vendorlist" data-toggle="tab">AP Vendor Type List</a>
                        </li>
                        <li>
                          <a href="#tab_newvendor" data-toggle="tab">New AP Vendor Type</a>
                        </li>
                        <li id="edit-vendor" class="disabled">
                          <a href="#tab_editvendor" data-toggle="tab">Edit AP Vendor Type</a>
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
                                  </tr>
                                  <tr>
                                    <th>Vendor Type Name</th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @foreach($vendor as $data)
                                  <tr>
                                    <td>
                                      <a href="#tab_editvendor" data-toggle="tab"
                                      class="edit-item" id="{{ $data->ap_vendor_type_id }}">{{ $data->vendor_type_name }}</a>
                                    </td>
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

                              <form method="post" action="{{ URL::to('/vendor/new-vendor-type') }}"
                              class="form-horizontal form-bordered">

                              {!! csrf_field() !!}

                              <div class="form-group" style="margin-bottom: 30px;">
                                <label class="col-md-3">Vendor Type Name *</label>
                                <div class="col-md-9">
                                  <input type="text" class="form-control" name="vendor_type_name" value="{{ old('vendor_type_name') }}" id="vendor_name">
                                </div><!-- end col-md-9 -->
                              </div><!-- end form-group -->

                              <div class="clearfix">
                              </div><!-- end clearfix -->

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

                            <form method="post" action="{{ URL::to('/vendor/update-vendor-type') }}"
                            class="form-horizontal form-bordered">

                            {!! csrf_field() !!}

                            <div class="form-group">
                              <input type="hidden" name="edit_ap_vendor_type_id" id="edit_ap_vendor_type_id" value="">
                            </div><!-- end form-group -->

                            <div class="form-group">
                              <label class="col-md-3">Vendor Type Name *</label>
                              <div class="col-md-9">
                                <input type="text" class="form-control" name="edit_vendor_type_name" value="{{ old('edit_vendor_type_name') }}" id="edit_vendor_type_name">
                              </div><!-- end col-md-9 -->
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
    localStorage.removeItem('ap_vendor_type_id');
  }

  else
  {
    if(localStorage.getItem('ap_vendor_type_id'))
    {
      var ap_vendor_type_id = localStorage.getItem('ap_vendor_type_id');
    }

    $("#edit_ap_vendor_type_id").val(ap_vendor_type_id);
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
  var table = $('#vendor-table').removeAttr('width').DataTable( {
    "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
    "order": [[ 0, "asc" ]],
    columnDefs: [
      { width: 500, targets: 0 }
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

  $("#confirm_btn").click(function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var vendor_name = $("#vendor_name").val();

    if($.trim(vendor_name).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Vendor Name is empty.";
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
    $("#appendRow").empty();

    $("#edit_ap_vendor_type_id").val('');
    $("#edit_type_vendor_name").val('');

    $(".nav-tabs > li:first-child").removeClass("active");
    $("#edit-vendor").addClass("active");

    var vendor_type_id = $(this).attr("id");

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      vendor_type_id: vendor_type_id
    };

    $.ajax({
      type: 'GET',
      url: "/vendor/vendor-type-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        localStorage.setItem('ap_vendor_type_id', response.vendor_type['ap_vendor_type_id']);
        if(localStorage.getItem('ap_vendor_type_id'))
        {
          var ap_vendor_type_id = localStorage.getItem('ap_vendor_type_id');
        }

        $("#edit_ap_vendor_type_id").val(ap_vendor_type_id);
        $("#edit_vendor_type_name").val(response.vendor_type['vendor_type_name']);
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

    var vendor_type_name = $("#edit_vendor_type_name").val();

    if($.trim(vendor_type_name).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Vendor Type Name is empty.";
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
