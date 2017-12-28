@extends('layouts.backend.app')
@section('main-content')

<div class="page-container-fluid">

  <div class="page-content-wrapper">

    <div class="page-head">

      <div class="container-fluid">

        <div class="page-title">
          <h1>Fa Hui Participant List 法会参加者列表</h1>
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
            <span>Fa Hui Participant List</span>
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
                          <a href="#tab_fahui_participant_list" data-toggle="tab">Fa Hui Participant List</a>
                        </li>
                      </ul>
                    <form method="post" target="_blank" action="{{ URL::to('/print/biaowen') }}" class="form-horizontal form-bordered" id="">
                      {!! csrf_field() !!}
                      <div class="tab-content">

                        <div class="tab-pane active" id="tab_fahui_participant_list">

                          <div class="form-body">

                            <div class="form-group">
                              <div class="pull-right">
                                 <button type="submit" class="btn blue">Print Biao Wen</button>
                              </div>

                              <table class="table table-bordered" id="fahui_participant_list_table">
                                <thead>
                                  <tr id="filter">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                  <tr>
                                    <th>Fa Hui</th>
                                    <th>Year</th>
                                    <th>SN</th>
                                    <th>Devotee ID</th>
                                    <th>Participant</th>
                                    <th>Type</th>
                                    <th>Item Description</th>

                                  </tr>
                                </thead>

                                <tbody>
                                  @foreach($participant_list as $participant)
                                  <tr>
                                    <td>{{ $participant['module_chinese_name'] }}</td>
                                    <td>{{ $participant['year'] }}</td>
                                    <td>{{ $participant['sn'] }}</td>
                                    <td>{{ $participant['devotee_id'] }}
                                    </td>
                                    <td>
                                        {{ $participant['participant_name_list'] }}
                                        {{ Form::hidden('display_name_list[]',$participant['display_name_list'])}}
                                    </td>
                                    <td>
                                        {{ $participant['type_chinese_name'] }}</td>
                                    <td>
                                        {{ $participant['display_address_list'] }}
                                        {{ Form::hidden('display_address_list[]',$participant['display_address_list'])}}
                                    </td>
                                  </tr>
                                  @endforeach
                                  </tbody>
                                </table>

                              </div><!-- end form-group -->

                            </div><!-- end form-body -->

                          </div><!-- end tab-pane tab_journallist -->

                          <div class="tab-pane" id="tab_journaldetail">

                          </div><!-- end tab-pane tab_journaldetail -->

                        </div><!-- end tab-content -->

                      </div><!-- end tabbable-bordered -->
                    </form>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

// DataTable
var table = $('#fahui_participant_list_table').removeAttr('width').DataTable( {
  "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
  "order": [[ 0, "asc" ],[1,"desc"],[2,"asc"]],
  columnDefs: [
    { "width": "200px", "targets": 0 },
    { "width": "200px", "targets": 1 },
    { "width": "200px", "targets": 2 },
    { "width": "200px", "targets": 3 },
    { "width": "200px", "targets": 4 },
    { "width": "200px", "targets": 5 },
    { "width": "200px", "targets": 6 }
  ],
  fixedColumns: true
});

$('#fahui_participant_list_table thead tr#filter th').each( function () {
  var title = $('#fahui_participant_list_table thead th').eq( $(this).index() ).text();
  $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
});

// Apply the filter
$("#fahui_participant_list_table thead input").on( 'keyup change', function () {
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

</script>
@stop
