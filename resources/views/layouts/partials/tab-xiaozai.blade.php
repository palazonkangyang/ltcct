@php
  $same_family_code = Session::get('same_family_code')['xiaozai'];
  $relative_and_friends = Session::get('relative_and_friends')['xiaozai'];
@endphp

<div class="form-body">

  <form target="_blank" method="post" action="{{ URL::to('/transaction/create') }}"
    class="form-horizontal form-bordered" id="xiaozai-form">

    {!! csrf_field() !!}
    {{ Form::hidden('mod_id',5)}}
    {{ Form::hidden('trans_no_to_cancel','') }}
    <div class="form-group">

      <h4>Same Family Code 同址善信</h4>

      <table class="table table-bordered" id="xiaozai_table">
        <thead>
          <tr>
            <th>#</th>
            <th width="120px">Chinese Name</th>
            <th width="80px">Devotee#</th>
            <th width="80px">Register By</th>
            <th>GY</th>
            <th>OPS</th>
            <th>Type</th>
            <th width="200px">Item Description</th>
            <th width="100px">XZ Receipt</th>
            <th width="80px">Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        @if(Session::has('same_family_code'))

        <tbody id="has_session">
        @if(count($same_family_code) > 0)
        @foreach($same_family_code as $devotee)
          @if($devotee->is_checked == 1)
            <tr>
              <td class="xiaozai-amount-col">
                <input type="checkbox" class="amount checkbox-multi-select-module-xiaozai-tab-xiaozai-section-sfc" name="xiaozai_amount[]" value="1">
                <input type="hidden" class="form-control is_checked_list" name="is_checked_list[]" value="">
                @if($devotee->type == 'base_home')
                  @if($devotee->hjgr == 'hj')
                    <input type="checkbox" class="hj" name="hj[]" value="" style="display:none">
                  @elseif($devotee->hjgr == 'gr')
                    <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                  @endif

                @elseif($devotee->type == 'home')
                  @if($devotee->hjgr == 'hj')
                    <input type="checkbox" class="hj" name="hj[]" value="" style="display:none">
                  @elseif($devotee->hjgr == 'gr')
                    <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                @else
                @endif

                @elseif($devotee->type == 'company')
                  <input type="checkbox" class="company" name="company[]" value="" style="display:none">
                @elseif($devotee->type == 'stall')
                  <input type="checkbox" class="stall" name="stall[]" value="" style="display:none">
                @elseif($devotee->type == 'office')
                  <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                @elseif($devotee->type == 'car')
                  <input type="checkbox" class="car" name="car[]" value="" style="display:none">
                @elseif($devotee->type == 'ship')
                  <input type="checkbox" class="ship" name="ship[]" value="" style="display:none">
                @else

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
                {{ Form::hidden('type[]',$devotee->type)}}
                @if($devotee->type == 'base_home')
                  @if($devotee->hjgr == 'hj')
                    合家
                    {{ Form::hidden('type_chinese_name_list[]','合家')}}
                    {{ Form::hidden('amount[]',$xiaozai_price_hj)}}
                  @elseif($devotee->hjgr == 'gr')
                    个人
                    {{ Form::hidden('type_chinese_name_list[]','个人')}}
                    {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                  @else

                  @endif

                @elseif($devotee->type == 'home')
                @if($devotee->hjgr == 'hj')
                  合家
                  {{ Form::hidden('type_chinese_name_list[]','合家')}}
                  {{ Form::hidden('amount[]',$xiaozai_price_hj)}}
                @elseif($devotee->hjgr == 'gr')
                  个人
                  {{ Form::hidden('type_chinese_name_list[]','个人')}}
                  {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                @else
                @endif
                @elseif($devotee->type == 'company')
                公司
                {{ Form::hidden('type_chinese_name_list[]','公司')}}
                {{ Form::hidden('amount[]',$xiaozai_price_company)}}
                @elseif($devotee->type == 'stall')
                小贩
                {{ Form::hidden('type_chinese_name_list[]','小贩')}}
                {{ Form::hidden('amount[]',$xiaozai_price_stall)}}
                @elseif($devotee->type == 'office')
                个人
                {{ Form::hidden('type_chinese_name_list[]','个人')}}
                {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                @elseif($devotee->type == 'car')
                车辆
                {{ Form::hidden('type_chinese_name_list[]','车辆')}}
                {{ Form::hidden('amount[]',$xiaozai_price_car)}}
                @elseif($devotee->type == 'ship')
                船只
                {{ Form::hidden('type_chinese_name_list[]','船只')}}
                {{ Form::hidden('amount[]',$xiaozai_price_ship)}}
                @else
                {{ Form::hidden('type_chinese_name_list[]','')}}
                {{ Form::hidden('amount[]',0)}}
                @endif
              </td>
              <td>{{ $devotee->item_description }}</td>
              {{ Form::hidden('item_description_list[]',$devotee->item_description)}}
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

          @endif
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
            <th width="100px">XZ Receipt</th>
            <th width="80px">Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        @if(count($relative_and_friends) > 0)

        <tbody id="appendDevoteeLists">

          @foreach($relative_and_friends as $devotee)
            @if($devotee->is_checked == 1)
            <tr>
              <td class="xiaozai-amount-col">
                <input type="checkbox" class="amount checkbox-multi-select-module-xiaozai-tab-xiaozai-section-raf" name="xiaozai_amount[]" value="1">
                <input type="hidden" class="form-control is_checked_list" name="is_checked_list[]"
                value="">
                @if($devotee->type == 'base_home')
                  @if($devotee->hjgr == 'hj')
                    <input type="checkbox" class="hj" name="hj[]" value="" style="display:none">
                  @elseif($devotee->hjgr == 'gr')
                    <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                  @else

                  @endif

                @elseif($devotee->type == 'home')
                  @if($devotee->hjgr == 'hj')
                    <input type="checkbox" class="hj" name="hj[]" value="" style="display:none">
                  @elseif($devotee->hjgr == 'gr')
                    <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                @else
                @endif

                @elseif($devotee->type == 'company')
                  <input type="checkbox" class="company" name="company[]" value="" style="display:none">
                @elseif($devotee->type == 'stall')
                  <input type="checkbox" class="stall" name="stall[]" value="" style="display:none">
                @elseif($devotee->type == 'office')
                  <input type="checkbox" class="gr" name="gr[]" value="" style="display:none">
                @elseif($devotee->type == 'car')
                  <input type="checkbox" class="car" name="car[]" value="" style="display:none">
                @elseif($devotee->type == 'ship')
                  <input type="checkbox" class="ship" name="ship[]" value="" style="display:none">
                @else

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
              <td>
                {{ Form::hidden('type[]',$devotee->type)}}
                @if($devotee->type == 'base_home')
                  @if($devotee->hjgr == 'hj')
                    合家
                    {{ Form::hidden('type_chinese_name_list[]','合家')}}
                    {{ Form::hidden('amount[]',$xiaozai_price_hj)}}
                  @elseif($devotee->hjgr == 'gr')
                    个人
                    {{ Form::hidden('type_chinese_name_list[]','个人')}}
                    {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                  @else
                  @endif
                @elseif($devotee->type == 'home')
                @if($devotee->hjgr == 'hj')
                  合家
                  {{ Form::hidden('type_chinese_name_list[]','合家')}}
                  {{ Form::hidden('amount[]',$xiaozai_price_hj)}}
                @elseif($devotee->hjgr == 'gr')
                  个人
                  {{ Form::hidden('type_chinese_name_list[]','个人')}}
                  {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                @else
                @endif
                @elseif($devotee->type == 'company')
                公司
                {{ Form::hidden('type_chinese_name_list[]','公司')}}
                {{ Form::hidden('amount[]',$xiaozai_price_company)}}
                @elseif($devotee->type == 'stall')
                小贩
                {{ Form::hidden('type_chinese_name_list[]','小贩')}}
                {{ Form::hidden('amount[]',$xiaozai_price_stall)}}
                @elseif($devotee->type == 'office')
                个人
                {{ Form::hidden('type_chinese_name_list[]','个人')}}
                {{ Form::hidden('amount[]',$xiaozai_price_gr)}}
                @elseif($devotee->type == 'car')
                车辆
                {{ Form::hidden('type_chinese_name_list[]','车辆')}}
                {{ Form::hidden('amount[]',$xiaozai_price_car)}}
                @elseif($devotee->type == 'ship')
                船只
                {{ Form::hidden('type_chinese_name_list[]','船只')}}
                {{ Form::hidden('amount[]',$xiaozai_price_ship)}}
                @else
                {{ Form::hidden('type_chinese_name_list[]','')}}
                {{ Form::hidden('amount[]',0)}}
                @endif
              </td>
              <td>{{ $devotee->item_description }}</td>
              {{ Form::hidden('item_description_list[]',$devotee->item_description)}}
              <td>{{ $devotee->receipt_no }}</td>
              <td></td>
              <td>
                @if(isset($devotee->lasttransaction_at))
                <span>{{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}</span>
                @else
                <span>{{ $devotee->lasttransaction_at }}</span>
                @endif
              </td>
            </tr>
            @endif
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
                    Date of Receipt
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="receipt_at" class="form-control input-small" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-end-date="0d" id="receipt_at">
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
                <input type="radio" name="receipt_printing_type" value="one_receipt_printing_for_same_address" checked>
                <span></span>
              </label>

              <label class="mt-radio mt-radio-outline"> Individual Receipt Printing
                <input type="radio" name="receipt_printing_type" value="individual_receipt_printing">
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
            <p style="font-size: 15px;"><span class="hj_total">0</span> 合家 @ S$ <span id="xiaozai_price_hj">{{ $xiaozai_price_hj }}</span>	=	S$ <span class="hj_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="gr_total">0</span> 个人 @ S$ <span id="xiaozai_price_gr">{{ $xiaozai_price_gr }}</span>	=	S$ <span class="gr_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="company_total">0</span> 公司 @ S$ <span id="xiaozai_price_company">{{ $xiaozai_price_company }}</span>	=	S$ <span class="company_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="stall_total">0</span> 小贩 @ S$ <span id="xiaozai_price_stall">{{ $xiaozai_price_stall }}</span>	=	S$ <span class="stall_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="car_total">0</span> 车辆 @ S$ <span id="xiaozai_price_car">{{ $xiaozai_price_car }}</span>	=	S$ <span class="car_total_amount">0</span></p>
            <p style="font-size: 15px;"><span class="ship_total">0</span> 船只 @ S$ <span id="xiaozai_price_ship">{{ $xiaozai_price_ship }}</span>	=	S$ <span class="ship_total_amount">0</span></p>
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
          <th>XZ Receipt</th>
          <th>Trans Date</th>
          <th>Transaction</th>
          <th>Description</th>
          <th>Paid By</th>
          <th>Devotee ID</th>
          <th>Amount</th>
          <th>Manual Receipt</th>
          <th>View Details</th>
        </tr>
      </thead>

      @if(Session::has('transaction.xiaozai'))

        @php
          $transactions = Session::get('transaction.xiaozai');
        @endphp

        <tbody>
          @foreach($transactions as $transaction)
          <tr>
            <td>
              @if($transaction->status == 'cancelled')
              <span style="color:red;">{{ $transaction->receipt }}</span>
              @elseif($transaction->status == NULL)
              {{ $transaction->receipt }}
              @endif
            </td>
            <td>{{ $transaction->trans_at }}</td>
            <td>{{ $transaction->trans_no }}</td>
            <td>{{ $transaction->description }}</td>
            <td>{{ $transaction->paid_by }}</td>
            <td>{{ $transaction->focusdevotee_id }}</td>
            <td>{{ $transaction->total_amount }}</td>
            <td>{{ $transaction->manualreceipt }}</td>
            <td><a href="#tab_xiaozai_transactiondetail" data-toggle="tab" id="{{ $transaction->trans_no }}" class="xiaozai-receipt-id">Detail</a></td>
          </tr>
          @endforeach
        </tbody>

      @else

      <tbody>
        <tr>
          <td colspan="10">No Result Found!</td>
        </tr>
      </tbody>

      @endif
    </table>

  </div><!-- end form-group -->

</div><!-- end form-body -->
