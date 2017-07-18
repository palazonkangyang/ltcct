@extends('layouts.backend.app')

@section('main-content')

    <div class="page-container-fluid">

        <div class="page-content-wrapper">

          <div class="page-head">

              <div class="container-fluid">

                  <div class="page-title">

                      <h1>Event  Calendar 庆典节目表</h1>

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
                          <span>Event  Calendar</span>
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

                                            <div class="form-body">

                                                <div class="form-group">

                                                    <h4>Event  Calendar 庆典节目表</h4>

                                                    <table class="table table-bordered" id="festive-event-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Date From 阴历</th>
                                                                <th>Date To 阴历</th>
                                                                <th>Lunar Date 阳历</th>
                                                                <th>Event 节日</th>
                                                                <th>Time 时间</th>
                                                                <th>Shuwen Title 文疏</th>
                                                                <th>Display</th>
                                                            </tr>
                                                        </thead>

                                                    </table>

                                                </div><!-- end form-group -->

                                            </div><!-- end form-body -->

                                        </div><!-- end portlet-body -->

                                  </div><!-- end portlet -->

                              </div><!-- end col-md-12 -->

                          </div><!-- end row -->

                      </div><!-- end inbox -->

                  </div><!-- end page-content-inner -->

              </div><!-- end container-fluid -->

          </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop
