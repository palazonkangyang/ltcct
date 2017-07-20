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
                  <span>Chart All Accounts</span>
              </li>
          </ul>

          <div class="page-content-inner">

            <div class="inbox">

              <div class="row">

                <div class="col-md-12">

                  <div class="portlet light">

                    <div class="portlet-title">

                      <div class="caption">
                        <i class="icon-social-dribbble font-blue-sharp"></i>
                        <span class="caption-subject font-blue-sharp bold uppercase">Chart All Accounts</span>
                      </div><!-- end caption -->

                    </div><!-- end portlet-title -->

                    <div class="portlet-body">

                      <div id="tree_1" class="tree-demo">

                        <ul>
                          <li data-jstree='{ "opened" : true }'>Root
                            <ul>

                              @foreach($glcodegroup as $gcg)

                                @php $glcodegroup_id = $gcg->glcodegroup_id; @endphp

                                <li data-jstree='{ "opened" : false }'>
                                  <a href="/account/new/{{ $gcg->$glcodegroup_id }}">{{ $gcg->name }}</a>

                                  <ul>

                                    @foreach($glcode as $gc)

                                      @if($glcodegroup_id == $gc->glcodegroup_id)
                                        <li data-jstree='{ "type" : "file" }'>
                                          <a href="/account/new-glaccount#tab_editglaccount" data-toggle="tab"
                                            class="edit-glaccount" id="{{ $gc->glcode_id }}">{{ $gc->accountcode }}</a>
                                        </li>

                                      @else

                            							@php continue; @endphp

                                      @endif

                                    @endforeach

                                  </ul>
                                </li>

                              @endforeach

                            </ul>
                          </li>
                        </ul>

                      </div><!-- end tree_1 -->

                    </div><!-- end portlet-body -->

                  </div><!-- end portlet light -->

                  <div class="clearfix"></div><!-- end clearfix -->

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

    <script src="{{asset('js/ui-tree.min.js')}}"></script>

@stop
