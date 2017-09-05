@extends('layouts.backend.app')

@section('main-content')

@php
		$xianyou_same_family = Session::get('xianyou_same_family');
		$xianyou_different_family = Session::get('xianyou_different_family');
		$yuejuan_same_family = Session::get('yuejuan_same_family');
		$yuejuan_different_family = Session::get('yuejuan_different_family');
		$focusdevotee_amount = Session::get('focusdevotee_amount');
		$samefamily_amount = Session::get('samefamily_amount');
		$differentfamily_amount = Session::get('differentfamily_amount');

		$cancellation_focusdevotee = Session::get('cancellation_focusdevotee_xiangyou');
		$cancellation_sameaddr_xiangyou = Session::get('cancellation_sameaddr_xiangyou');
		$cancellation_differentaddr_xiangyou = Session::get('cancellation_differentaddr_xiangyou');
		$focus_devotee = Session::get('focus_devotee');
		$date = \Carbon\Carbon::now()->subDays(365);
		$now = \Carbon\Carbon::now();

@endphp


	<div class="page-container-fluid">

		<div class="page-content-wrapper">

			<div class="page-head">

                <div class="container-fluid">

                	<div class="page-title">

                        <h1>General Donation 乐捐</h1>

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
                            <span>General Donation</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                    	<div class="inbox">

                    		 <div class="row">

                    		 	@include('layouts.partials.focus-devotee-sidebar')

                    		 	<div class="col-md-9">

                    		 		<div class="form-horizontal form-row-seperated">

                    		 			<div class="portlet">

                              	<div class="validation-error">
                                </div><!-- end validation-error -->

                    		 				@if($errors->any())

                                <div class="alert alert-danger">

                                    @foreach($errors->all() as $error)
                                      <p>{{ $error }}</p>
                                    @endforeach

																</div><!-- end alert -->

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
                                                    <a href="#tab_xiangyou" data-toggle="tab">Xiangyou <br>香油</a>
                                                  </li>
                                                  <li>
                                                    <a href="#tab_ciji" data-toggle="tab">Ciji <br> 慈济</a>
                                                  </li>
                                                  <li>
                                                    <a href="#tab_yuejuan" data-toggle="tab">Yuejuan <br> 月捐 </a>
                                                  </li>
                                                  <li class="disabled">
                                                    <a href="#tab_others" data-toggle="tab">Others <br> 其他 </a>
                                                  </li>

																									<li class="pull-right">
                                                    <a href="#tab_transactiondetail" data-toggle="tab">Search <br> 交易详情 </a>
                                                  </li>
																									<li class="pull-right">
                                                    <a href="#tab_relative_friends" data-toggle="tab">Relative & Friends <br> 亲戚朋友 </a>
                                                  </li>
																									<li class="pull-right">
                                                    <a href="#tab_samefamily" data-toggle="tab">Same Family Code <br> 同址善信 </a>
                                                  </li>
                                                </ul>

                                                    <div class="tab-content">

                                                    	<div class="tab-pane active" id="tab_xiangyou">

                                                    		<div class="form-body">

                                                    			<form target="_blank" method="post" action="{{ URL::to('/staff/donation') }}"
                                                    				class="form-horizontal form-bordered" id="donation-form">

                                                    				{!! csrf_field() !!}

                                                    			<div class="form-group">

                                                    				<h4>Same address Devotee 同址善信</h4>

                                                                    <table class="table table-bordered" id="generaldonation_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Chinese Name</th>
                                                                                <th>Devotee#</th>
																																								<th>Member#</th>
                                                                                <th>Address</th>
                                                                                <th>Guiyi Name</th>
                                                                                <th width="80px">Amount</th>
                                                                                <th width="80px">Paid Till</th>
                                                                                <th width="100px">HJ/ GR</th>
                                                                                <th width="80px">Display</th>
                                                                                <th>XYReceipt</th>
                                                                                <th>Trans Date</th>
                                                                            </tr>
                                                                        </thead>

                                                                        @if(Session::has('devotee_lists'))

                                                                        <tbody id="has_session">

                                                                            @if(count($xianyou_same_family) > 0)

																																						@foreach($xianyou_same_family as $xs_family)

                                                                            <tr>
                                                                            	<td>
																																								@if($xs_family->deceased_year != null)
																																								<span class="text-danger">{{ $xs_family->chinese_name }}</span>
																																								@else
																																								<span>{{ $xs_family->chinese_name }}</span>
																																								@endif
																																							</td>
                                                                            	<td>
																																								@if($xs_family->specialremarks_devotee_id == null)
																																								<span id="devotee">{{ $xs_family->devotee_id }}</span>
																																								@else
																																								<span class="text-danger" id="devotee">{{ $xs_family->devotee_id }}</span>
																																								@endif
                                                                            		<input type="hidden" name="devotee_id[]" value="{{ $xs_family->devotee_id }}">
                                                                            	</td>
																																							<td>
																																								@if(\Carbon\Carbon::parse($xs_family->lasttransaction_at)->lt($date))
																																								<span style="color: #a5a5a5">{{ $xs_family->member_id }}</span>
																																								@else
																																								<span>{{ $xs_family->member_id }}</span>
																																								@endif
																																							</td>
                                                                            	<td>
																																								@if(isset($xs_family->oversea_addr_in_chinese))
																																									{{ $xs_family->oversea_addr_in_chinese }}
																																								@elseif(isset($xs_family->address_unit1) && isset($xs_family->address_unit2))
																																									{{ $xs_family->address_houseno }}, #{{ $xs_family->address_unit1 }}-{{ $xs_family->address_unit2 }}, {{ $xs_family->address_street }}, {{ $xs_family->address_postal }}
																																								@else
																																									{{ $xs_family->address_houseno }}, {{ $xs_family->address_street }}, {{ $xs_family->address_postal }}
																																								@endif
																																							</td>
                                                                            	<td>{{ $xs_family->guiyi_name }}</td>
                                                                            	<td width="80px" class="amount-col">
                                                                            		<input type="text" class="form-control amount" name="amount[]">
                                                                            	</td>
                                                                            	<td width="80px">
																																								@if(isset($xs_family->paytill_date) && \Carbon\Carbon::parse($xs_family->paytill_date)->lt($now))
																																								<span class="text-danger">{{ \Carbon\Carbon::parse($xs_family->paytill_date)->format("d/m/Y") }}</span>
																																								@elseif(isset($xs_family->paytill_date))
																																								<span>{{ \Carbon\Carbon::parse($xs_family->paytill_date)->format("d/m/Y") }}</span>
																																								@else
																																								<span>{{ $xs_family->paytill_date }}</span>
																																								@endif
                                                                            	</td>
                                                                            	<td width="100px">
                                                                            		<select class="form-control hjgr" name="hjgr_arr[]">
	                                                                                    <option value="hj">合家</option>
	                                                                                    <option value="gr">个人</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td width="80px">
                                                                            		<select class="form-control display" name="display[]">
																																									<option value="N">N</option>
																																									<option value="Y">Y</option>
	                                                                              </select>
                                                                            	</td>
                                                                            	<td></td>
                                                                            	<td></td>
                                                                            </tr>

                                                                            @endforeach

																																						@endif

                                                                        </tbody>

                                                                        @else

                                                                        <tbody id="no_session">
                                                                          <tr>
	                                                                          <td colspan="12">No Data</td>
	                                                                        </tr>
                                                                        </tbody>

                                                                        @endif

                                                                    </table>

                                                                </div><!-- end form-group -->

                                                                <div class="form-group">
                                                    							<h4>Relatives and friends 亲戚朋友</h4>
                                                                </div><!-- end form-group -->

                                                                <div class="form-group">

                                                                    <table class="table table-bordered" id="generaldonation_table2">
                                                                        <thead>
                                                                          <tr>
                                                                            <th>Chinese Name</th>
                                                                            <th>Devotee#</th>
																																						<th>Member#</th>
                                                                            <th>Address</th>
                                                                        		<th>Guiyi Name</th>
                                                                            <th width="80px">Amount</th>
                                                                            <th width="80px">Paid Till</th>
                                                                            <th width="100px">HJ/ GR</th>
                                                                            <th width="80px">Display</th>
                                                                            <th>XYReceipt</th>
                                                                            <th>Trans Date</th>
                                                                          </tr>
                                                                        </thead>

																																				@if(Session::has('xianyou_different_family'))

																																				<tbody id="appendDevoteeLists">

																																					@if(count($xianyou_different_family) > 0)

																																					@foreach($xianyou_different_family as $list)

                                                                            <tr>
                                                                            	<td>
																																								@if($list->deceased_year != null)
																																								<span class="text-danger">{{ $list->chinese_name }}</span>
																																								@else
																																								<span>{{ $list->chinese_name }}</span>
																																								@endif
																																							</td>
																																							<td>
																																								@if($list->specialremarks_devotee_id == null)
																																								<span id="devotee">{{ $list->devotee_id }}</span>
																																								@else
																																								<span class="text-danger" id="devotee">{{ $list->devotee_id }}</span>
																																								@endif
																																							<input type="hidden" name="other_devotee_id[]" value="{{ $list->devotee_id }}">
																																							</td>
																																							<td>
																																								@if(\Carbon\Carbon::parse($list->lasttransaction_at)->lt($date))
																																								<span style="color: #a5a5a5">{{ $list->member_id }}</span>
																																								@else
																																								<span>{{ $list->member_id }}</span>
																																								@endif
																																							</td>
																																							<td>
																																								@if(isset($list->oversea_addr_in_chinese))
																																									{{ $list->oversea_addr_in_chinese }}
																																								@elseif(isset($list->address_unit1) && isset($list->address_unit2))
																																									{{ $list->address_houseno }}, #{{ $list->address_unit1 }}-{{ $list->address_unit2 }}, {{ $list->address_street }}, {{ $list->address_postal }}
																																								@else
																																									{{ $list->address_houseno }}, {{ $list->address_street }}, {{ $list->address_postal }}
																																								@endif
																																							</td>
																																							<td>{{ $list->guiyi_name }}</td>
																																							<td class="amount-col">
                                                                            		<input type="text" class="form-control amount other_amount" name="other_amount[]">
                                                                            	</td>
                                                                            	<td>
																																								@if(isset($list->paytill_date) && \Carbon\Carbon::parse($list->paytill_date)->lt($now))
																																								<span class="text-danger">{{ \Carbon\Carbon::parse($list->paytill_date)->format("d/m/Y") }}</span>
																																								@elseif(isset($list->paytill_date))
																																								<span>{{ \Carbon\Carbon::parse($list->paytill_date)->format("d/m/Y") }}</span>
																																								@else
																																								<span>{{ $list->paytill_date }}</span>
																																								@endif
                                                                            	</td>
                                                                            	<td>
                                                                            		<select class="form-control hjgr" name="other_hjgr_arr[]">
	                                                                                    <option value="hj">合家</option>
	                                                                                    <option value="gr">个人</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td>
                                                                            		<select class="form-control display" name="other_display[]">
																																									<option value="N">N</option>
	                                                                                <option value="Y">Y</option>
	                                                                              </select>
                                                                            	</td>
                                                                            	<td></td>
                                                                            	<td></td>
                                                                            </tr>
																																					@endforeach

																																					@endif

																																				</tbody>

																																				@else

																																				<tbody id="no_session">
                                                                          <tr>
	                                                                          <td colspan="12">No Data</td>
	                                                                        </tr>
                                                                        </tbody>

																																				@endif
                                                                    </table>

                                                                </div><!-- end form-group -->

                                                    		</div><!-- end form-body -->

                                                    		<hr>

                                                    		<div class="form-body">

                                                    			<div class="form-group">

	                                                    			<div class="col-md-12">
	                                                    				<h5><b>Total Amount: S$ <span class="total"></span></b></h5>
	                                                    			</div><!-- end col-md-12 -->

	                                                    		</div><!-- end form-group -->

	                                                    		<div class="form-group">

	                                                    			<div class="col-md-12">

	                                                    				<div class="col-md-6">

	                                                    					<div class="form-group">

		                                                                        <label class="col-md-3">Transation No:</label>
		                                                                        <label class="col-md-9" id="trans_info"></label><!-- end col-md-8 -->

		                                                                    </div><!-- end form-group -->

		                                                                    <div class="form-group">

		                                                                        <label class="col-md-12">Mode of Payment</label>

		                                                                    </div><!-- end form-group -->

		                                                                    <div class="form-group">

		                                                                        <div class="col-md-12">
		                                                                        	<div class="mt-radio-list">

				                                                                        <div class="col-md-6">
				                                                                        	<label class="mt-radio mt-radio-outline"> Cash
					                                                                            <input type="radio" name="mode_payment"
					                                                                            	value="cash" checked>
					                                                                            <span></span>
					                                                                        </label>
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="col-md-6">
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="clearfix"></div>

				                                                                        <div class="col-md-6">
				                                                                        	<label class="mt-radio mt-radio-outline"> Cheque
					                                                                            <input type="radio" name="mode_payment"
					                                                                            	value="cheque" class="form-control">
					                                                                            <span></span>
					                                                                        </label>
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="col-md-6">
				                                                                        	<input type="text" name="cheque_no" value=""
				                                                                        		class="form-control input-small" id="cheque_no">
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="clearfix"></div>

				                                                                        <div class="col-md-6">
				                                                                        	<label class="mt-radio mt-radio-outline"> NETS
					                                                                            <input type="radio" name="mode_payment"
					                                                                            	value="nets">
					                                                                            <span></span>
					                                                                        </label>
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="col-md-6">
																																									<input type="text" name="nets_no" value=""
				                                                                        		class="form-control input-small" id="nets_no">
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="clearfix"></div>

				                                                                        <div class="col-md-6">
				                                                                        	<label class="mt-radio mt-radio-outline"> Manual Receipt
					                                                                            <input type="radio" name="mode_payment"
					                                                                            	value="receipt">
					                                                                            <span></span>
					                                                                        </label>
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="col-md-6">
				                                                                        	<input type="text" name="manualreceipt" value=""
				                                                                        		class="form-control input-small"
                                                                                                id="manualreceipt">
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="clearfix"></div>

				                                                                        <div class="col-md-6">
				                                                                        	<label class="mt-radio mt-radio-outline">
				                                                                        		Date of Receipts
					                                                                        </label>
				                                                                        </div><!-- end col-md-6 -->

				                                                                        <div class="col-md-6">
				                                                                        	<input type="text" name="receipt_at" class="form-control input-small"
				                                                                        		data-provide="datepicker" data-date-format="dd/mm/yyyy" id="receipt_at">
				                                                                    	</div><!-- end col-md-6 -->

				                                                                    </div><!-- end mt-radio-list -->

		                                                                        </div><!-- end col-md-12 -->

		                                                                    </div><!-- end form-group -->

		                                                    			</div><!-- end col-md-6 -->

		                                                    			<div class="col-md-6">

		                                                    				<div class="form-group">

		                                                                        <label class="col-md-12">Type of Receipt Printing</label>

		                                                                    </div><!-- end form-group -->

		                                                                    <div class="form-group">

		                                                                        <div class="col-md-12">
		                                                                        	<div class="mt-radio-list">

				                                                                        <label class="mt-radio mt-radio-outline"> 1 Receipt Printing for Same Address
				                                                                            <input type="radio" name="hjgr" value="hj" checked>
				                                                                            <span></span>
				                                                                        </label>

				                                                                        <label class="mt-radio mt-radio-outline"> Individual Receipt Printing
				                                                                            <input type="radio" name="hjgr" value="gr">
				                                                                            <span></span>
				                                                                        </label>
				                                                                    </div><!-- end mt-radio-list -->

		                                                                        </div><!-- end col-md-12 -->

		                                                                    </div><!-- end form-group -->

																																				<div class="form-group" style="display: none">

						                                                           		<label class="col-md-12">Event</label>

						                                                           	</div><!-- end form-group -->

																																				<div class="form-group" style="display: none">

																																					<div class="col-md-12">

																																						<div class="col-md-9">

																																								<select class="form-control" name="festiveevent_id">
																																										@foreach($events as $event)
																																										<option value="{{ $event->festiveevent_id }}">
																																												{{ \Carbon\Carbon::parse($event->start_at)->format("d/m/Y") }} ({{ $event->event }})
																																										</option>
																																										@endforeach
																																								</select>

		                                                                        </div><!-- end col-md-9 -->

																																						<div class="col-md-3">
																																						</div><!-- end col-md-3 -->

																																					</div><!-- end col-md-12 -->

		                                                                  	</div><!-- end form-group -->

		                                                    			</div><!-- end col-md-6 -->

	                                                    			</div><!-- end col-md-12 -->

	                                                    		</div><!-- end form-group -->

	                                                    		@if(Session::has('focus_devotee'))
	                                                    		<div class="form-group">
	                                                    			<input type="hidden" name="focusdevotee_id"
	                                                    				value="{{ $focus_devotee[0]->devotee_id }}">
	                                                    			<input type="hidden" name="total_amount" id="total_amount" value="">
																														<input type="hidden" name="minimum_amount" id="minimum_amount" value="{{ $amount[0]->minimum_amount }}">
	                                                    		</div>

	                                                    		@else

	                                                    		<div class="form-group">
	                                                    			<input type="hidden" name="focusdevotee_id"
	                                                    				value="">
	                                                    			<input type="hidden" name="total_amount" id="total_amount" value="">
																														<input type="hidden" name="minimum_amount" id="minimum_amount" value="{{ $amount[0]->minimum_amount }}">
	                                                    		</div>

	                                                    		@endif

	                                                    		<div class="form-group">

	                                                    			<div class="col-md-12">

	                                                    				<div class="form-actions">
	                                                              <button type="submit" class="btn blue" id="confirm_donation_btn">Confirm
	                                                              </button>
	                                                              <button type="button" class="btn default">Cancel</button>
	                                                            </div><!-- end form-actions -->

	                                                    			</div><!-- end col-md-12 -->

	                                                    		</div><!-- end form-group -->

	                                                    		</form>

                                                    		</div><!-- end form-body -->

                                                            <hr>

                                                            <div class="form-body">

                                                                <div class="form-group portlet-body">

                                                                    <table class="table table-bordered order-column" id="receipt_history_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>XY Receipt</th>
                                                                                <th>Trans Date</th>
                                                                                <th>Transaction</th>
                                                                                <th>Description</th>
                                                                                <th>Paid By</th>
                                                                                <th>Devotee ID</th>
                                                                                <th>HJ/ GR</th>
                                                                                <th>Amount</th>
                                                                                <th>Manual Receipt</th>
																																								<th>View Details</th>
                                                                            </tr>
                                                                        </thead>

                                                                        @if(Session::has('receipts'))

                                                                            @php

                                                                                $receipts = Session::get('receipts');
                                                                            @endphp

                                                                            <tbody>
                                                                                @foreach($receipts as $receipt)
                                                                                <tr>
                                                                                    <td>{{ $receipt->receipt_no }}</td>
                                                                                    <td>{{ \Carbon\Carbon::parse($receipt->trans_at)->format("d/m/Y") }}</td>
                                                                                    <td>{{ $receipt->trans_no }}</td>
                                                                                    <td>{{ $receipt->description }}</td>
                                                                                    <td>{{ $receipt->chinese_name }}</td>
                                                                                    <td>{{ $receipt->focusdevotee_id }}</td>
                                                                                    <td>
																																											@if($receipt->hjgr == "hj")
																																											合家
																																											@else
																																											个人
																																											@endif
																																										</td>
                                                                                    <td>{{ $receipt->total_amount }}</td>
                                                                                    <td>{{ $receipt->manualreceipt }}</td>
																																										<td><a href="#tab_transactiondetail" data-toggle="tab" id="{{ $receipt->trans_no }}" class="receipt-id">Detail</a></td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>

                                                                        @else



                                                                        @endif


                                                                    </table>

                                                                </div><!-- end form-group -->

                                                            </div><!-- end form-body -->

                                                    	</div><!-- end tab-pane -->

																											<div class="tab-pane" id="tab_ciji">
																												@include('layouts.partials.tab-ciji')
																											</div><!-- end tab-pane tab_ciji -->

																											<div class="tab-pane" id="tab_yuejuan">
																												@include('layouts.partials.tab-yuejuan')
																											</div><!-- end tab-pane tab_ciji -->

																											<div class="tab-pane" id="tab_samefamily">
																												@include('layouts.partials.tab-xiangyou-samefamily')
																											</div><!-- end tab-pane tab_samefamily -->

																											<div class="tab-pane" id="tab_relative_friends">
																												@include('layouts.partials.tab-xiangyou-relative-friends')
																											</div><!-- end tab-pane tab_relative_friends -->

																											<div class="tab-pane" id="tab_transactiondetail">
																												@include('layouts.partials.tab-xiangyou-transactiondetail')
																											</div><!-- end tab-pane tab_transactiondetail -->

                                                    </div><!-- end tab-content -->

                                            	</div><!-- end tabbable-bordered -->

                                            </div><!-- end portlet-body -->

                    		 			</div><!-- end portlet -->

                    		 		</div><!-- end form-horizontal -->

                    		 	</div><!-- end col-md-9 -->

                    		 </div><!-- end row -->

                    	</div><!-- end inbox -->

                    </div><!-- end page-content-inner -->

            	</div><!-- end container-fluid -->

            </div><!-- end page-content -->

		</div><!-- end page-content-wrapper -->

	</div><!-- end page-container-fluid -->

