@extends('admin.layouts.app')
@section('main-content')
<div class="page-container">
  <div class="page-content-wrapper">
    <div class="page-head">
      <div class="container-fluid">
        <div class="page-title">
          <h1>FaHui Setting</h1>
        </div>{{-- end page-title --}}
      </div>{{-- end container --}}
    </div>{{-- end page-head --}}
  <div class="page-content">
    <div class="container-fluid">
    <ul class="page-breadcrumb breadcrumb">
      <li>
        <a href="/operator/index">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>FaHui Setting</span>
      </li>
    </ul>
    <div class="validation-error">
    </div>{{-- end validation-error --}}
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
          <form role="form" method="post" action="{{ URL::to('/admin/fahui-setting/update-fahui-setting') }}">
          {!! csrf_field() !!}
            <div class="portlet light">

              {{-- xiao zai setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Xiao Zai Da Fa Hui 消灾大法会</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <h4>Price Setting 价格设定</h4>
                    <div class="col-md-2">
                      <label>合家</label>
                      <input type="number" class="form-control" name="xiaozai_price_hj"  id="xiaozai_price_hj" value="{{ $xiaozai_price_hj }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                    <br/>
                    <div class="col-md-2">
                      <label>个人</label>
                      <input type="number" class="form-control" name="xiaozai_price_gr"  id="xiaozai_price_gr" value="{{ $xiaozai_price_gr }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                    <br/>
                    <div class="col-md-2">
                      <label>公司</label>
                      <input type="number" class="form-control" name="xiaozai_price_company"  id="xiaozai_price_company" value="{{ $xiaozai_price_company }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                    <br/>
                    <div class="col-md-2">
                      <label>小贩</label>
                      <input type="number" class="form-control" name="xiaozai_price_stall"  id="xiaozai_price_stall" value="{{ $xiaozai_price_stall }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                    <br/>
                    <div class="col-md-2">
                      <label>车辆</label>
                      <input type="number" class="form-control" name="xiaozai_price_car"  id="xiaozai_price_car" value="{{ $xiaozai_price_car }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                    <br/>
                    <div class="col-md-2">
                      <label>船只</label>
                      <input type="number" class="form-control" name="xiaozai_price_ship"  id="xiaozai_price_ship" value="{{ $xiaozai_price_ship }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- xiao zai setting end --}}

              {{-- * setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Qian Fo Fa Hui 千佛法会</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <div class="col-md-2">
                      {{--
                      <label>*</label>
                      <input type="text" class="form-control" name="*" value="" id="*">
                      --}}
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- * setting end --}}

              {{-- * setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Da Bei Fa Hui 大悲法会</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <div class="col-md-2">
                      {{--
                      <label>*</label>
                      <input type="text" class="form-control" name="*" value="" id="*">
                      --}}
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- * setting end --}}

              {{-- * setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Yao Shi Fa Hui 药师法会</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <div class="col-md-2">
                      {{--
                      <label>*</label>
                      <input type="text" class="form-control" name="*" value="" id="*">
                      --}}
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- * setting end --}}

              {{-- * setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Qi Fu Fa Hui 祈福法会</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <h4>Price Setting 价格设定</h4>
                    <div class="col-md-2">
                      <label>个人</label>
                      <input type="number" class="form-control" name="qifu_price_gr"  id="qifu_price_gr" value="{{ $qifu_price_gr }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- * setting end --}}

              {{-- * setting start --}}
              <div class="portlet-title">
                <div class="caption font-red-sunglo">
                  <i class="icon-settings font-red-sunglo"></i>
                  <span class="caption-subject bold uppercase">Kong Dan 孔诞</span>
                </div>{{-- end caption font-red-sunglo --}}
              </div>{{-- end portlet-title --}}
              <div class="portlet-body form">
                <div class="form-body">
                  <div class="form-group">
                    <h4>Price Setting 价格设定</h4>
                    <div class="col-md-2">
                      <label>个人</label>
                      <input type="number" class="form-control" name="kongdan_price_gr"  id="kongdan_price_gr" value="{{ $kongdan_price_gr }}" min="0" max="1000" step="any">
                    </div>{{-- end col-md-2 --}}
                    <div class="col-md-10">
                    </div>{{-- end col-md-10 --}}
                    <div class="clearfix">
                    </div>{{-- end clearfix --}}
                  </div>{{-- end form-group --}}
                </div>{{-- end form-body --}}
              </div>{{-- end portlet-body form --}}
              {{-- * setting end --}}

              <hr/>
              <div class="form-actions">
                <button type="submit" class="btn blue" id="update">Update</button>
                <button type="button" class="btn default" id="cancel">Cancel</button>
              </div>{{-- end form-actions --}}
            </div>{{-- end portlet light --}}
          </form>
        </div>{{-- end col-md-12 --}}
      </div>{{-- end row --}}
    </div>{{-- end page-content-inner --}}
    </div>{{-- end container --}}
  </div>{{-- end page-content --}}
  </div>{{-- end page-content-wrapper --}}
</div>{{-- end page-container --}}
@stop
@section('script-js')
  <script type="text/javascript">
    $(function() {
      $("#update").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        $(".alert-success").remove();
        $(".validation-error").empty();

        var minimum_amount = $("#minimum_amount").val();

        if ($.trim(minimum_amount).length <= 0)
        {
            //validationFailed = true;
            //errors[count++] = "Amount field is empty."
        }

        if (validationFailed)
        {
            var errorMsgs = '';

            for(var i = 0; i < count; i++)
            {
                errorMsgs = errorMsgs + errors[i] + "<br/>";
            }

            $('html,body').animate({ scrollTop: 0 }, 'slow');

            $(".validation-error").addClass("bg-danger alert alert-error")
            $(".validation-error").html(errorMsgs);

            return false;
        }

        else
        {
            $(".validation-error").removeClass("bg-danger alert alert-error")
            $(".validation-error").empty();
        }
      });
    });
  </script>
@stop
