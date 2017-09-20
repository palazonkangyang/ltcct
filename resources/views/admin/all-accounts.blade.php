@extends('admin.layouts.app')

@section('main-content')

	<div class="page-container">

        <div class="page-content-wrapper">

            <div class="page-head">

                <div class="container">

                	<div class="page-title">
                        <h1>All Accounts</h1>

                    </div><!-- end page-title -->

                </div><!-- end container -->

            </div><!-- end page-head -->

            <div class="page-content">

                <div class="container">

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/operator/index">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>All Accounts</span>
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

                        <div class="mt-bootstrap-tables">

                        	<div class="row">

                                <div class="col-md-12">

                                	<div class="portlet light">

                                        <div class="portlet-title">

                                            <div class="caption">
                                                <i class="icon-social-dribbble font-dark hide"></i>
                                                <span class="caption-subject font-dark bold uppercase">All Accounts</span>
                                            </div><!-- end caption -->
                                        </div><!-- end portlet-title -->

                                        <div class="portlet-body">

                                            <table class="table table-bordered" id="all-accounts-table">
                                                <thead>
																									<tr id="filter">
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
																									</tr>
                                                  <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>User Name</th>
                                                    <th>Role</th>
                                                    <th>Actions</th>
                                                  </tr>
                                                </thead>

                                                <tbody>
                                                	@foreach($staffs as $staff)

                                                	<tr>
                                                		<td>{{ $staff-> first_name }}</td>
                                                		<td>{{ $staff-> last_name }}</td>
                                                		<td>{{ $staff-> user_name }}</td>
                                                		<td>{{ $staff-> role_name }}</td>
                                                		<td>
                                                			<a href="{{ URL::to('/admin/account/edit/' . $staff->id) }}" class="btn btn-outline btn-circle btn-sm purple">
                                                				<i class="fa fa-edit"></i> Edit
                                                			</a>

                                                			<a href="{{ URL::to('/admin/account/delete/' . $staff->id) }}" class="btn btn-outline btn-circle dark btn-sm black delete-account">
                                                				<i class="fa fa-trash-o"></i> Delete
                                                			</a>
                                                		</td>
                                                	</tr>

                                                	@endforeach
                                                </tbody>

                                            </table>

                                        </div><!-- end portlet-body -->

                                    </div><!-- end portlet light -->

                                </div><!-- end col-md-12 -->

                            </div><!-- end row -->

                        </div><!-- end mt-bootstrap-tables -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container -->

@stop

@section('script-js')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/custom/common.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript">

		$(function() {
			// DataTable
			var table = $('#all-accounts-table').DataTable({
				"lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
				"order": [[ 0, "desc" ]]
			});

			$('#all-accounts-table thead tr#filter th').each( function () {
						var title = $('#all-accounts-table thead th').eq( $(this).index() ).text();
						$(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
			});

			// Apply the filter
			$("#all-accounts-table thead input").on( 'keyup change', function () {
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

			$("#all-accounts-table").on('click', '.delete-account', function() {
        if (!confirm("Do you confirm you want to delete this record? Note that this process is irreversable.")){
          return false;
        }
      });

			$("#filter input[type=text]:last").css("display", "none");
		});
	</script>

@stop
