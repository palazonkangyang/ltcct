@extends('layouts.backend.app')

@section('main-content')

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
                                                  <li class="disabled">
                                                    <a href="#tab_ciji" data-toggle="tab">Ciji <br> 慈济</a>
                                                  </li>
                                                  <li class="disabled">
                                                    <a href="#tab_yuejuan" data-toggle="tab">Yuejuan <br> 月捐 </a>
                                                  </li>
                                                  <li class="disabled">
                                                    <a href="#tab_others" data-toggle="tab">Others <br> 其他 </a>
                                                  </li>

																									<li class="pull-right">
                                                    <a href="#tab_relative_friends" data-toggle="tab">Relative & Friends <br> 其他 </a>
                                                  </li>
																									<li class="pull-right">
                                                    <a href="#tab_samefamily" data-toggle="tab">Same Family Code <br> 其他 </a>
                                                  </li>
                                                </ul>

                                                    <div class="tab-content">

                                                    	<div class="tab-pane active" id="tab_xiangyou">

                                                    		<div class="form-body">

                                                    			<form method="post" action="{{ URL::to('/staff/donation') }}"
                                                    				class="form-horizontal form-bordered" id="">

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
                                                                                <th>Amount</th>
                                                                                <th>Pay Till</th>
                                                                                <th>HJ/ GR</th>
                                                                                <th>Display</th>
                                                                                <th>XYReceipt</th>
                                                                                <th>Trans Date</th>
                                                                            </tr>
                                                                        </thead>

                                                                        @if(Session::has('devotee_lists'))

                                                                            @php

                                                                                $xianyou_same_family = Session::get('xianyou_same_family');
                                                                                $focus_devotee = Session::get('focus_devotee');
																																								$date = \Carbon\Carbon::now()->subDays(365);
																																								$now = \Carbon\Carbon::now();

                                                                            @endphp

                                                                        <tbody id="has_session">

                                                                            <tr>
                                                                            	<td>
																																								@if($focus_devotee[0]->deceased_year != null)
																																								<span class="text-danger">{{ $focus_devotee[0]->chinese_name }}</span>
																																								@else
																																								<span>{{ $focus_devotee[0]->chinese_name }}</span>
																																								@endif
																																							</td>
                                                                            	<td>
																																								@if($focus_devotee[0]->specialremarks_devotee_id == null)
																																								<span>{{ $focus_devotee[0]->devotee_id }}</span>
																																								@else
																																								<span class="text-danger">{{ $focus_devotee[0]->devotee_id }}</span>
																																								@endif
                                                                            		<input type="hidden" name="devotee_id[]" value="{{ $focus_devotee[0]->devotee_id }}">
                                                                            	</td>
																																							<td>
																																								@if(\Carbon\Carbon::parse($focus_devotee[0]->lasttransaction_at)->lt($date))
																																								<span style="color: #a5a5a5">{{ $focus_devotee[0]->member_id }}</span>
																																								@else
																																								<span>{{ $focus_devotee[0]->member_id }}</span>
																																								@endif
																																							</td>
                                                                            	<td>
																																								@if(isset($focus_devotee[0]->oversea_addr_in_chinese))
																																									{{ $focus_devotee[0]->oversea_addr_in_chinese }}
																																								@elseif(isset($focus_devotee[0]->address_unit1) && isset($focus_devotee[0]->address_unit2))
																																									{{ $focus_devotee[0]->address_houseno }}, #{{ $focus_devotee[0]->address_unit1 }}-{{ $focus_devotee[0]->address_unit2 }}, {{ $focus_devotee[0]->address_street }}, {{ $focus_devotee[0]->address_postal }}
																																								@else
																																									{{ $focus_devotee[0]->address_houseno }}, {{ $focus_devotee[0]->address_street }}, {{ $focus_devotee[0]->address_postal }}
																																								@endif
																																							</td>
                                                                            	<td>{{ $focus_devotee[0]->guiyi_name }}</td>
                                                                            	<td width="100px">
                                                                            		<input type="text" class="form-control amount" name="amount[]">
                                                                            	</td>
                                                                            	<td width="80px">
																																								@if(isset($focus_devotee[0]->paytill_date) && \Carbon\Carbon::parse($focus_devotee[0]->paytill_date)->lt($now))
																																								<span class="text-danger">{{ \Carbon\Carbon::parse($focus_devotee[0]->paytill_date)->format("d/m/Y") }}</span>
																																								@elseif(isset($focus_devotee[0]->paytill_date))
																																								<span>{{ \Carbon\Carbon::parse($focus_devotee[0]->paytill_date)->format("d/m/Y") }}</span>
																																								@else
																																								<span>{{ $focus_devotee[0]->paytill_date }}</span>
																																								@endif
                                                                            	</td>
                                                                            	<td width="120px">
                                                                            		<select class="form-control" name="hjgr_arr[]">
	                                                                                    <option value="hj">hj</option>
	                                                                                    <option value="gr">gr</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td width="80px">
                                                                            		<select class="form-control" name="display[]">
	                                                                                    <option value="Y">Y</option>
	                                                                                    <option value="N">N</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td></td>
                                                                            	<td></td>
                                                                            </tr>

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
																																								<span>{{ $xs_family->devotee_id }}</span>
																																								@else
																																								<span class="text-danger">{{ $xs_family->devotee_id }}</span>
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
                                                                            	<td width="100px" class="amount-col">
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
                                                                            		<select class="form-control" name="hjgr_arr[]">
	                                                                                    <option value="hj">hj</option>
	                                                                                    <option value="gr">gr</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td width="80px">
                                                                            		<select class="form-control" name="display[]">
	                                                                                    <option value="Y">Y</option>
	                                                                                    <option value="N">N</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td></td>
                                                                            	<td></td>
                                                                            </tr>

                                                                            @endforeach

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
                                                                                <th width="100px">Amount</th>
                                                                                <th width="80px">Pay Till</th>
                                                                                <th width="100px">HJ/ GR</th>
                                                                                <th width="80px">Display</th>
                                                                                <th>XYReceipt</th>
                                                                                <th>Trans Date</th>
                                                                            </tr>
                                                                        </thead>

																																				@if(Session::has('xianyou_different_family'))

																																				@php $xianyou_different_family = Session::get('xianyou_different_family'); @endphp

																																				<tbody id="appendDevoteeLists">

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
																																								<span>{{ $list->devotee_id }}</span>
																																								@else
																																								<span class="text-danger">{{ $list->devotee_id }}</span>
																																								@endif
																																							<input type="hidden" name="other_devotee_id[]"
																																							value="{{ $list->devotee_id }}">
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
                                                                            		<select class="form-control" name="other_hjgr_arr[]">
	                                                                                    <option value="hj">hj</option>
	                                                                                    <option value="gr">gr</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td>
                                                                            		<select class="form-control" name="other_display[]">
	                                                                                    <option value="Y">Y</option>
	                                                                                    <option value="N">N</option>
	                                                                                </select>
                                                                            	</td>
                                                                            	<td></td>
                                                                            	<td></td>
                                                                            </tr>
																																				@endforeach

																																				</tbody>

																																				@else

																																				<tbody id="appendDevoteeLists">
																																						<tr id="no_data">
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

		                                                                        <label class="col-md-4">Transation No:</label>
		                                                                        <div class="col-md-8"></div><!-- end col-md-8 -->

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

																																				<div class="form-group">

						                                                           		<label class="col-md-12">Event</label>

						                                                           	</div><!-- end form-group -->

																																				<div class="form-group">

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
	                                                    		</div>

	                                                    		@else

	                                                    		<div class="form-group">
	                                                    			<input type="hidden" name="focusdevotee_id"
	                                                    				value="">
	                                                    			<input type="hidden" name="total_amount" id="total_amount" value="">
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

                                                                    <table class="table table-bordered order-column"
                                                                        id="receipt_history_table sample_1">
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
                                                                                <th>Print</th>
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
                                                                                    <td>{{ $receipt->xy_receipt }}</td>
                                                                                    <td>{{ \Carbon\Carbon::parse($receipt->trans_date)->format("d/m/Y") }}</td>
                                                                                    <td>{{ $receipt->trans_no }}</td>
                                                                                    <td>{{ $receipt->description }}</td>
                                                                                    <td>{{ $receipt->chinese_name }}</td>
                                                                                    <td>{{ $receipt->devotee_id }}</td>
                                                                                    <td>{{ $receipt->generaldonation_hjgr }}</td>
                                                                                    <td>{{ $receipt->amount }}</td>
                                                                                    <td>{{ $receipt->manualreceipt }}</td>
                                                                                    <td><a href="{{ URL::to('/staff/receipt/' . $receipt->receipt_id) }}">Print</a></td>
																																										<td><a href="{{ URL::to('/staff/receiptdetail/' . $receipt->receipt_id) }}">Detail</a></td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>

                                                                        @else



                                                                        @endif


                                                                    </table>

                                                                </div><!-- end form-group -->

                                                            </div><!-- end form-body -->

                                                    	</div><!-- end tab-pane -->

																											<div class="tab-pane" id="tab_samefamily">

																												<div class="form-body">
																													<form method="post" action="{{ URL::to('/staff/samefamily-setting') }}"
																												    class="form-horizontal form-bordered" id="samefamily_form">

																														{!! csrf_field() !!}

																														<div class="form-group">

																															<table class="table table-bordered" id="same_familycode_table">
																																<thead>
																								                    <tr>
																																			<th>#</th>
																																			<th>#</th>
																								                      <th>Chinese Name</th>
																								                      <th>Devotee#</th>
																																			<th>Member#</th>
																								                      <th>Address</th>
																								                      <th>Guiyi Name</th>
																								                      <th>Contact</th>
																								                      <th>Pay Till</th>
																								                      <th>Mailer</th>
																								                      <th>Last Trans</th>
																								                      <th>Family Code</th>
																								                    </tr>
																								                </thead>

																																@if(Session::has('setting_samefamily'))

																																@php

																						                        $setting_samefamily = Session::get('setting_samefamily');
																						                        $focus_devotee = Session::get('focus_devotee');
																																		$setting_generaldonation = Session::get('setting_generaldonation');
																						                    @endphp

																																<tbody id="has_session">

																																	@foreach($setting_samefamily as $devotee)
																							                    <tr>
																																		<td class="checkbox-col">
																																			<input type="checkbox" class="form-control same xiangyou_ciji_id" name="xiangyou_ciji_id[]"
																																			value="1" <?php if ($devotee->xiangyou_ciji_id == '1'){ ?>checked="checked"<?php }?>>
																																			<input type="hidden" class="form-control hidden_xiangyou_ciji_id" name="hidden_xiangyou_ciji_id[]"
																																			value="">
																																		</td>
																																		<td class="checkbox-col">
																																			<input type="checkbox" class="form-control same yuejuan_id" name="yuejuan_id[]"
																																			value="1" <?php if ($devotee->yuejuan_id == '1'){ ?>checked="checked"<?php }?>>
																																			<input type="hidden" class="form-control hidden_yuejuan_id" name="hidden_yuejuan_id[]"
																																			value="0">
																																		</td>
																							                      <td>
																																			@if($devotee->deceased_year != null)
																																			<span class="text-danger">{{ $devotee->chinese_name }}</span>
																																			@else
																																			<span>{{ $devotee->chinese_name }}</span>
																																			@endif
																																		</td>
																							                      <td>
																																			<input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
																																			@if($devotee->specialremarks_devotee_id == null)
																																			<span>{{ $devotee->devotee_id }}</span>
																																			@else
																																			<span class="text-danger">{{ $devotee->devotee_id }}</span>
																																			@endif
																																		</td>
																																		<td>
																																			@if(\Carbon\Carbon::parse($devotee->lasttransaction_at)->lt($date))
																																			<span style="color: #a5a5a5">{{ $devotee->member_id }}</span>
																																			@else
																																			<span>{{ $devotee->member_id }}</span>
																																			@endif
																																		</td>
																							                      <td>
																																			@if(isset($devotee->oversea_addr_in_chinese))
																																				{{ $devotee->oversea_addr_in_chinese }}
																																			@elseif(isset($devotee->address_unit1) && isset($devotee->address_unit2))
																																				{{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																			@else
																																				{{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																			@endif
																							                      </td>
																							                      <td>{{ $devotee->guiyi_name }}</td>
																							                      <td>{{ $devotee->contact }}</td>
																							                      <td>
																																			@if(isset($devotee->paytill_date) && \Carbon\Carbon::parse($devotee->paytill_date)->lt($now))
																																			<span class="text-danger">{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
																																			@elseif(isset($devotee->paytill_date))
																																			<span>{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
																																			@else
																																			<span>{{ $devotee->paytill_date }}</span>
																																			@endif
																																		</td>
																							                      <td>{{ $devotee->mailer }}</td>
																							                      <td>
																																			@if(isset($devotee->lasttransaction_at))
																																			{{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}
																																			@else
																																			{{ $devotee->lasttransaction_at }}
																																			@endif
																																		</td>
																							                      <td>{{ $devotee->familycode }}</td>
																							                    </tr>
																							                    @endforeach

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

																														@php $focus_devotee = Session::get('focus_devotee'); @endphp

																														<div class="form-group">
																															@if(count($focus_devotee) > 0)
																																<input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}">
																															@else
																																<input type="hidden" name="focusdevotee_id" value="">
																															@endif
																														</div><!-- end form-group -->

																														<div class="form-actions">
																																<button type="submit" class="btn blue" id="update_sameaddr_btn">Update</button>
																																<button type="reset" class="btn default" id="cancel_samefamily_btn">Cancel</button>
																														</div><!-- end form-actions -->

																														<div class="clearfix"></div><!-- end clearfix -->

																													</form>
																												</div><!-- end form-body -->

																											</div><!-- end tab-pane tab_samefamily -->

																											<div class="tab-pane" id="tab_relative_friends">
																												<div class="form-body">
																													<form method="post" action="{{ URL::to('/staff/differentfamily-setting') }}"
																												    class="form-horizontal form-bordered" id="differentfamily_form">

																														{!! csrf_field() !!}

																														<div class="form-group">

																															<table class="table table-bordered" id="different_familycode_table">
																																<thead>
																								                    <tr>
																																			<th>#</th>
																																			<th>#</th>
																																			<th>#</th>
																								                      <th>Chinese Name</th>
																								                      <th>Devotee#</th>
																																			<th>Member#</th>
																								                      <th>Address</th>
																								                      <th>Guiyi Name</th>
																								                      <th>Contact</th>
																								                      <th>Pay Till</th>
																								                      <th>Mailer</th>
																								                      <th>Last Trans</th>
																								                      <th>Family Code</th>
																								                    </tr>
																								                </thead>

																																<tbody id="appendDifferentFamilyCodeTable">

																																	@if(Session::has('setting_differentfamily'))

																																	@php

																																	    $setting_differentfamily = Session::get('setting_differentfamily');
																																	    $focus_devotee = Session::get('focus_devotee');
																																	    $setting_generaldonation = Session::get('setting_generaldonation');
																																	@endphp

																																  @foreach($setting_differentfamily as $devotee)
																																  <tr>
																																    <td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>
																																    <td class="checkbox-col">
																																      <input type="checkbox" class="form-control same xiangyou_ciji_id" name="xiangyou_ciji_id[]"
																																      value="1" <?php if ($devotee->xiangyou_ciji_id == '1'){ ?>checked="checked"<?php }?>>
																																      <input type="hidden" class="form-control hidden_xiangyou_ciji_id" name="hidden_xiangyou_ciji_id[]"
																																      value="">
																																    </td>
																																    <td class="checkbox-col">
																																      <input type="checkbox" class="form-control same yuejuan_id" name="yuejuan_id[]"
																																      value="1" <?php if ($devotee->yuejuan_id == '1'){ ?>checked="checked"<?php }?>>
																																      <input type="hidden" class="form-control hidden_yuejuan_id" name="hidden_yuejuan_id[]"
																																      value="0">
																																    </td>
																																    <td>
																																			@if($devotee->deceased_year != null)
																																			<span class="text-danger">{{ $devotee->chinese_name }}</span>
																																			@else
																																			<span>{{ $devotee->chinese_name }}</span>
																																			@endif
																																		</td>
																																    <td>
																																      <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
																																			@if($devotee->specialremarks_devotee_id == null)
																																			<span>{{ $devotee->devotee_id }}</span>
																																			@else
																																			<span class="text-danger">{{ $devotee->devotee_id }}</span>
																																			@endif
																																		</td>
																																    <td>
																																			@if(\Carbon\Carbon::parse($devotee->lasttransaction_at)->lt($date))
																																			<span style="color: #a5a5a5">{{ $devotee->member_id }}</span>
																																			@else
																																			<span>{{ $devotee->member_id }}</span>
																																			@endif
																																		</td>
																																    <td>
																																      @if(isset($devotee->oversea_addr_in_chinese))
																																        {{ $devotee->oversea_addr_in_chinese }}
																																      @elseif(isset($devotee->address_unit1) && isset($devotee->address_unit2))
																																        {{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																      @else
																																        {{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
																																      @endif
																																    </td>
																																    <td>{{ $devotee->guiyi_name }}</td>
																																    <td>{{ $devotee->contact }}</td>
																																    <td>
																																			@if(isset($devotee->paytill_date) && \Carbon\Carbon::parse($devotee->paytill_date)->lt($now))
																																			<span class="text-danger">{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
																																			@elseif(isset($devotee->paytill_date))
																																			<span>{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
																																			@else
																																			<span>{{ $devotee->paytill_date }}</span>
																																			@endif
																																		</td>
																																    <td>{{ $devotee->mailer }}</td>
																																    <td>
																																      @if(isset($devotee->lasttransaction_at))
																																      {{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}
																																      @else
																																      {{ $devotee->lasttransaction_at }}
																																      @endif
																																    </td>
																																    <td>{{ $devotee->familycode }}</td>
																																  </tr>
																																  @endforeach

																																	@else

																																	<tr>
																																		<td colspan="12">No Data</td>
																																	</tr>

																																	@endif

																																</tbody>
																															</table>
																														</div><!-- end form-group -->

																														<div class="col-md-4">

																															<div class="form-group">
																																<label class="col-md-5">Devotee ID</label>
																																<div class="col-md-7">
																																		<input type="text" class="form-control" id="search_devotee_id">
																																</div><!-- end col-md-7 -->
																															</div><!-- end form-group -->

																															<div class="form-group">
																																<label class="col-md-5">Member ID</label>
																																<div class="col-md-7">
																																		<input type="text" class="form-control" id="search_member_id">
																																</div><!-- end col-md-7 -->
																															</div><!-- end form-group -->

																															<div class="form-group">
																																<label class="col-md-5">Chinese Name</label>
																																<div class="col-md-7">
																																		<input type="text" class="form-control" id="search_chinese_name">
																																</div><!-- end col-md-7 -->
																															</div><!-- end form-group -->

																															<div class="form-actions">
																																	<button type="button" class="btn default" id="insert_devotee" style="padding: 7px; 15px;">Insert</button>
																																	<button type="button" class="btn default" id="search_detail_btn" style="padding: 7px; 15px;">Detail</button>
																															</div><!-- end form-actions -->

																														</div><!-- end col-md-4 -->

																														<div class="col-md-2">

																															<table class="table table-bordered" id="search_devotee_lists">
																																<tbody>
																																	<tr class="no-record">
																																		<td>No Result Record!</td>
																																	</tr>
																																</tbody>
																															</table>

																														</div><!-- end col-md-2 -->

																														<div class="col-md-6">

																															<div class="col-md-12">
																																<div class="form-group">
																																	<label class="col-md-3">Title</label>
																																	<div class="col-md-6">
																																			<input type="text" class="form-control" id="search_title">
																																	</div><!-- end col-md-6 -->
																																	<label class="col-md-3">Devotee ID</label>
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">Chinese Name</label>
																																	<div class="col-md-6">
																																			<input type="text" class="form-control" id="searchby_chinese_name">
																																	</div><!-- end col-md-6 -->
																																	<div class="col-md-3">
																																			<input type="text" class="form-control" id="searchby_devotee_id">
																																	</div><!-- end col-md-3 -->
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">English Name</label>
																																	<div class="col-md-6">
																																			<input type="text" class="form-control" id="search_english_name">
																																	</div><!-- end col-md-6 -->
																																	<label class="col-md-3">Member ID</label>
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">Guiyi Name</label>
																																	<div class="col-md-6">
																																			<input type="text" class="form-control" id="search_guiyi_name">
																																	</div><!-- end col-md-6 -->
																																	<div class="col-md-3">
																																			<input type="text" class="form-control" id="searchby_member_id">
																																	</div><!-- end col-md-3 -->
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">Contact#</label>
																																	<div class="col-md-6">
																																			<input type="text" class="form-control" id="search_contact">
																																	</div><!-- end col-md-8 -->
																																</div><!-- end form-group -->
																															</div><!-- end col-md-12 -->

																															<div class="col-md-12">

																																<div class="form-group">
																																	<label class="col-md-3">Addr - House no</label>
																																	<div class="col-md-3">
																																			<input type="text" class="form-control" id="search_address_houseno">
																																	</div><!-- end col-md-4 -->

																																	<label class="col-md-1">Unit</label>
																																	<div class="col-md-5">
																																			<input type="text" class="form-control" id="search_address_unit">
																																	</div><!-- end col-md-4 -->
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">Address - Street</label>
																																	<div class="col-md-9">
																																			<input type="text" class="form-control" id="search_address_street">
																																	</div><!-- end col-md-9 -->
																																</div><!-- end form-group -->

																																<div class="form-group">
																																	<label class="col-md-3">Address - Postal</label>
																																	<div class="col-md-3">
																																			<input type="text" class="form-control" id="search_address_postal">
																																	</div><!-- end col-md-3 -->

																																	<label class="col-md-2">Country</label>
																																	<div class="col-md-4">
																																			<input type="text" class="form-control" id="search_country">
																																	</div><!-- end col-md-3 -->
																																</div><!-- end form-group -->

																															</div><!-- end col-md-12 -->

																														</div><!-- end col-md-6 -->

																														<div class="clearfix">
																														</div><!-- end clearfix -->

																														@php $focus_devotee = Session::get('focus_devotee'); @endphp

																														<div class="form-group">
																															@if(count($focus_devotee) > 0)
																																<input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}">
																															@else
																																<input type="hidden" name="focusdevotee_id" value="">
																															@endif
																														</div><!-- end form-group -->

																														<div class="form-actions">
																																<button type="submit" class="btn blue" id="update_differentaddr_btn">Update</button>
																																<button type="button" class="btn default" id="cancel_differentaddr_btn">Cancel</button>
																														</div><!-- end form-actions -->

																														<div class="clearfix"></div><!-- end clearfix -->

																														</form>
																												</div><!-- end form-body -->
																											</div><!-- end tab-pane tab_relative_friends -->

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

	<script type="text/javascript">
		$(function() {

			$(".total").text(0);

			// Disabled Edit Tab
			$(".nav-tabs > li").click(function(){
					if($(this).hasClass("disabled"))
							return false;
			});

			// $('body').on('focus',".paid_till", function(){
	    //  		$(this).datepicker({ dateFormat: 'yy-mm-dd' });
	    // });

			$('body').on('keyup',".amount-col", function(){
	        var sum = 0;

			  $(".amount").each(function(){
			        sum += +$(this).val();
		  	});

				$(".total").text(sum);
				$("#total_amount").val(sum);

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

 		});
	</script>

@stop
