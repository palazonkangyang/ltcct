@extends('layouts.backend.app')

@section('main-content')

    <div class="page-container-fluid">

        <div class="page-content-wrapper">

            <div class="page-head">

                <div class="container-fluid">

                    <div class="page-title">

                        <h1>Edit Devotee</h1>

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
                            <span>Edit Devotee</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                        <div class="inbox">

                            <div class="row">

                                @include('layouts.partials.sidebar')

                                <div class="col-md-9">

                                    <div class="inbox-body">

                                        <div class="inbox-header">
                                            <h1 class="pull-left">Edit Devotee</h1>
                                        </div>

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

                                        <div class="inbox-content portlet light" style="overflow: hidden;">

                                            <form class="form-horizontal form-bordered" method="post"
                                                action="{{ URL::to('/operator/devotee/edit/' . $devotee->devotee_id) }}">

                                                {!! csrf_field() !!}

                                            <div class="form-body portlet-body form">

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Title</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="title">
                                                                <option value="mr">Mr</option>
                                                                <option value="miss">Miss</option>
                                                                <option value="madam">Madam</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Chinese Name</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" name="chinese_name"
                                                                    value="{{ $devotee->chinese_name }}">
                                                            </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">English Name</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" name="english_name"
                                                                    value="{{ $devotee->english_name }}">
                                                            </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Contact #</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="contact"
                                                                value="{{ $devotee->contact }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                    <label class="col-md-3 control-label">Guiyi Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="guiyi_name"
                                                            value="{{ $devotee->guiyi_name }}">
                                                    </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Address - House No</label>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" name="address_houseno"
                                                                value="{{ $devotee->address_houseno }}">
                                                        </div><!-- end col-md-3 -->

                                                        <label class="col-md-1 control-label">Unit</label>

                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" name="address_unit1"
                                                                value="{{ old('address_unit1') }}">
                                                        </div><!-- end col-md-2 -->

                                                        <label class="col-md-1">-</label>

                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" name="address_unit2"
                                                                value="{{ old('address_unit2') }}">
                                                        </div><!-- end col-md-2 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Address - Street</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="address_street">
                                                                <option value="Ang Mo Kio Ave 10">Ang Mo Kio Ave 10</option>
                                                                <option value="Ang Mo Kio Ave 8">Ang Mo Kio Ave 8</option>
                                                                <option value="Bishan Ave 4">Bishan Ave 4</option>
                                                                <option value="Rehill Ave 1">Rehill Ave 1</option>
                                                                <option value="Clementi Ave 3">Clementi Ave 3</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Address - Building</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="address_building"
                                                                value="{{ $devotee->address_building }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                    <label class="col-md-3 control-label">Address - Postal</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="address_postal"
                                                            value="{{ $devotee->address_postal }}">
                                                    </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Address - Translate</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="address_translated"
                                                                value="{{ $devotee->address_translated }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Oversea Addr in Chinese</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="oversea_addr_in_chinese"
                                                                value="{{ $devotee->oversea_addr_in_chinese }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                </div><!-- end col-md- 6-->

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">NRIC</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="nric"
                                                                value="{{ $devotee->nric }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Deceased Year</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="deceased_year"
                                                                value="{{ $devotee->deceased_year }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Date of Birth</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="dob" data-provide="datepicker"
                                                                value="{{ Carbon\Carbon::parse($devotee->dob)->format('d/m/Y') }}">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Marital Status</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="marital_status">
                                                                <option value="">Please select</option>
                                                                <option value="single">Single</option>
                                                                <option value="married">Married</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Dialect</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="dialect">
                                                                <option value="">Please select</option>
                                                                <option value="chinese">Chinese</option>
                                                                <option value="others">Others</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Race</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="race">
                                                                <option value="">Please select</option>
                                                                <option value="chinese">Chinese</option>
                                                                <option value="others">Others</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Nationality</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" name="nationality">
                                                                <option value="">Please select</option>
                                                                <option value="singapore">Singapore</option>
                                                                <option value="others">Others</option>
                                                            </select>
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                </div><!-- end col-md-6 -->

                                                <div class="clearfix"></div>

                                                <hr>

                                                <h4>Optional</h4>

                                                <div class="col-md-6">

                                                    @php $count = 1; @endphp

                                                    @foreach($optionaladdresses as $optionaladdress)

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Opt.Address #{{ $count }}</label>

                                                        <div class="col-md-3">
                                                            <select class="form-control" name="address_type[]">
                                                                <option value="home" <?php if ($optionaladdress->type == 'home') echo "selected"; ?>>Home</option>
                                                                <option value="company" <?php if ($optionaladdress->type == 'company') echo "selected"; ?>>Company</option>
                                                                <option value="stall" <?php if ($optionaladdress->type == 'stall') echo "selected"; ?>>Stall</option>
                                                                <option value="office" <?php if ($optionaladdress->type == 'office') echo "selected"; ?>>Office</option>
                                                            </select>
                                                        </div><!-- end col-md-3 -->

                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="address_data[]"
                                                                value="{{ $optionaladdress->data }}">
                                                        </div><!-- end col-md-6 -->

                                                    </div><!-- end form-group -->

                                                    @php $count++; @endphp

                                                    @endforeach


                                                    @php $count = 1; @endphp

                                                    @foreach($optionalvehicles as $optionalvehicle)

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Opt.Vehicle #{{ $count }}</label>

                                                        <div class="col-md-3">
                                                            <select class="form-control" name="vehicle_type[]">
                                                                <option value="car" <?php if ($optionalvehicle->type == 'car') echo "selected"; ?>>Car</option>
                                                                <option value="ship">Ship</option>
                                                            </select>
                                                        </div><!-- end col-md-3 -->

                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="vehicle_data[]"
                                                                value="{{ $optionalvehicle->data }}">
                                                        </div><!-- end col-md-6 -->

                                                    </div><!-- end form-group -->

                                                    @php $count++; @endphp

                                                    @endforeach

                                                    <div class="form-group">

                                                        <label class="col-md-3 control-label">Special Remark #1</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="special_remark">
                                                        </div><!-- end col-md-9 -->

                                                    </div><!-- end form-group -->

                                                    <div class="form-group"></div><!-- end form-group -->

                                                </div><!-- end col-md-6 -->

                                                <div class="clearfix"></div><!-- end clearfix -->

                                                <div class="col-md-12">

                                                    <div class="form-actions">
                                                        <button type="submit" class="btn blue" id="confirm_btn">Update</button>
                                                        <button type="button" class="btn default">Cancel</button>
                                                    </div><!-- end form-actions -->

                                                </div><!-- end col-md-12 -->

                                            </div><!-- end form-body -->

                                            </form>

                                        </div><!-- end inbox-content -->

                                    </div><!-- end inbox-body -->

                                </div><!-- end col-md-9 -->

                            </div><!-- end row -->

                        </div><!-- end inbox -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container-fluid -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop
