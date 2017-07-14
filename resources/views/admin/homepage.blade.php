@extends('admin.layouts.app')

@section('main-content')

    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Form Stuff</span>
            </li>
        </ul>

        <div class="page-toolbar">

            <div class="btn-group pull-right">
                
                <button type="button" class="btn green btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Actions
                    <i class="fa fa-angle-down"></i>
                </button>
                            
                <ul class="dropdown-menu pull-right" role="menu">
                    <li>
                        <a href="#">
                            <i class="icon-bell"></i> Action</a>
                    </li>
                    <li>
                        <a href="#">
                        <i class="icon-shield"></i> Another action</a>
                    </li>
                    <li>
                        <a href="#">
                        <i class="icon-user"></i> Something else here</a>
                    </li>
                    <li class="divider"> </li>
                    <li>
                        <a href="#">
                            <i class="icon-bag"></i> Separated link</a>
                    </li>
                </ul>

            </div><!-- end btn-group pull-right -->

        </div><!-- end page-toolbar -->

    </div><!-- end page-bar -->

    <h1 class="page-title"> Bootstrap Form Controls
        <small>bootstrap inputs, input groups, custom checkboxes and radio controls and more</small>
    </h1>

    <div class="row">

        <div class="col-md-6">

        </div><!-- end col-md-6 -->
    </div><!-- end row -->
    
@stop