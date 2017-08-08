@extends('layouts.backend.app')

@section('main-content')

	<div class="page-container-fluid">

        <div class="page-content-wrapper">

            <div class="page-head">

                <div class="container-fluid">

                	<div class="page-title">

                        <h1>Edit Account</h1>

                    </div><!-- end page-title -->

                </div><!-- end container-fluid -->

            </div><!-- end page-head -->

            <div class="page-content">

                <div class="container-fluid">

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Devotee</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                        <div class="inbox">

                            <div class="row">

																<div id="partial-pageload">
																	@include('layouts.partials.focus-devotee-sidebar')
																</div>

                                <div class="col-md-9">

                                    <div class="form-row-seperated">

                                        <div class="portlet">

                                            <div class="validation-error">
                                            </div><!-- end validation-error -->

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

                                            <div class="portlet-body">

                                                <div class="tabbable-bordered">

                                                          <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_devoteelists" data-toggle="tab">Devotee Lists<br> 善信名单</a>
                                                        </li>

                                                        <li id="members">
                                                            <a href="#tab_memberlists" data-toggle="tab">Member Lists<br> 会员名单</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_deceasedlists" data-toggle="tab">Deceased Lists <br> 已故善信名单</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_newdevotee" data-toggle="tab">New Devotee <br>新善信档案</a>
                                                        </li>
                                                        <li id="edit" class="disabled">
                                                            <a href="#tab_editdevotee" data-toggle="tab">Edit Devotee <br>资料更新</a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_relocation" data-toggle="tab">Relocation  <br>全家搬迁</a>
                                                        </li>
																												<li id="search" id="search">
                                                            <a href="#tab_searchresult" data-toggle="tab">Search Result  <br>全家搬迁</a>
                                                        </li>
                                                    </ul>


                                                    <div class="tab-content">

                                                        <div class="tab-pane active" id="tab_devoteelists">

                                                            <div class="form-body">

                                                                <div class="form-group">

                                                                    <table class="table table-striped table-bordered" id="devotees_table">

                                                                        <thead>
                                                                            <tr id="filter">
                                                                                <th>Chinese Name</th>
                                                                                <th>Devotee</th>
                                                                                <th>Address</th>
                                                                                <th>Guiyi Name</th>
                                                                                <th>Family Code</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Chinese Name</th>
                                                                                <th>Devotee</th>
                                                                                <th>Address</th>
                                                                                <th>Guiyi Name</th>
                                                                                <th>Family Code</th>
                                                                            </tr>
                                                                        </thead>

                                                                        @php $count = 1; @endphp

                                                                        <tbody>
                                                                            @foreach($devotees as $devotee)
                                                                            <tr>
                                                                                <td>
																																									@if($devotee->specialremarks_devotee_id == null)
																																									<a href="/operator/devotee/{{ $devotee->devotee_id }}" class="edit-devotee" id="{{ $devotee->devotee_id }}">{{ $devotee->chinese_name }}</a>
																																									@else
																																									<a href="/operator/devotee/{{ $devotee->devotee_id }}" class="edit-devotee text-danger"
																																										id="{{ $devotee->devotee_id }}">{{ $devotee->chinese_name }}</a>
																																									@endif
                                                                                </td>
                                                                                <td>{{ $devotee->devotee_id }}</td>
                                                                                <td>
																																									@if(isset($devotee->address_unit1) && isset($devotee->address_unit2))
																																										{{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																									@else
																																										{{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																									@endif
                                                                                </td>
                                                                                <td>{{ $devotee->guiyi_name }}</td>
                                                                                <td>{{ $devotee->familycode }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>

                                                                    </table>

                                                                </div><!-- end form-group -->

                                                            </div><!-- end form-body -->

                                                        </div><!-- end tab-pane devotee-lists -->

                                                        <div class="tab-pane" id="tab_memberlists">

                                                            <div class="form-body">

                                                                <div class="form-group">

                                                                    <table class="table table-bordered" id="members_table">
                                                                        <thead>
																																					<tr id="filter">
																																							<th>Chinese Name</th>
																																							<th>Devotee#</th>
																																							<th>Member#</th>
																																							<th>Address</th>
																																							<th>Guiyi Name</th>
																																							<th>Family Code</th>
																																					</tr>
                                                                            <tr>
                                                                                <th>Chinese Name</th>
                                                                                <th>Devotee#</th>
                                                                                <th>Member#</th>
                                                                                <th>Address</th>
                                                                                <th>Guiyi Name</th>
                                                                                <th>Family Code</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @foreach($members as $member)
                                                                            <tr>
                                                                                <td>
																																									@if($devotee->specialremarks_devotee_id == null)
																																									<a href="/operator/devotee/{{ $member->devotee_id }}" id="edit-member">{{ $member->chinese_name }}</a>
																																									@else
																																									<a href="/operator/devotee/{{ $member->devotee_id }}" id="edit-member text-danger">{{ $member->chinese_name }}</a>
																																									@endif
																																								</td>
                                                                                <td>{{ $member->devotee_id }}</td>
                                                                                <td>{{ $member->member_id }}</td>
                                                                                <td>
                                                                                    @if(isset($member->address_unit1) && isset($member->address_unit2))
																																										{{ $member->address_houseno }}, #{{ $member->address_unit1 }}-{{ $member->address_unit2 }}, {{ $member->address_street }}, {{ $member->address_postal }}
																																										@else
																																										{{ $member->address_houseno }}, {{ $member->address_street }}, {{ $member->address_postal }}
																																										@endif
                                                                                </td>
                                                                                <td>{{ $member->guiyi_name }}</td>
                                                                                <td>{{ $member->familycode }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>

                                                                    </table>

                                                                </div><!-- end form-group -->

                                                            </div><!-- end form-body -->

                                                        </div><!-- end tab-pane member-lists -->

                                                        <div class="tab-pane" id="tab_deceasedlists">

                                                            <div class="form-body">

                                                                <div class="form-group">

                                                                    <table class="table table-bordered" id="deceased_table">
                                                                        <thead>
																																						<tr id="filter">
																																								<th>Chinese Name</th>
																																								<th>Devotee#</th>
																																								<th>Member#</th>
																																								<th>Address</th>
																																								<th>Guiyi Name</th>
																																								<th>Family Code</th>
																																						</tr>
                                                                            <tr>
                                                                                <th>Chinese Name</th>
                                                                                <th>Devotee</th>
                                                                                <th>Member</th>
                                                                                <th>Address</th>
                                                                                <th>Guiyi Name</th>
                                                                                <th>Family Code</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @foreach($deceased_lists as $deceased_list)
                                                                            <tr>
                                                                                <td><span class="text-danger">{{ $deceased_list->chinese_name }}</span></td>
                                                                                <td>{{ $deceased_list->devotee_id }}</td>
                                                                                <td>{{ $deceased_list->member_id }}</td>
                                                                                <td>
																																									@if(isset($deceased_list->address_unit1) && isset($deceased_list->address_unit2))
																																									{{ $deceased_list->address_houseno }}, #{{ $deceased_list->address_unit1 }}-{{ $deceased_list->address_unit2 }}, {{ $deceased_list->address_street }}, {{ $deceased_list->address_postal }}
																																									@else
																																									{{ $deceased_list->address_houseno }}, {{ $deceased_list->address_street }}, {{ $deceased_list->address_postal }}
																																									@endif
																																								</td>
                                                                                <td>{{ $deceased_list->guiyi_name }}</td>
                                                                                <td>{{ $deceased_list->familycode }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>

                                                                    </table>

                                                                </div><!-- end form-group -->

                                                            </div><!-- end form-body -->

                                                        </div><!-- end tab-pane deceased-lists -->

                                                        <div class="tab-pane" id="tab_newdevotee">

																														<div class="form-body" style="margin-bottom: 25px;">

																															<div class="col-md-3">
																																<label>Devotee ID (to be assigned)</label>
																															</div><!-- end col-md-3 -->

																															<div class="col-md-3">
																																<label>Member ID (to be assigned)</label>
																															</div><!-- end col-md-3 -->

																															<div class="col-md-3">
																																<label>Bridging ID (to be assigned)</label>
																															</div><!-- end col-md-3 -->

																															<div class="col-md-3">
																																<label>Family Code (to be assigned)</label>
																															</div><!-- end col-md-3 -->

																															<div class="clearfix">
																															</div><!-- end clearfix -->

																														</div><!-- end form-body -->

                                                            <form method="post" action="{{ URL::to('/operator/new-devotee') }}"
                                                                class="form-horizontal form-bordered">
                                                                {!! csrf_field() !!}

                                                            <div class="form-body">

                                                                <div class="col-md-6">

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Title</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control" name="title">
                                                                                <option value="mr">Mr</option>
                                                                                <option value="miss">Miss</option>
                                                                                <option value="madam">Madam</option>
                                                                            </select>
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Chinese Name</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="chinese_name"
                                                                                value="{{ old('chinese_name') }}" id="content_chinese_name">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">English Name</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="english_name"
                                                                            value="{{ old('english_name') }}" id="content_english_name">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Contact #</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="contact"
                                                                                value="{{ old('contact') }}" id="content_contact">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Guiyi Name</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="guiyi_name"
                                                                            value="{{ old('guiyi_name') }}" id="content_guiyi_name">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Address - House No</label>
                                                                        <div style='width:19.66667%;float:left; padding-left: 15px;'>
                                                                            <input type="text" class="form-control" name="address_houseno"
                                                                                value="{{ old('address_houseno') }}"
                                                                                id="content_address_houseno">
                                                                        </div><!-- end col-md-3 -->

                                                                        <label style='width:9.3%;float:left;'>Unit</label>

                                                                        <div style='width:11.5%;float:left;'>
                                                                            <input type="text" class="form-control" name="address_unit1"
                                                                                value="{{ old('address_unit1') }}" id="content_address_unit1">
                                                                        </div><!-- end col-md-2 -->

                                                                        <label style='width:6.2%;float:left;'>-</label>

                                                                        <div style='width:16.66667%;float:left;'>
                                                                            <input type="text" class="form-control" name="address_unit2"
                                                                                value="{{ old('address_unit2') }}" id="content_address_unit2">
                                                                        </div><!-- end col-md-2 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Address - Street</label>
                                                                        <div class="col-md-8">
																																						<input type="text" class="form-control" name="address_street"
                                                                                value="{{ old('address_street') }}" id="content_address_street">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Address - Postal</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="address_postal"
                                                                                value="{{ old('address_postal') }}" id="content_address_postal">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Address - Translate</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control"
                                                                                name="address_translated" id="address_translated" readonly>
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Oversea Addr in Chinese
                                                                        </label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control"
                                                                                name="oversea_addr_in_chinese" id="content_oversea_addr_in_chinese">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                      <div class="col-md-12">
																																				<button type="button" class="btn default check_family_code" style="margin-right: 30px;">
	                                                                      	Check Family Code
	                                                                      </button>

																																				<button type="button" class="btn default address_translated_btn">
																																					Translate Address
																																				</button>
                                                                      </div><!-- end col-md-12 -->

                                                                    </div><!-- end form-group -->

                                                                </div><!-- end col-md-6 -->

                                                                <div class="col-md-6">

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">NRIC</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="nric"
                                                                                value="{{ old('nric') }}" id="content_nirc">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Deceased Year</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="deceased_year form-control" name="deceased_year"
                                                                                value="{{ old('deceased_year') }}" id="content_deceased_year">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Date of Birth</label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" class="form-control" name="dob" id="content_dob"
                                                                                data-provide="datepicker" data-date-format="dd/mm/yyyy" value="{{ old('dob') }}">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Marital Status</label>
                                                                        <div class="col-md-8">
                                                                           <select class="form-control" name="marital_status" id="content_marital_status">
                                                                                <option value="">Please select</option>
                                                                                <option value="single">Single</option>
                                                                                <option value="married">Married</option>
                                                                            </select>
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Dialect</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control" name="dialect" id="content_dialect">
                                                                                <option value="">Please select</option>
                                                                                <option value="chinese">Chinese</option>
                                                                                <option value="others">Others</option>
                                                                            </select>
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

																																		<div class="form-group" id="other_dialect_field" style="display:none;">

                                                                        <label class="col-md-4"></label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" name="other_dialect" class="form-control" value=""
																																						placeholder="Other Dialect" id="content_other_dialect">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Race</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control" name="race" id="content_race">
                                                                                <option value="">Please select</option>
                                                                                <option value="chinese">Chinese</option>
                                                                                <option value="others">Others</option>
                                                                            </select>
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

																																		<div class="form-group" id="other_race_field" style="display:none;">

                                                                        <label class="col-md-4"></label>
                                                                        <div class="col-md-8">
                                                                            <input type="text" name="other_race" class="form-control" value=""
																																						placeholder="Other Race" id="content_other_race">
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Nationality</label>
                                                                        <div class="col-md-8">
                                                                            <select class="form-control" name="nationality" id="content_nationality">
                                                                                <option value="">Please select</option>
																																								@foreach($countries as $country)
                                                                                <option value="{{ $country->id }}"<?php if ($country->id == 202) echo "selected"; ?>>{{ $country->country_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div><!-- end col-md-8 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <div class="col-md-12">
                                                                            <div class="table-scrollable" id="familycode-table">
                                                                                <table class="table table-bordered table-hover">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>Family Code</th>
																																														<th>Name</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr id="no_familycode">
                                                                                            <td colspan="3">No Family Code</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-4">Mailer</label>
                                                                        <div class="col-md-2">
                                                                            <select class="form-control" name="mailer" style="width: 90px;">
																																							<option value="No">No</option>
                                                                              <option value="Yes">Yes</option>
                                                                            </select>
                                                                        </div><!-- end col-md-2 -->

                                                                        <div class="col-md-4">
                                                                        </div><!-- end col-md-4 -->

                                                                    </div><!-- end form-group -->

                                                                </div><!-- end col-md-6 -->

                                                                <div class="clearfix"></div>

                                                                <hr>

                                                                <h4>Optional</h4>

                                                                <div class="col-md-6">

                                                                    <div class="opt_address">
																																			<div class="inner_opt_addr">
																																				<div class="form-group">

																																						<div class="col-md-1"></div><!-- end col-md-1 -->

		                                                                        <label class="col-md-2">Opt.Addr</label>

		                                                                        <div class="col-md-3">
		                                                                            <select class="form-control address-type" name="address_type[]">
		                                                                                <option value="home">Home</option>
		                                                                                <option value="company">Comp</option>
		                                                                                <option value="stall">Stall</option>
		                                                                                <option value="office">Office</option>
		                                                                            </select>
		                                                                        </div><!-- end col-md-3 -->

		                                                                        <div class="col-md-4 populate-address">
		                                                                            <input type="text" class="form-control address-data" name="address_data[]" readonly
																																									value="Please fill the address on the right" title="Please fill the address on the right">
		                                                                        </div><!-- end col-md-4 -->

		                                                                        <div class="col-md-2">
																																							<button type="button" class='fa fa-angle-double-right populate-data form-control' aria-hidden='true'>
																																							</button>
																																						</div><!-- end col-md-1 -->

		                                                                    </div><!-- end form-group -->

																																				<div class="form-group" style="margin-bottom: 0;">

																																						<div class="col-md-1"></div><!-- end col-md-1 -->

		                                                                        <label class="col-md-2"></label>

		                                                                        <div class="col-md-3">
		                                                                        </div><!-- end col-md-3 -->

		                                                                        <div class="col-md-4 populate-address">
		                                                                            <input type="hidden" class="form-control address-data-hidden" name="address_data_hidden[]">
		                                                                        </div><!-- end col-md-4 -->

		                                                                        <div class="col-md-2">
																																						</div><!-- end col-md-2 -->

		                                                                    </div><!-- end form-group -->
																																			</div><!-- end inner_opt_addr -->
                                                                    </div><!-- end opt_address -->

                                                                    <div id="append_opt_address">
                                                                    </div><!-- end append_opt_address -->

                                                                    <div class="form-group">

                                                                        <div class="col-md-1"></div><!-- end col-md-1 -->

                                                                        <div class="col-md-5" style="margin-bottom: 15px;">
                                                                            <i class="fa fa-plus-circle" aria-hidden="true"
                                                                                id="appendAddressBtn"></i>
                                                                        </div><!-- end col-md-5 -->

                                                                        <div class="col-md-6"></div><!-- end col-md-5 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

																																				<div class="col-md-1"></div><!-- end col-md-1 -->

                                                                        <label class="col-md-2">Opt.Vehicle</label>

                                                                        <div class="col-md-3">
                                                                            <select class="form-control" name="vehicle_type[]">
                                                                                <option value="car">Car</option>
                                                                                <option value="ship">Ship</option>
                                                                            </select>
                                                                        </div><!-- end col-md-2 -->

                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control" name="vehicle_data[]">
                                                                        </div><!-- end col-md-6 -->

                                                                    </div><!-- end form-group -->

                                                                    <div id="append_opt_vehicle">
                                                                    </div><!-- end append_opt_vehicle -->

                                                                    <div class="form-group">

                                                                        <div class="col-md-1">
                                                                        </div><!-- end col-md-1 -->

                                                                        <div class="col-md-5" style="margin-bottom: 15px;">
                                                                            <i class="fa fa-plus-circle" aria-hidden="true"
                                                                                id="appendVehicleBtn"></i>
                                                                        </div><!-- end col-md-5 -->

                                                                        <div class="col-md-6">
                                                                        </div><!-- end col-md-6 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

																																				<div class="col-md-1"></div><!-- end col-md-1 -->

                                                                        <label class="col-md-2">Special Remark</label>

                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control" name="special_remark[]">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div id="append_special_remark">
                                                                    </div><!-- end append_special_remark -->

                                                                    <div class="form-group">

                                                                        <div class="col-md-1">
                                                                        </div><!-- end col-md-1 -->

                                                                        <div class="col-md-5" style="margin-bottom: 15px;">
                                                                            <i class="fa fa-plus-circle" aria-hidden="true"
                                                                                id="appendSpecRemarkBtn"></i>
                                                                        </div><!-- end col-md-5 -->

                                                                        <div class="col-md-6">
                                                                        </div><!-- end col-md-6 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-12">
                                                                            If you have made Changes to the above. You need to CONFIRM to save the Changes.<br />
                                                                            To Confirm, please enter authorized password to proceed.
                                                                        </label>

                                                                    </div><!-- end form-group -->

                                                                </div><!-- end col-md-6 -->

                                                                <div class="col-md-6">

																																	<div style="border: 1px solid #D5D4D4; padding: 5px; margin-bottom: 10px;">
																																		<h4>Local Address</h4>

																																		<div class="form-group">
																																			<label class="col-md-4">House No</label>
																																			<div style='width:19.66667%;float:left; padding-left: 15px;'>
																																					<input type="text" class="form-control" name="populate_houseno"
																																							value="{{ old('populate_houseno') }}" id="populate_houseno">
																																			</div><!-- end col-md-3 -->

																																			<label style='width:9.3%;float:left;'>Unit</label>

																																			<div style='width:11.5%;float:left;'>
																																					<input type="text" class="form-control" name="populate_unit_1"
																																							value="{{ old('populate_unit_1') }}" id="populate_unit_1">
																																			</div><!-- end col-md-2 -->

																																			<label style='width:6.2%;float:left;'>-</label>

																																			<div style='width:16.66667%;float:left;'>
																																					<input type="text" class="form-control" name="populate_unit_2"
																																							value="{{ old('populate_unit_2') }}" id="populate_unit_2">
																																			</div><!-- end col-md-2 -->
																																		</div><!-- end form-group -->

																																		<div class="form-group">
																																			<label class="col-md-4">Street</label>
																																			<div class="col-md-8">
																																					<input type="text" class="form-control" name="populate_street"
																																							value="{{ old('populate_address_street') }}" id="populate_street">
																																			</div><!-- end col-md-8 -->
																																		</div><!-- end form-group -->

																																		<div class="form-group">
																																			<label class="col-md-4">Postal</label>
																																			<div class="col-md-8">
																																					<input type="text" class="form-control" name="populate_postal"
																																							value="{{ old('populate_postal') }}" id="populate_postal">
																																			</div><!-- end col-md-8 -->
																																		</div><!-- end form-group -->

																																		<div class="form-group">
																																			<label class="col-md-4">Address Translate</label>
																																			<div class="col-md-8">
																																					<input type="text" class="form-control" name="populate_address_translate" readonly
																																							value="{{ old('populate_address_translate') }}" id="populate_address_translate">
																																			</div><!-- end col-md-8 -->
																																		</div><!-- end form-group -->

																																		<div class="form-group">
																																			<label class="col-md-4">Oversea Addr in Chinese</label>
																																			<div class="col-md-8">
																																					<input type="text" class="form-control" name="populate_oversea_addr_in_china"
																																							value="{{ old('populate_oversea_addr_in_china') }}" id="populate_oversea_addr_in_china">
																																			</div><!-- end col-md-8 -->
																																		</div><!-- end form-group -->
																																	</div>

																																	@if(Auth::user()->role == 3)

                                                                    <div class="form-group">

                                                                        <label class="col-md-3 control-label">Introduced By-1</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control" name="introduced_by1"
                                                                                value="{{ old('introduced_by1') }}" id="content_introduced_by1">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-3 control-label">Introduced By-2</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control" name="introduced_by2"
                                                                                value="{{ old('introduced_by2') }}" id="content_introduced_by2">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-group">

                                                                        <label class="col-md-3 control-label">Member Approved Date</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-control-inline date-picker" name="approved_date" data-provide="datepicker"
																																							data-date-format="dd/mm/yyyy" id="content_approved_date">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->

																																		@endif

                                                                    <div class="form-group">
                                                                        <label class="col-md-3"></label>
                                                                        <label class="col-md-5 control-label">Authorized Password</label>
                                                                        <div class="col-md-4">
                                                                            <input type="password" class="form-control"
                                                                                name="authorized_password" id="content_authorized_password">
                                                                        </div><!-- end col-md-4 -->

                                                                    </div><!-- end form-group -->

                                                                    <div class="form-actions pull-right">
                                                                        <button type="submit" class="btn blue" id="confirm_btn" disabled>Confirm</button>
                                                                        <button type="button" class="btn default">Cancel</button>
                                                                    </div><!-- end form-actions -->
                                                                </div><!-- end col-md-6 -->

                                                            </div><!-- end form-body -->

                                                            <div class="clearfix"></div><!-- end clearfix -->

                                                            </form>

                                                        </div><!-- end tab-pane new-devotee -->

                                                        <div class="tab-pane" id="tab_editdevotee">

                                                            @include('layouts.partials.edit-devotee')

                                                        </div><!-- end tab-pane -->

                                                        <div class="tab-pane" id="tab_relocation">

                                                            <div class="form-body">

                                                                <form method="post" action="{{ URL::to('/operator/relocation') }}"
                                                                    class="form-horizontal form-bordered">
                                                                    {!! csrf_field() !!}

                                                                <div class="col-md-12 .relocation-devotee-table">
                                                                    <div class="form-group">

                                                                        <table class="table table-bordered relocation" id="relocation_table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>Chinese Name</th>
                                                                                    <th>English Name</th>
                                                                                    <th>Guiyi Name</th>
                                                                                    <th>NRIC</th>
                                                                                    <th>Address</th>
                                                                                    <th>Family Code</th>
                                                                                    <th>Member Code</th>
                                                                                </tr>
                                                                            </thead>

                                                                            @if(Session::has('devotee_lists'))

                                                                            @php

                                                                                $devotee_lists = Session::get('devotee_lists');
                                                                                $focus_devotee = Session::get('focus_devotee');

                                                                            @endphp

	                                                                            @if(count($focus_devotee) == 1)

																																							<tbody id="has_session">

	                                                                                <tr>
	                                                                                    <tr>
	                                                                                        <td><input type="checkbox" name="relocation_devotee_id[]"
	                                                                                            value="{{ $focus_devotee[0]->devotee_id }}" /></td>
	                                                                                        <td>{{ $focus_devotee[0]->chinese_name }}</td>
	                                                                                        <td>{{ $focus_devotee[0]->english_name }}</td>
	                                                                                        <td>{{ $focus_devotee[0]->guiyi_name }}</td>
	                                                                                        <td>{{ $focus_devotee[0]->nric }}</td>
	                                                                                        <td>
																																														@if(isset($focus_devotee[0]->address_unit1) && isset($focus_devotee[0]->address_unit2))
																																														{{ $focus_devotee[0]->address_houseno }}, #{{ $focus_devotee[0]->address_unit1 }}-{{ $focus_devotee[0]->address_unit2 }}, {{ $focus_devotee[0]->address_street }}, {{ $focus_devotee[0]->address_postal }}
																																														@else
																																														{{ $focus_devotee[0]->address_houseno }}, {{ $focus_devotee[0]->address_street }}, {{ $focus_devotee[0]->address_postal }}
																																														@endif
																																													</td>
	                                                                                        <td>{{ $focus_devotee[0]->familycode }}</td>
	                                                                                        <td>{{ $focus_devotee[0]->member_id }}</td>
	                                                                                    </tr>
	                                                                                </tr>

	                                                                                @foreach($devotee_lists as $devotee)
	                                                                                    <tr>
	                                                                                        <td><input type="checkbox" name="devotee_id[]"
	                                                                                            value="{{ $devotee->devotee_id }}" /></td>
	                                                                                        <td>{{ $devotee->chinese_name }}</td>
	                                                                                        <td>{{ $devotee->english_name }}</td>
	                                                                                        <td>{{ $devotee->guiyi_name }}</td>
	                                                                                        <td>{{ $devotee->nric }}</td>
	                                                                                        <td>
																																														@if(isset($devotee->address_unit1) && isset($devotee->address_unit2))
																																														{{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																														@else
																																														{{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																														@endif
																																													</td>
	                                                                                        <td>{{ $devotee->familycode }}</td>
	                                                                                        <td>{{ $devotee->member_id }}</td>
	                                                                                    </tr>
	                                                                                @endforeach
	                                                                            </tbody>


																																							@else

																																							<tbody id="more_devotee">
																																								<tr>
																																									<td>No data</td>
																																								</tr>
	                                                                            </tbody>

																																							@endif

                                                                            @else

                                                                            <tbody id="no_session">
																																							<tr>
																																								<td>No data</td>
																																							</tr>
                                                                            </tbody>

                                                                            @endif

                                                                        </table>
                                                                    </div><!-- end form-group -->

                                                                </div><!-- end col-md-12 -->

                                                                <div class="clearfix"></div>

                                                                <hr>

                                                                <div class="col-md-12">
                                                                    <h4>Current Address</h4>
                                                                    <h5>Local Address</h5>

                                                                    @if(Session::has('focus_devotee'))

                                                                    @php $focus_devotee = Session::get('focus_devotee'); @endphp

                                                                    	@if(count($focus_devotee) == 1)

																																			<div class="col-md-8" id="has_session">
	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - House No</label>
	                                                                            <div class="col-md-3">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_houseno" id="current_address_houseno"
	                                                                                    value="{{ $focus_devotee[0]->address_houseno }}" readonly>
	                                                                            </div><!-- end col-md-3 -->

	                                                                            <label class="col-md-1">Unit</label>

	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_unit1" id="current_address_unit1"
	                                                                                    value="{{ $focus_devotee[0]->address_unit1 }}" readonly>
	                                                                            </div><!-- end col-md-2 -->

	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_unit2" id="current_address_unit2"
	                                                                                    value="{{ $focus_devotee[0]->address_unit2 }}" readonly>
	                                                                            </div><!-- end col-md-2 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - Street</label>
	                                                                            <div class="col-md-8">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_street" id="current_address_street"
	                                                                                    value="{{ $focus_devotee[0]->address_street }}" readonly>
	                                                                            </div><!-- end col-md-8 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - Postal</label>
	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_postal" id="current_address_postal"
	                                                                                    value="{{ $focus_devotee[0]->address_postal }}" readonly>
	                                                                            </div><!-- end col-md-2 -->

	                                                                            <label class="col-md-2">Country *</label>
	                                                                            <div class="col-md-4">
	                                                                                <select class="form-control" name="nationality" disabled>
	                                                                                    <option value="">Please select</option>
																																											@foreach($countries as $country)
			                                                                                <option value="{{ $country->id }}" <?php if ($focus_devotee[0]->nationality == $country->id) echo "selected"; ?>>{{ $country->country_name }}</option>
			                                                                                @endforeach
	                                                                                </select>
	                                                                            </div><!-- end col-md-3 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Oversea Addr in Chinese</label>
	                                                                            <div class="col-md-8">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="oversea_addr_in_chinese" id="current_oversea_addr_in_chinese"
	                                                                                    value="{{ $focus_devotee[0]->oversea_addr_in_chinese }}" readonly>
	                                                                            </div><!-- end col-md-6 -->

	                                                                        </div><!-- end form-group -->

	                                                                    </div><!-- end col-md-6 -->


																																			@else

																																			<div class="col-md-8" id="more_devotees">
	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - House No</label>
	                                                                            <div class="col-md-3">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_houseno" id="current_address_houseno">
	                                                                            </div><!-- end col-md-3 -->

	                                                                            <label class="col-md-1">Unit</label>

	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_unit1" id="current_address_unit1">
	                                                                            </div><!-- end col-md-2 -->

	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_unit2" id="current_address_unit2">
	                                                                            </div><!-- end col-md-2 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - Street</label>
	                                                                            <div class="col-md-8">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_street" id="current_address_street">
	                                                                            </div><!-- end col-md-8 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Address - Postal</label>
	                                                                            <div class="col-md-2">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="address_postal" id="current_address_postal">
	                                                                            </div><!-- end col-md-2 -->

	                                                                            <label class="col-md-2">Country *</label>
	                                                                            <div class="col-md-4">
	                                                                                <select class="form-control" name="nationality">
	                                                                                    <option value="">Please select</option>
																																											@foreach($countries as $country)
			                                                                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
			                                                                                @endforeach
	                                                                                </select>
	                                                                            </div><!-- end col-md-3 -->

	                                                                        </div><!-- end form-group -->

	                                                                        <div class="form-group">
	                                                                            <label class="col-md-4">Oversea Addr in Chinese</label>
	                                                                            <div class="col-md-8">
	                                                                                <input type="text" class="form-control"
	                                                                                    name="oversea_addr_in_chinese" id="current_oversea_addr_in_chinese">
	                                                                            </div><!-- end col-md-6 -->

	                                                                        </div><!-- end form-group -->

	                                                                    </div><!-- end col-md-6 -->


																																			@endif

                                                                    @else

                                                                    <div class="col-md-8" id="no_session">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - House No</label>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="address_houseno" id="current_address_houseno">
                                                                            </div><!-- end col-md-3 -->

                                                                            <label class="col-md-1">Unit</label>

                                                                            <div class="col-md-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="address_unit1" id="current_address_unit1">
                                                                            </div><!-- end col-md-2 -->

                                                                            <div class="col-md-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="address_unit2" id="current_address_unit2">
                                                                            </div><!-- end col-md-2 -->

                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - Street</label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control"
                                                                                    name="address_street" id="current_address_street">
                                                                            </div><!-- end col-md-8 -->

                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - Postal</label>
                                                                            <div class="col-md-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="address_postal" id="current_address_postal">
                                                                            </div><!-- end col-md-2 -->

                                                                            <label class="col-md-2">Country *</label>
                                                                            <div class="col-md-4">
                                                                                <select class="form-control" name="nationality">
                                                                                    <option value="">Please select</option>
																																										@foreach($countries as $country)
		                                                                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
		                                                                                @endforeach
                                                                                </select>
                                                                            </div><!-- end col-md-3 -->

                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Oversea Addr in Chinese</label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control"
                                                                                    name="oversea_addr_in_chinese" id="current_oversea_addr_in_chinese">
                                                                            </div><!-- end col-md-6 -->

                                                                        </div><!-- end form-group -->

                                                                    </div><!-- end col-md-6 -->

                                                                    @endif

                                                                    <div class="col-md-4">
                                                                    </div><!-- end col-md-4 -->

                                                                </div><!-- end col-md-12 -->

                                                                <div class="clearfix"></div>

                                                                <hr>

                                                                <div class="col-md-12">
                                                                    <h4>New Address</h4>
                                                                    <h5>Local Address</h5>

                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - House No</label>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="new_address_houseno"
                                                                                    value="{{ old('new_address_houseno') }}" id="new_address_houseno">
                                                                            </div><!-- end col-md-3 -->

                                                                            <label class="col-md-1">Unit</label>

                                                                            <div class="col-md-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="new_address_unit1" id="new_address_unit1"
                                                                                    value="{{ old('new_address_unit1') }}">
                                                                            </div><!-- end col-md-2 -->

                                                                            <div class="col-md-2">
                                                                                <input type="number" class="form-control"
                                                                                    name="new_address_unit2" id="new_address_unit2"
                                                                                    value="{{ old('new_address_unit2') }}">
                                                                            </div><!-- end col-md-2 -->
                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - Street</label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control"
                                                                                    name="new_address_street"
                                                                                    value="{{ old('new_address_street') }}" id="new_address_street">
                                                                            </div><!-- end col-md-8 -->
                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Address - Postal</label>
                                                                            <div class="col-md-2">
                                                                                <input type="text" class="form-control"
                                                                                    name="new_address_postal"
                                                                                    value="{{ old('new_address_postal') }}" id="new_address_postal">
                                                                            </div><!-- end col-md-2 -->

                                                                            <label class="col-md-2">Country *</label>
                                                                            <div class="col-md-4">
                                                                                <select class="form-control" name="new_nationality" id="new_nationality">
                                                                                    <option value="">Please select</option>
																																										@foreach($countries as $country)
		                                                                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
		                                                                                @endforeach
                                                                                </select>
                                                                            </div><!-- end col-md-3 -->

                                                                        </div><!-- end form-group -->

                                                                        <div class="form-group">
                                                                            <label class="col-md-4">Oversea Addr in Chinese</label>
                                                                            <div class="col-md-8">
                                                                                <input type="text" class="form-control"
                                                                                    name="new_oversea_addr_in_chinese" id="new_oversea_addr_in_chinese">
                                                                            </div><!-- end col-md-6 -->
                                                                        </div><!-- end form-group -->

																																				<div class="form-group">
                                                                          <button type="button" class="btn default relocation_check_family_code" style="margin-right: 30px;">
																																						Check Family Code</button>
                                                                        </div><!-- end form-group -->

                                                                    </div><!-- end col-md-8 -->

                                                                    <div class="col-md-4">
																																			<div class="table-scrollable" id="relocation-familycode-table">
																					 															 <table class="table table-bordered table-hover">

																					 																	 <thead>
																					 																			 <tr>
																					 																					 <th>#</th>
																					 																					 <th>Family Code</th>
																					 																					 <th>Name</th>
																					 																			 </tr>
																					 																	 </thead>

																					 																 <tbody>
																					 																			 <tr id="relocation_no_familycode">
																					 																					 <td colspan="3">No Family Code</td>
																					 																			 </tr>
																					 																	 </tbody>
																					 															 </table>
																					 													 </div>
                                                                    </div><!-- end col-md-4 -->

                                                                </div><!-- end col-md-12 -->

                                                                <div class="clearfix"></div>

                                                                <hr>

																																<div class="col-md-12">

																																	<div class="col-md-6">
																																		<div class="form-group">
																																				<label class="col-md-12">
																																						If you have made Changes to the above. You need to CONFIRM to save the Changes.<br />
																																						To Confirm, please enter authorized password to proceed.
																																				</label>
																																		</div><!-- end form-group -->
																																	</div><!-- end col-md-6 -->

																																	<div class="col-md-6">
																																		<div class="form-group pull-right">
                                                                        <label class="col-md-2"></label>
                                                                        <label class="col-md-6 control-label">Authorized Password</label>
                                                                        <div class="col-md-4">
                                                                            <input type="password" class="form-control"
                                                                                name="authorized_password" id="relocation_authorized_password">
                                                                        </div><!-- end col-md-9 -->

                                                                    </div><!-- end form-group -->
																																	</div><!-- end col-md-6 -->

																																</div><!-- end col-md-12 -->

                                                                <div class="col-md-12">
                                                                    <div class="col-md-6">
                                                                    </div><!-- end col-md-6 -->

                                                                    <div class="col-md-6">

                                                                        <div class="form-actions pull-right">
                                                                            <button type="submit" class="btn blue" id="confirm_relocation_btn">Confirm
                                                                            </button>
                                                                            <button type="button" class="btn default">Cancel</button>
                                                                        </div><!-- end form-actions -->

                                                                    </div><!-- end col-md-6 -->

                                                                </div><!-- end col-md-12 -->

                                                                </form>

                                                                <div class="clearfix"></div>

                                                            </div><!-- end form-body -->

                                                        </div><!-- end tab-pane relocation -->

																												<div class="tab-pane" id="tab_searchresult">

																												  <div class="form-body">

																												      <div class="form-group">

																												        @if(Session::has('focus_devotee'))

																												            @php
																												                $focus_devotee = Session::get('focus_devotee');
																												            @endphp

																												          <table class="table table-bordered" id="search_table">
																												              <thead>
																																					<tr id="filter">
																																							<th>Chinese Name</th>
																																							<th>Devotee#</th>
																																							<th>Member#</th>
																																							<th>Address</th>
																																							<th>Guiyi Name</th>
																																							<th>Family Code</th>
																																					</tr>
																												                  <tr>
																												                      <th>Chinese Name</th>
																												                      <th>Devotee#</th>
																												                      <th>Member#</th>
																												                      <th>Address</th>
																												                      <th>Guiyi Name</th>
																												                      <th>Family Code</th>
																												                  </tr>
																												              </thead>

																												              <tbody id="records">
																																					@foreach($focus_devotee as $fd)
																												                  <tr>
																												                      <td>
																																								<a href="/operator/devotee/{{ $fd->devotee_id }}">{{ $fd->chinese_name }}</a>
																																							</td>
																												                      <td>{{ $fd->devotee_id }}</td>
																												                      <td>{{ $fd->member_id }}</td>
																												                      <td>
																																								@if(isset($fd->address_unit1) && isset($fd->address_unit2))
																																								{{ $fd->address_houseno }}, #{{ $fd->address_unit1 }}-{{ $fd->address_unit2 }}, {{ $fd->address_street }}, {{ $fd->address_postal }}
																																								@else
																																								{{ $fd->address_houseno }}, {{ $fd->address_street }}, {{ $fd->address_postal }}
																																								@endif
																																							</td>
																												                      <td>{{ $fd->guiyi_name }}</td>
																												                      <td>{{ $fd->familycode }}</td>
																												                  </tr>
																												                  @endforeach
																												              </tbody>

																												          </table>

																												          @else

																												          <table class="table table-bordered" id="search_table">
																												              <thead>
																												                  <tr>
																												                      <th>Chinese Name</th>
																												                      <th>Devotee#</th>
																												                      <th>Member#</th>
																												                      <th>Address</th>
																												                      <th>Unit</th>
																												                      <th>Guiyi Name</th>
																												                      <th>Family Code</th>
																												                  </tr>
																												              </thead>

																												              <tbody id="no-record">
																												                  <tr>
																												                    <td>No Record Found!</td>
																												                  </tr>
																												              </tbody>

																												          </table>

																												          @endif

																												      </div><!-- end form-group -->

																												  </div><!-- end form-body -->

																												</div><!-- end tab-pane tab_searchresult -->

                                                    </div><!-- end tab-content -->

                                                </div><!-- end tabbable-bordered -->

                                            </div><!-- end portlet-body -->

                                        </div><!-- end portlet -->

                                    </div><!-- end form-horizontal form-row-seperated -->

                                </div><!-- end col-md-10 -->

                            </div><!-- end row -->

                        </div><!-- end box -->

                    </div><!-- end page-content-inner -->

                </div><!-- end container-fluid -->

            </div><!-- end page-content -->

        </div><!-- end page-content-wrapper -->

    </div><!-- end page-container-fluid -->

@stop

@section('custom-js')

    <script src="{{asset('js/custom/common.js')}}"></script>
		<script src="{{asset('js/custom/populate-address.js')}}"></script>
		<script src="{{asset('js/custom/edit-populate-address.js')}}"></script>
    <script src="{{asset('js/custom/search-devotee.js')}}"></script>
    <script src="{{asset('js/custom/check-familycode.js')}}"></script>
    <script src="{{asset('js/custom/edit-check-familycode.js')}}"></script>
		<script src="{{asset('js/custom/relocation-check-familycode.js')}}"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">

        $(function(){

					// Disabled Edit Tab
					$(".nav-tabs > li").click(function(){
						if($(this).hasClass("disabled"))
						return false;
					});

					$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
							localStorage.setItem('activeTab', $(e.target).attr('href'));
					});

					if ( $('.alert-success').children().length > 0 ) {
							localStorage.removeItem('activeTab');
					}

					else
					{
							var activeTab = localStorage.getItem('activeTab');
					}

					if (activeTab) {
							$('a[href="' + activeTab + '"]').tab('show');
							console.log(activeTab);
					}

					$("#opt_address .removeAddressBtn1").first().remove();
					$("#opt_vehicle .removeVehicleBtn1").first().remove();
					$("#special_remark .removeSpecRemarkBtn1").first().remove();
					$("#edit_opt_address .removeAddressBtn1").first().remove();

					$("#content_introduced_by1").autocomplete({
						source: "/operator/search/autocomplete",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#content_introduced_by1').val(ui.item.value);
						}
					});

					$("#content_introduced_by2").autocomplete({
						source: "/operator/search/autocomplete2",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#content_introduced_by2').val(ui.item.value);
						}
					});

					$("#edit_introduced_by1").autocomplete({
						source: "/operator/search/autocomplete",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#edit_introduced_by1').val(ui.item.value);
						}
					});

					$("#edit_introduced_by2").autocomplete({
						source: "/operator/search/autocomplete2",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#edit_introduced_by2').val(ui.item.value);
						}
					});

					$("#address_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#address_street').val(ui.item.value);
						}
					});

					$("#content_address_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#content_address_street').val(ui.item.value);
						}
					});

					$("#edit_address_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#edit_address_street').val(ui.item.value);
						}
					});

					$("#new_address_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#new_address_street').val(ui.item.value);
						}
					});

					$("#populate_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#populate_street').val(ui.item.value);
						}
					});

					$("#edit_populate_street").autocomplete({
						source: "/operator/search/address_street",
						minLength: 1,
					  select: function(event, ui) {
					  	$('#edit_populate_street').val(ui.item.value);
						}
					});

					$(".address_translated_btn").click(function() {

							var count = 0;
							var errors = new Array();
							var validationFailed = false;

							var address_houseno = $("#content_address_houseno").val();
							var address_postal = $("#content_address_postal").val();
							var address_street = $("#content_address_street").val();

							if ($.trim(address_houseno).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Houseno is empty."
							}

							if ($.trim(address_postal).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Postal is empty."
							}

							if ($.trim(address_street).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Street is empty."
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

							else {
									$(".validation-error").removeClass("bg-danger alert alert-error")
									$(".validation-error").empty();
							}

							var formData = {
			        	_token: $('meta[name="csrf-token"]').attr('content'),
			        	address_street: address_street,
			        };

							$.ajax({
					    	type: 'GET',
					      url: "/operator/address-translate",
					      data: formData,
					      dataType: 'json',
					      success: function(response)
					      {
									if($.trim(address_unit1).length <= 0)
									{
										var full_address = "No." + address_houseno + ", " + response.address_translate[0]['chinese'] + ", " + address_building + ", " + address_postal + ", Singapore";

										$("#address_translated").val(full_address);
									}
									else
									{
										var full_address = response.address_translate[0]['chinese'] + ", No." + address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + address_building +  ", " +
																				address_postal + ", Singapore";

										$("#address_translated").val(full_address);
									}
					      },

					      error: function (response) {
					      	console.log(response);
					      }
					   });
 					});

					$(".edit_address_translated_btn").click(function() {

							var count = 0;
							var errors = new Array();
							var validationFailed = false;

							var address_houseno = $("#edit_address_houseno").val();
							var address_postal = $("#edit_address_postal").val();
							var address_street = $("#edit_address_street").val();

							if ($.trim(address_houseno).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Houseno is empty."
							}

							if ($.trim(address_postal).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Postal is empty."
							}

							if ($.trim(address_street).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Address Street is empty."
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

							else {
									$(".validation-error").removeClass("bg-danger alert alert-error")
									$(".validation-error").empty();
							}

							var formData = {
			        	_token: $('meta[name="csrf-token"]').attr('content'),
			        	address_street: address_street,
			        };

							$.ajax({
					    	type: 'GET',
					      url: "/operator/address-translate",
					      data: formData,
					      dataType: 'json',
					      success: function(response)
					      {
									if($.trim(address_unit1).length <= 0)
									{
										var full_address = "No." + address_houseno + ", " + response.address_translate[0]['chinese'] + ", " + address_building + ", " + address_postal + ", Singapore";

										$("#edit_address_translated").val(full_address);
									}
									else
									{
										var full_address = response.address_translate[0]['chinese'] + ", No." + address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + address_building +  ", " +
																				address_postal + ", Singapore";

										$("#edit_address_translated").val(full_address);
									}
					      },

					      error: function (response) {
					      	console.log(response);
					      }
					   });
 					});

					// DataTable
					var table = $('#devotees_table').DataTable({
						"lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
						"order": [[ 1, "desc" ]]
					});

          $('#devotees_table thead tr#filter th').each( function () {
                var title = $('#devotees_table thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" class="form-control" onclick="stopPropagation(event);" placeholder="" />' );
          });

					// Apply the filter
					$("#devotees_table thead input").on( 'keyup change', function () {
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

          // DataTable
          var member_table = $('#members_table').DataTable({
              "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
							"order": [[ 1, "desc" ]]
          });

					$('#members_table thead tr#filter th').each( function () {
                var title = $('#members_table thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" class="form-control" onclick="stopPropagation2(event);" placeholder="" />' );
          });

          // Apply the filter
          $("#members_table thead input").on( 'keyup change', function () {
              member_table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
          });

					function stopPropagation2(evt) {
            if (evt.stopPropagation !== undefined) {
                    evt.stopPropagation();
            } else {
                    evt.cancelBubble = true;
            }
        	}

					// DataTable
          var deceased_table = $('#deceased_table').DataTable({
              "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
							"order": [[ 1, "desc" ]]
          });

					$('#deceased_table thead tr#filter th').each( function () {
                var title = $('#deceased_table thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" class="form-control" onclick="stopPropagation3(event);" placeholder="" />' );
          });

          // Apply the filter
          $("#deceased_table thead input").on( 'keyup change', function () {
              member_table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
          });

					// DataTable
          var search_table = $('#search_table').DataTable({
              "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]]
          });

					$('#search_table thead tr#filter th').each( function () {
                var title = $('#search_table thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" class="form-control" onclick="stopPropagation4(event);" placeholder="" />' );
          });

          // Apply the filter
          $("#search_table thead input").on( 'keyup change', function () {
              search_table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
          });

					function stopPropagation4(evt) {
            if (evt.stopPropagation !== undefined) {
                    evt.stopPropagation();
            } else {
                    evt.cancelBubble = true;
            }
        	}

            var opt_address;

						// $('#quick_search').on('click', function() {
						//
						//
						// 		alert('change tab');
						//
						// 		// $('.nav-tabs a[href="#tab_searchresult"]').tab('show');
						//
						// 		$(".nav-tabs > li:first-child").removeClass("active");
						// 		$('#search').addClass('active');
						// });

						// if($("#quick_search").attr('disabled')){
						// 	// $("#edit").removeClass('disabled');
						// 	// $('.nav-tabs li:eq(6) a').tab('show');
						//
						//
					  // }else{
						// 	$("#edit").addClass('disabled');
					  // }

						// var search_table = $("#search_table");
						//
						// if(search_table.children().length >= 3){
						// 	localStorage.removeItem('activeTab');
						// 	alert('has childern');
						// 	$('.nav-tabs li:eq(6) a').tab('show');
						// }

						var dialect = $("#content_dialect").val();

						if(dialect == 'others')
						{
							$("#other_dialect_field").show();
						}

						$("#content_dialect").change(function() {
							var dialect = $(this).val();

							if(dialect == 'others')
							{
								$("#other_dialect_field").show();
							}

							else {
								$("#other_dialect_field").hide();
							}
						});

						var edit_dialect = $("#edit_dialect").val();

						if(edit_dialect == 'others')
						{
							$("#edit_other_dialect_field").show();
						}

						$("#edit_dialect").change(function() {
							var edit_dialect = $(this).val();

							if(edit_dialect == 'others')
							{
								$("#edit_other_dialect_field").show();
							}

							else {
								$("#edit_other_dialect_field").hide();
							}
						});

						var race = $("#content_race").val();

						if(race == 'others')
						{
							$("#other_race_field").show();
						}

						$("#content_race").change(function() {
							var race = $(this).val();

							if(race == 'others')
							{
								$("#other_race_field").show();
							}

							else {
								$("#other_race_field").hide();
							}
						});

						var edit_race = $("#edit_race").val();

						if(edit_race == 'others')
						{
							$("#edit_other_race_field").show();
						}

						$("#edit_race").change(function() {
							var edit_race = $(this).val();

							if(edit_race == 'others')
							{
								$("#edit_other_race_field").show();
							}

							else {
								$("#edit_other_race_field").hide();
							}
						});

						$("#confirm_btn").click(function() {

							var count = 0;
							var errors = new Array();
							var validationFailed = false;

							var chinese_name = $("#content_chinese_name").val();
							var contact = $("#content_contact").val();
							var address_houseno = $("#content_address_houseno").val();
							var address_street = $("#content_address_street").val();
							var address_postal = $("#content_address_postal").val();
							var oversea_addr_in_chinese = $("#content_oversea_addr_in_chinese").val();
							var authorized_password = $("#content_authorized_password").val();

							var content_nric = $("#content_nirc").val();
							var content_dob = $("#content_dob").val();
							var content_marital_status = $("#content_marital_status").val();
							var content_dialect = $("#content_dialect").val();
							var content_nationality = $("#content_nationality").val();
							var content_introduced_by1 = $("#content_introduced_by1").val();
							var content_introduced_by2 = $("#content_introduced_by2").val();
							var content_approved_date = $("#content_approved_date").val();

							if ($.trim(chinese_name).length <= 0)
							{
							    validationFailed = true;
							    errors[count++] = "Mandatory Chinese name field is empty."
							}

							if ($.trim(contact).length <= 0)
							{
							    validationFailed = true;
							    errors[count++] = "Mandatory Contact field is empty."
							}

							if($.trim(oversea_addr_in_chinese).length <= 0)
							{
								if ($.trim(address_houseno).length <= 0)
								{
								    validationFailed = true;
								    errors[count++] = "Mandatory Address Houseno field is empty."
								}

								if ($.trim(address_street).length <= 0)
								{
								    validationFailed = true;
								    errors[count++] = "Mandatory Address Street field is empty."
								}

								if ($.trim(address_postal).length <= 0)
								{
								    validationFailed = true;
								    errors[count++] = "Mandatory Address Postal field is empty."
								}
							}

							if($.trim(content_approved_date).length > 0)
							{
							  if ($.trim(content_nric).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory NRIC field is empty."
							  }

							  if ($.trim(content_dob).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Date of Birth field is empty."
							  }

							  if ($.trim(content_marital_status).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Marital Status field is empty."
							  }

							  if ($.trim(content_dialect).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Dialect field is empty."
							  }

							  if ($.trim(content_nationality).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Nationality field is empty."
							  }

							  if ($.trim(content_introduced_by1).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Introduced By 1 field is empty."
							  }

							  if ($.trim(content_introduced_by2).length <= 0)
							  {
							      validationFailed = true;
							      errors[count++] = "Mandatory Introduced By 2 field is empty."
							  }
							}

							if ($.trim(authorized_password).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes."
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

            $("#update_btn").click(function() {

								$(".alert-danger").remove();

                var count = 0;
                var errors = new Array();
                var validationFailed = false;

                var chinese_name = $("#edit_chinese_name").val();
                var contact = $("#edit_contact").val();
								var address_houseno = $("#edit_address_houseno").val();
								var address_street = $("#edit_address_street").val();
                var address_postal = $("#edit_address_postal").val();
                var authorized_password = $("#authorized_password").val();

                if ($.trim(chinese_name).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Mandatory Chinese name is empty."
                }

                if ($.trim(contact).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Mandatory Contact field is empty."
                }

								if ($.trim(address_houseno).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Mandatory Address House No field is empty."
                }

								if ($.trim(address_street).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Mandatory Address Street field is empty."
                }

                if ($.trim(address_postal).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Mandatory Address Postal field is empty."
                }

                if ($.trim(authorized_password).length <= 0)
                {
                    validationFailed = true;
                    errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes."
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

						$("#confirm_relocation_btn").click(function() {

							var count = 0;
							var errors = new Array();
							var validationFailed = false;

							var count_checked = $("[name='relocation_devotee_id[]']:checked").length; // count the checked rows

							var address_houseno = $("#new_address_houseno").val();
							var address_street = $("#new_address_street").val();
							var address_postal = $("#new_address_postal").val();
							var nationality = $("#new_nationality").val();
							var oversea_addr_in_chinese = $("#new_oversea_addr_in_chinese").val();
							var authorized_password = $("#relocation_authorized_password").val();

							if(count_checked == 0)
			        {
									validationFailed = true;
			            errors[count++] = "Mandatory Devotee ID field is empty."
			        }

							if ($.trim(oversea_addr_in_chinese).length <= 0)
							{
								if ($.trim(address_houseno).length <= 0)
								{
										validationFailed = true;
										errors[count++] = "Mandatory Address House No field is empty."
								}

								if ($.trim(address_street).length <= 0)
								{
										validationFailed = true;
										errors[count++] = "Mandatory Address Street field is empty."
								}

								if ($.trim(address_postal).length <= 0)
								{
										validationFailed = true;
										errors[count++] = "Mandatory Address Postal field is empty."
								}
							}

							if ($.trim(nationality).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Mandatory Country field is empty."
							}

							if ($.trim(authorized_password).length <= 0)
							{
									validationFailed = true;
									errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes."
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

						if ( $('.alert-success').children().length > 0 ) {
				        localStorage.removeItem('devotee_id');
				        localStorage.removeItem('familycode_id');
				        localStorage.removeItem('member_id');
				    }


            $("#appendAddressBtn").click(function() {

                $("#append_opt_address").append("<div class='inner_opt_addr'><div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeAddressBtn' aria-hidden='true'></i></div>" +
										"<label class='col-md-2 control-label'>Opt.Addr</label><div class='col-md-3'>" +
                    "<select class='form-control address-type' name='address_type[]'><option value='home'>Home</option>" +
                    "<option value='company'>Comp</option><option value='stall'>Stall</option><option value='office'>Office</option>" +
                    "</select></div><div class='col-md-4'><input type='text' class='form-control address-data' name='address_data[]' readonly value='Please fill the address on the right' title='Please fill the address on the right'></div>" +
                    "<div class='col-md-2'><button type='button' class='fa fa-angle-double-right populate-data form-control' aria-hidden='true'>" +
										"</button></div></div>" +
										"<div class='form-group' style='margin-bottom: 0;'><div class='col-md-1'></div>" +
										"<label class='col-md-2'></label><div class='col-md-3'>" +
		                "</div><div class='col-md-4 populate-address'><input type='hidden' class='form-control address-data-hidden' name='address_data_hidden[]'></div>" +
		                "<div class='col-md-2'></div></div></div></div>");
            });

            $("#AddressBtn").click(function() {

                $("#edit_opt_address").append("<div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeAddressBtn1' aria-hidden='true'></i></div>" +
										"<label class='col-md-2'>Opt.Addr</label><div class='col-md-3'>" +
                    "<select class='form-control address-type' name='address_type[]'><option value='home'>Home</option>" +
                    "<option value='company'>Comp</option><option value='stall'>Stall</option><option value='office'>Office</option>" +
                    "</select></div><div class='col-md-4'><input type='text' class='form-control edit-address-data' name='address_data[]'></div>" +
                    "<div class='col-md-2'><button type='button' class='fa fa-angle-double-right edit-populate-data form-control' aria-hidden='true'></button></div></div>");
            });

            $("#append_opt_address").on('click', '.removeAddressBtn', function() {
                $(this).parent().parent().parent().remove();
            });

            $("#edit_opt_address").on('click', '.removeAddressBtn1', function() {
                $(this).parent().parent().remove();
            });

            $("#appendVehicleBtn").click(function() {

                $("#append_opt_vehicle").append("<div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeVehicleBtn' aria-hidden='true'></i></div>" +
										"<label class='col-md-2'>Opt.Vehicle</label>" +
                    "<div class='col-md-3'><select class='form-control' name='vehicle_type[]'><option value='car'>Car</option>" +
                    "<option value='ship'>Ship</option></select></div><div class='col-md-6'>" +
                    "<input type='text' class='form-control' name='vehicle_data[]'></div>" +
                    "</div>");
            });

            $("#VehicleBtn").click(function() {

                $("#opt_vehicle").append("<div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeVehicleBtn1' aria-hidden='true'></i></div>" +
										"<label class='col-md-2'>Opt.Vehicle " +
                    "</label><div class='col-md-3'><select class='form-control' name='vehicle_type[]'><option value='car'>Car</option>" +
                    "<option value='ship'>Ship</option></select></div><div class='col-md-6'>" +
                    "<input type='text' class='form-control' name='vehicle_data[]'></div>" +
                    "</div>");
            });

            $("#append_opt_vehicle").on('click', '.removeVehicleBtn', function() {
                $(this).parent().parent().remove();
            });

            $("#opt_vehicle").on('click', '.removeVehicleBtn1', function() {
                $(this).parent().parent().remove();
            });

            $("#appendSpecRemarkBtn").click(function() {

                $("#append_special_remark").append("<div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeSpecRemarkBtn' aria-hidden='true'></i></div>" +
										"<label class='col-md-2'>Special Remark</label>" +
                    "<div class='col-md-9'><input type='text' class='form-control' name='special_remark[]'></div>" +
                    "</div>");
            });

            $("#SpecRemarkBtn").click(function() {

                $("#special_remark").append("<div class='form-group'><div class='col-md-1'><i class='fa fa-minus-circle removeSpecRemarkBtn1' aria-hidden='true'></i></div>" +
										"<label class='col-md-2'>Special Remark" +
                    "</label><div class='col-md-9'><input type='text' class='form-control' name='special_remark[]'></div>" +
                    "</div>");
            });

            $("#append_special_remark").on('click', '.removeSpecRemarkBtn', function() {
                $(this).parent().parent().remove();
            });

            $("#special_remark").on('click', '.removeSpecRemarkBtn1', function() {
                $(this).parent().parent().remove();
            });
        });
    </script>

@stop
