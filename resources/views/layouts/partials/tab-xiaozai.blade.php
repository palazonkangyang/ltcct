@php
  $xiaozai_same_focusdevotee = Session::get('xiaozai_same_focusdevotee');
  $xiaozai_same_family = Session::get('xiaozai_same_family');
  $xiaozai_different_family = Session::get('xiaozai_different_family');
@endphp

<div class="form-body">

  <form target="_blank" method="post" action="{{ URL::to('/fahui/xiaozai') }}"
    class="form-horizontal form-bordered" id="xiaozai-form">

    {!! csrf_field() !!}

    <div class="form-group">

      <h4>Same Family Code 同址善信</h4>

      <table class="table table-bordered" id="xiaozai_table">
        <thead>
          <tr>
            <th>#</th>
            <th width="120px">Chinese Name</th>
            <th width="80px">Devotee#</th>
            <th width="80px">RegisterBy</th>
            <th>GY</th>
            <th>OPS</th>
            <th>Type</th>
            <th width="200px">Item Description</th>
            <th width="100px">XZReceipt</th>
            <th width="80px">Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        @if(count($xiaozai_same_focusdevotee) > 0)

        <tbody id="has_session">

        @foreach($xiaozai_same_focusdevotee as $devotee)

        <tr>
          <td class="xiaozai-amount-col">
            <input type="checkbox" class="amount" name="xiaozai_amount[]" value="1">
            <input type="hidden" class="form-control hidden_xiaozai_amount" name="hidden_xiaozai_amount[]"
            value="">
            @if($devotee->type == 'sameaddress')
              <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'individual')
              <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'home')
              <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'company')
              <input type="checkbox" class="company_total_type" name="company_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'stall')
              <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'office')
              <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'car')
              <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
            @else
              <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
            @endif
          </td>
          <td>
            @if($devotee->deceased_year != null)
            <span class="text-danger">{{ $devotee->chinese_name }}</span>
            @else
            <span>{{ $devotee->chinese_name }}</span>
            @endif
          </td>
          <td>
            @if($devotee->specialremarks_devotee_id == null)
            <span id="devotee">{{ $devotee->devotee_id }}</span>
            @else
            <span class="text-danger" id="devotee">{{ $devotee->devotee_id }}</span>
            @endif
            <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
          </td>
          <td></td>
          <td>{{ $devotee->guiyi_name }}</td>
          <td>{{ $devotee->ops }}</td>
          <td class="xiaozai-type-col">
            @if($devotee->type == 'sameaddress')
            合家
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'individual')
            个人
            <input type="hidden" class="form-control" name="amount[]" value="20">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'home')
            宅址
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'company')
            公司
            <input type="hidden" class="form-control" name="amount[]" value="100">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'stall')
            小贩
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'office')
            办公址
            <input type="hidden" class="form-control" name="amount[]" value="20">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'car')
            车辆
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @else
            船只
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @endif
          </td>
          <td>{{ $devotee->item_description }}</td>
          <td width="80px">{{ $devotee->receipt_no }}</td>
          <td></td>
          <td>
            @if(isset($devotee->lasttransaction_at))
            <span>{{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}</span>
            @else
            <span>{{ $devotee->lasttransaction_at }}</span>
            @endif
          </td>
        </tr>

        @endforeach

        @if(count($xiaozai_same_family) > 0)

        @foreach($xiaozai_same_family as $devotee)

        <tr>
          <td class="xiaozai-amount-col">
            <input type="checkbox" class="amount" name="xiaozai_amount[]" value="1">
            <input type="hidden" class="form-control hidden_xiaozai_amount" name="hidden_xiaozai_amount[]"
            value="">
            @if($devotee->type == 'sameaddress')
              <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'individual')
              <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'home')
              <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'company')
              <input type="checkbox" class="company_total_type" name="company_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'stall')
            @elseif($devotee->type == 'office')
              <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
            @elseif($devotee->type == 'car')
              <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
            @else
              <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
            @endif
          </td>
          <td>
            @if($devotee->deceased_year != null)
            <span class="text-danger">{{ $devotee->chinese_name }}</span>
            @else
            <span>{{ $devotee->chinese_name }}</span>
            @endif
          </td>
          <td>
            @if($devotee->specialremarks_devotee_id == null)
            <span id="devotee">{{ $devotee->devotee_id }}</span>
            @else
            <span class="text-danger" id="devotee">{{ $devotee->devotee_id }}</span>
            @endif
            <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
          </td>
          <td></td>
          <td>{{ $devotee->guiyi_name }}</td>
          <td>{{ $devotee->ops }}</td>
          <td class="xiaozai-type-col">
            @if($devotee->type == 'sameaddress')
            合家
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'individual')
            个人
            <input type="hidden" class="form-control" name="amount[]" value="20">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'home')
            宅址
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'company')
            公司
            <input type="hidden" class="form-control" name="amount[]" value="100">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'stall')
            小贩
            <input type="hidden" class="form-control" name="amount[]" value="">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'office')
            办公址
            <input type="hidden" class="form-control" name="amount[]" value="20">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @elseif($devotee->type == 'car')
            车辆
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @else
            船只
            <input type="hidden" class="form-control" name="amount[]" value="30">
            <input type="hidden" name="type[]" value="{{ $devotee->type }}">
            @endif
          </td>
          <td>{{ $devotee->item_description }}</td>
          <td width="80px">{{ $devotee->receipt_no }}</td>
          <td></td>
          <td>
            @if(isset($devotee->lasttransaction_at))
            <span>{{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}</span>
            @else
            <span>{{ $devotee->lasttransaction_at }}</span>
            @endif
          </td>
        </tr>

        @endforeach

        @endif



      </tbody>

      @else

      <tbody id="no_session">
        <tr>
          <td colspan="10">No Result Found</td>
        </tr>
      </tbody>

      @endif

    </table>

    </div><!-- end form-group -->

    <div class="form-group">
      <h4>Relatives and friends 亲戚朋友</h4>
    </div><!-- end form-group -->

    <div class="form-group">

      <table class="table table-bordered" id="xiaozai_table2">
        <thead>
          <tr>
            <th>#</th>
            <th width="120px">Chinese Name</th>
            <th width="80px">Devotee#</th>
            <th width="80px">RegisterBy</th>
            <th>GY</th>
            <th>OPS</th>
            <th>Type</th>
            <th width="170px">Item Description</th>
            <th width="100px">XZReceipt</th>
            <th width="80px">Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        @if(count($xiaozai_different_family) > 0)

        <tbody id="appendDevoteeLists">

          @foreach($xiaozai_different_family as $list)

            <tr>
              <td class="xiaozai-amount-col">
                <input type="checkbox" class="amount" name="xiaozai_amount[]" value="1">
                <input type="hidden" class="form-control hidden_xiaozai_amount" name="hidden_xiaozai_amount[]"
                value="">
                @if($list->type == 'sameaddress')
                  <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
                @elseif($list->type == 'individual')
                  <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
                @elseif($list->type == 'home')
                  <input type="checkbox" class="address_total_type" name="address_total_type[]" value="" style="display:none">
                @elseif($list->type == 'company')
                  <input type="checkbox" class="company_total_type" name="company_total_type[]" value="" style="display:none">
                @elseif($list->type == 'stall')
                @elseif($list->type == 'office')
                  <input type="checkbox" class="individual_office_total_type" name="individual_office_total_type[]" value="" style="display:none">
                @elseif($list->type == 'car')
                  <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
                @else
                  <input type="checkbox" class="vehicle_total_type" name="vehicle_total_type[]" value="" style="display:none">
                @endif
              </td>
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
              <input type="hidden" name="devotee_id[]" value="{{ $list->devotee_id }}">
              </td>
              <td></td>
              <td>{{ $list->guiyi_name }}</td>
              <td>{{ $list->ops }}</td>
              <td>
                @if($list->type == 'sameaddress')
                合家
                <input type="hidden" class="form-control" name="amount[]" value="30">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'individual')
                个人
                <input type="hidden" class="form-control" name="amount[]" value="20">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'home')
                宅址
                <input type="hidden" class="form-control" name="amount[]" value="30">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'company')
                公司
                <input type="hidden" class="form-control" name="amount[]" value="100">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'stall')
                小贩
                <input type="hidden" class="form-control" name="amount[]" value="">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'office')
                办公址
                <input type="hidden" class="form-control" name="amount[]" value="20">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @elseif($list->type == 'car')
                车辆
                <input type="hidden" class="form-control" name="amount[]" value="30">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @else
                船只
                <input type="hidden" class="form-control" name="amount[]" value="30">
                <input type="hidden" name="type[]" value="{{ $list->type }}">
                @endif
              </td>
              <td>{{ $list->item_description }}</td>
              <td>{{ $list->receipt_no }}</td>
              <td></td>
              <td>
                @if(isset($devotee->lasttransaction_at))
                <span>{{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}</span>
                @else
                <span>{{ $devotee->lasttransaction_at }}</span>
                @endif
              </td>
            </tr>

          @endforeach

        </tbody>

        @else

        <tbody id="no_session">
          <tr>
            <td colspan="11">No Result Found</td>
          </tr>
        </tbody>

        @endif

      </table>

    </div><!-- end form-group -->

    <hr>

    <div class="form-body">

      <div class="col-md-6">

        <div class="form-group" id="transaction_wrap" style="display: none;">
          <label class="col-md-4" style="font-weight:bold">Transaction No (交易):</label>
          <label class="col-md-8" id="trans_info"></label><!-- end col-md-8 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-12" style="font-weight: bold;">Mode of Payment</label>
        </div><!-- end form-group -->

        <div class="form-group">

            <div class="col-md-12">
              <div class="mt-radio-list">

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Cash
                      <input type="radio" name="mode_payment"
                        value="cash" checked>
                      <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
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

                <div class="col-md-6 payment">
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

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Manual Receipt
                      <input type="radio" name="mode_payment"
                        value="receipt">
                      <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="manualreceipt" value=""
                    class="form-control input-small" id="manualreceipt">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
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

        <div class="form-group">

          <label class="col-md-12" style="font-weight: bold">Type of Receipt Printing</label>

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

      <div class="col-md-6">

        <div class="form-group">
          <label class="col-md-12"><h5 style="font-weight: bold">Price Summary</h5></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-12">
            <p style="font-size: 15px;"><span class="address_total">0</span> 合家 / 宅址 @ S$ 30.00	=	S$ <span class="address_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="individual_office_total">0</span> 个人 / 办公址 @ S$ 20.00	=	S$ <span class="individual_office_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="company_total">0</span> 公司 @ S$ 100.00	=	S$ <span class="company_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="vehicle_total">0</span> 车辆 @ S$ 30.00	=	S$ <span class="vehicle_total_amount">0</span></p>
          </label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-12">
            <h4 style="font-weight: bold" class="text-danger">
              Total Payable Amount S$ <span class="total_payable">0</span>
            </h4>
          </label>
        </div><!-- end form-group -->

        <div class="form-group">
          @if(Session::has('focus_devotee'))
          <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}">
          @else
          <input type="hidden" name="focusdevotee_id" value="">
          @endif
          <input type="hidden" name="total_amount" id="total_amount" value="0">
        </div>

      </div><!-- end col-md-6 -->

      <div class="col-md-12">

        <div class="form-group">

          <div class="form-actions">
            <button type="submit" class="btn blue" id="confirm_xiaozai_btn">Confirm
            </button>
            <button type="button" class="btn default">Cancel</button>
          </div><!-- end form-actions -->

          <div id="dialog-box" title="System Alert" style="display:none;">
            Do you want to submit this form?
          </div><!-- end dialog-box -->

        </div><!-- end form-group -->

      </div><!-- end col-md-12 -->

    </div><!-- end form-body -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->

<hr>

<div class="form-body">

  <div class="form-group portlet-body">

    <table class="table table-bordered order-column" id="xiaozai_receipt_history_table">
      <thead>
        <tr>
          <th>XZReceipt</th>
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

      @if(Session::has('xiaozai_receipts'))

        @php
          $receipts = Session::get('xiaozai_receipts');
        @endphp

        <tbody>
          @foreach($receipts as $receipt)

          <tr>
            @if(isset($receipt->cancelled_date))
              <td class="text-danger">{{ $receipt->receipt_no }}</td>
            @else
              <td>{{ $receipt->receipt_no }}</td>
            @endif
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
              <td><a href="#tab_xiaozai_transactiondetail" data-toggle="tab" id="{{ $receipt->trans_no }}" class="xiaozai-receipt-id">Detail</a></td>
            </tr>
            @endforeach
          </tbody>

      @else

      <tbody>
        <tr>
          <td colspan="10"></td>
        </tr>
      </tbody>

      @endif
    </table>

  </div><!-- end form-group -->

</div><!-- end form-body -->
