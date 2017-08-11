@extends('admin.layouts.app')

@section('main-content')

  <div class="page-container">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
            <h1>Add New Race</h1>
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
                  <span>Add New Race</span>
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

                    <form role="form" method="post" action="{{ URL::to('/admin/add-race') }}">
                        {!! csrf_field() !!}

                      <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-6">
                              <label>Race Name</label>
                              <input name="race_name" class="form-control" placeholder="" type="text" value="{{ old('race_name') }}">
                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                            </div><!-- end col-md-6 -->

                            <div class="clearfix">
                            </div><!-- end clearfix -->

                        </div><!-- end form-group -->
                      </div><!-- end form-body -->

                      <div class="form-actions">
                          <button type="submit" class="btn blue">Create</button>
                          <button type="reset" class="btn default">Cancel</button>
                      </div><!-- end form-actions -->

                    </form>

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
