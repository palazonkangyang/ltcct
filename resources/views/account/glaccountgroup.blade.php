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
                                <a href="#tab_xiangyou" data-toggle="tab">GL Account Group List</a>
                              </li>

                              <li id="members">
                                <a href="#tab_ciji" data-toggle="tab">New GL Account Group</a>
                              </li>
                              
                            </ul>
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