@stop

@section('custom-js')

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{asset('js/custom/common.js')}}"></script>
<script src="{{asset('js/custom/search-devotee.js')}}"></script>
<script src="{{asset('js/custom/search-relative-friends.js')}}"></script>
<script src="{{asset('js/custom/transaction-detail.js')}}"></script>
<script src="{{asset('js/custom/ciji.js')}}"></script>
<script src="{{asset('js/custom/yuejuan.js')}}"></script>

	<script type="text/javascript">
		$(function() {

			// $(".total").text(0);
			window.onhashchange = function() {
			 alert('back now');
			}


			// Disabled Edit Tab
			$(".nav-tabs > li").click(function(){
					if($(this).hasClass("disabled"))
							return false;
			});

			$('body').on('input', '.amount-col', function(){
	        var sum = 0;

			  $(".amount").each(function(){

			    sum += +$(this).val();

					$(".total").html(sum);
					$("#total_amount").val(sum);
		  	});
	    });

			$('#update_sameaddr_btn').click(function() {

				var count = 0;
				var errors = new Array();
				var validationFailed = false;

	      checked = $("#samefamily_form input[type=checkbox]:checked").length;

	      if(!checked) {
					validationFailed = true;
					errors[count++] = "You must check at least one checkbox.";
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

			$("#cancel_samefamily_btn").click(function() {
				$('.same input:checkbox').removeAttr('checked');
			});

			$("#samefamily_form").submit(function() {

				var this_master = $(this);

				this_master.find("input[name='xiangyou_ciji_id[]']").each( function () {
						var checkbox_this = $(this);
						var hidden_xiangyou_ciji_id = checkbox_this.closest('.checkbox-col').find('.hidden_xiangyou_ciji_id');

						if( checkbox_this.is(":checked") == true ) {
								hidden_xiangyou_ciji_id.attr('value','1');
						}

						else {
								hidden_xiangyou_ciji_id.prop('checked', true);
								//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
								hidden_xiangyou_ciji_id.attr('value','0');
						}
				});

				this_master.find("input[name='yuejuan_id[]']").each( function () {
						var checkbox_this = $(this);
						var hidden_yuejuan_id = checkbox_this.closest('.checkbox-col').find('.hidden_yuejuan_id');

						if( checkbox_this.is(":checked") == true ) {
								hidden_yuejuan_id.attr('value','1');
						}

						else {
								hidden_yuejuan_id.prop('checked', true);
								//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
								hidden_yuejuan_id.attr('value','0');
						}
				});
			});

			$("#differentfamily_form").submit(function() {

				var this_master = $(this);

				this_master.find("input[name='xiangyou_ciji_id[]']").each( function () {
						var checkbox_this = $(this);
						var hidden_xiangyou_ciji_id = checkbox_this.closest('.checkbox-col').find('.hidden_xiangyou_ciji_id');

						if( checkbox_this.is(":checked") == true ) {
								hidden_xiangyou_ciji_id.attr('value','1');
						}

						else {
								hidden_xiangyou_ciji_id.prop('checked', true);
								//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
								hidden_xiangyou_ciji_id.attr('value','0');
						}
				});

				this_master.find("input[name='yuejuan_id[]']").each( function () {
						var checkbox_this = $(this);
						var hidden_yuejuan_id = checkbox_this.closest('.checkbox-col').find('.hidden_yuejuan_id');

						if( checkbox_this.is(":checked") == true ) {
								hidden_yuejuan_id.attr('value','1');
						}

						else {
								hidden_yuejuan_id.prop('checked', true);
								//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
								hidden_yuejuan_id.attr('value','0');
						}
				});
			});

			$("body").delegate('.amount', 'focus', function() {

				var minimum_amount = parseInt($("#minimum_amount").val());

				$(this).on("change",function (){

					var amount = parseInt($(this).val());

					if(amount > minimum_amount)
					{
						$(this).closest('tr').find(".display").val('Y');
					}
					else
					{
						$(this).closest('tr').find(".display").val('N');
					}
				});
			});

			window.onbeforeunload = function() {

			}

 		});
	</script>

@stop
