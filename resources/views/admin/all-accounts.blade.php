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
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>All Accounts</span>
                        </li>
                    </ul>

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
                                            
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                    	<th>#</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>User Name</th>
                                                        <th>Role</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>

                                                @php

                                                	$count = 1;

                                               	@endphp

                                                <tbody>
                                                	@foreach($staffs as $staff)

                                                	<tr>
                                                		<td>{{ $count++ }}</td>
                                                		<td>{{ $staff-> first_name }}</td>
                                                		<td>{{ $staff-> last_name }}</td>
                                                		<td>{{ $staff-> user_name }}</td>
                                                		<td>{{ $staff-> role_name }}</td>
                                                		<td>
                                                			<a href="{{ URL::to('/admin/account/edit/' . $staff->id) }}" class="btn btn-outline btn-circle btn-sm purple">
                                                				<i class="fa fa-edit"></i> Edit 
                                                			</a>

                                                			<a href="{{ URL::to('/admin/account/delete/' . $staff->id) }}" class="btn btn-outline btn-circle dark btn-sm black">
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