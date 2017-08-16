@extends('admin.layouts.app')

@section('main-content')

  <div class="page-container">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
            <h1>All Race</h1>
          </div><!-- end page-title -->

        </div><!-- end container-fluid -->

      </div><!-- end page-head -->

      <div class="page-content">

        <div class="container-fluid">

          <ul class="page-breadcrumb breadcrumb">
              <li>
                  <a href="/admin/dashboard">Home</a>
                  <i class="fa fa-circle"></i>
              </li>
              <li>
                  <span>All Race</span>
              </li>
          </ul>

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

                    <div class="form-group">
                        <a href="/admin/add-race" class="btn blue" style="margin: 0 25px 20px 10px;">Add New Race
                        </a>
                    </div><!-- end form-group -->

                    <div class="col-md-6">
                      <table class="table table-bordered" id="all-race-table">
                          <thead>
                            <tr id="filter">
                              <th width="70%">Race Name</th>
                              <th width="30%">Actions</th>
                            </tr>
                            <tr>
                              <th>Dialect Name</th>
                              <th>Actions</th>
                            </tr>
                          </thead>

                          <tbody>
                            @foreach($race as $r)

                            <tr>
                              <td>{{ $r->race_name }}</td>
                              <td>
                                <a href="{{ URL::to('/admin/race/edit/' . $r->race_id) }}" class="btn btn-outline btn-circle btn-sm purple">
                                  <i class="fa fa-edit"></i> Edit
                                </a>

                                <a href="{{ URL::to('/admin/race/delete/' . $r->race_id) }}" class="btn btn-outline btn-circle dark btn-sm black delete-race">
                                  <i class="fa fa-trash-o"></i> Delete
                                </a>
                              </td>
                            </tr>

                            @endforeach
                          </tbody>

                      </table>
                    </div><!-- end col-md-6 -->

                    <div class="col-md-6">
                    </div><!-- end col-md-6 -->

                    <div class="clearfix">

                    </div>

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

<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript">

		$(function() {
			// DataTable
			var table = $('#all-race-table').DataTable({
				"lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
			});

			$('#all-race-table thead tr#filter th').each( function () {
						var title = $('#all-race-table thead th').eq( $(this).index() ).text();
						$(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
			});

			// Apply the filter
			$("#all-race-table thead input").on( 'keyup change', function () {
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

      $("#all-race-table").on('click', '.delete-race', function() {
        if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
          return false;
        }
      });

      $("#filter input[type=text]:last").css("display", "none");
		});
	</script>

@stop
