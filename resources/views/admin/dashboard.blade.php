@extends('admin.layouts.app')

@section('main-content')

    <div class="page-container">
                        
        <div class="page-content-wrapper">
            
            <div class="page-head">
                
                <div class="container">

                    <div class="page-title">
                        <h1>Dashboard</h1>

                    </div><!-- end page-title -->

                </div><!-- end container -->

            </div><!-- end page-head -->

            <div class="page-content">

                <div class="container">

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/admin/dashboard">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Dashboard</span>
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
                                                <span class="caption-subject font-dark bold uppercase"> Welcome to dashboard </span>
                                            </div><!-- end caption -->
                                        </div><!-- end portlet-title -->
                                        
                                        <div class="portlet-body">
                                            
                                         

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