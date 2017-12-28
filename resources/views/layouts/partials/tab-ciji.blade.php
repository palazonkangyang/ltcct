
  <form method="post" action="{{ URL::to('/staff/postcijidoantion') }}"
  class="form-horizontal form-bordered" id="ciji-donation-form" target="_blank">

  {!! csrf_field() !!}

  <div class="form-body">

      <div class="form-group">

        <h4>Same Family Code 同址善信</h4>

        <table class="table table-bordered" id="ciji_generaldonation_table">
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

            @if(count($ciji_same_family) > 0 || count($ciji_same_focusdevotee) > 0)

            <tbody id="has_session">
                @if(count($ciji_same_focusdevotee) > 0)

                <tr>
                  <td>
                    @if($ciji_same_focusdevotee[0]->deceased_year != null)
                    <span class="text-danger">{{ $ciji_same_focusdevotee[0]->chinese_name }}</span>
                    @else
                    <span>{{ $ciji_same_focusdevotee[0]->chinese_name }}</span>
                    @endif
                  </td>
                  <td>
                    @if($ciji_same_focusdevotee[0]->specialremarks_devotee_id == null)
                    <span id="devotee">{{ $ciji_same_focusdevotee[0]->devotee_id }}</span>
                    @else
                    <span class="text-danger" id="devotee">{{ $ciji_same_focusdevotee[0]->devotee_id }}</span>
                    @endif
                    <input type="hidden" name="devotee_id[]" value="{{ $ciji_same_focusdevotee[0]->devotee_id }}">
                  </td>
                  <td>
                    @if(\Carbon\Carbon::parse($ciji_same_focusdevotee[0]->lasttransaction_at)->lt($date))
                    <span style="color: #a5a5a5">{{ $ciji_same_focusdevotee[0]->member }}</span>
                    @else
                    <span>{{ $ciji_same_focusdevotee[0]->member }}</span>
                    @endif
                  </td>
                  <td>
                    @if(isset($ciji_same_focusdevotee[0]->oversea_addr_in_chinese))
                      {{ $ciji_same_focusdevotee[0]->oversea_addr_in_chinese }}
                    @elseif(isset($ciji_same_focusdevotee[0]->address_unit1) && isset($ciji_same_focusdevotee[0]->address_unit2))
                      {{ $ciji_same_focusdevotee[0]->address_houseno }}, #{{ $ciji_same_focusdevotee[0]->address_unit1 }}-{{ $ciji_same_focusdevotee[0]->address_unit2 }}, {{ $ciji_same_focusdevotee[0]->address_street }}, {{ $ciji_same_focusdevotee[0]->address_postal }}
                    @else
                      {{ $ciji_same_focusdevotee[0]->address_houseno }}, {{ $ciji_same_focusdevotee[0]->address_street }}, {{ $ciji_same_focusdevotee[0]->address_postal }}
                    @endif
                  </td>
                  <td>{{ $ciji_same_focusdevotee[0]->guiyi_name }}</td>
                  <td width="80px" class="ciji-amount-col">
                    <input type="text" class="form-control ciji-amount" name="amount[]">
                  </td>
                  <td width="80px">
                    @if(isset($xs_family->paytill_date) && \Carbon\Carbon::parse($ciji_same_focusdevotee[0]->paytill_date)->lt($now))
                    <span class="text-danger">{{ \Carbon\Carbon::parse($ciji_same_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
                    @elseif(isset($ciji_same_focusdevotee[0]->paytill_date))
                    <span>{{ \Carbon\Carbon::parse($ciji_same_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
                    @else
                    <span>{{ $ciji_same_focusdevotee[0]->paytill_date }}</span>
                    @endif
                  </td>
                  <td width="100px">
                    <select class="form-control ciji-hjgr" name="hjgr_arr[]">
                      <option value="hj">合家</option>
                      <option value="gr">个人</option>
                    </select>
                  </td>
                  <td width="80px">
                    <select class="form-control ciji-display" name="display[]">
                      <option value="N">N</option>
                      <option value="Y">Y</option>
                    </select>
                    <input type="hidden" name="display[]" class="ciji-display-hidden" value="">
                  </td>
                  <td>{{ $ciji_same_focusdevotee[0]->xyreceipt }}</td>
                  <td>
                    @if(isset($ciji_same_focusdevotee[0]->lasttransaction_at))
                    <span>{{ \Carbon\Carbon::parse($ciji_same_focusdevotee[0]->lasttransaction_at)->format("d/m/Y") }}</span>
                    @else
                    <span>{{ $ciji_same_focusdevotee[0]->lasttransaction_at }}</span>
                    @endif
                  </td>
                </tr>

                @endif

                @if(count($ciji_same_family) > 0)

                @foreach($ciji_same_family as $xs_family)

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
                    <span style="color: #a5a5a5">{{ $xs_family->member }}</span>
                    @else
                    <span>{{ $xs_family->member }}</span>
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
                  <td width="80px" class="ciji-amount-col">
                    <input type="text" class="form-control ciji-amount" name="amount[]">
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
                    <select class="form-control ciji-hjgr" name="hjgr_arr[]">
                      <option value="hj">合家</option>
                      <option value="gr">个人</option>
                    </select>
                  </td>
                  <td width="80px">
                    <select class="form-control ciji-display" name="display[]">
                      <option value="N">N</option>
                      <option value="Y">Y</option>
                    </select>
                    <input type="hidden" name="display[]" class="ciji-display-hidden" value="">
                  </td>
                  <td>{{ $xs_family->xyreceipt }}</td>
                  <td>
                    @if(isset($xs_family->lasttransaction_at))
                    <span>{{ \Carbon\Carbon::parse($xs_family->lasttransaction_at)->format("d/m/Y") }}</span>
                    @else
                    <span>{{ $xs_family->lasttransaction_at }}</span>
                    @endif
                  </td>
                </tr>

                @endforeach

                @endif

            </tbody>

            @else

            <tbody id="no_session">
              <tr>
                <td colspan="12">No Result Found</td>
              </tr>
            </tbody>

            @endif

        </table>

      </div><!-- end form-group -->

      <div class="form-group">
        <h4>Relatives and friends 亲戚朋友</h4>
      </div><!-- end form-group -->

      <div class="form-group">

        <table class="table table-bordered" id="ciji_generaldonation_table2">
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

            @if(count($ciji_different_family) > 0)

            <tbody id="appendDevoteeLists">

              @foreach($ciji_different_family as $list)

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
                    <span style="color: #a5a5a5">{{ $list->member }}</span>
                    @else
                    <span>{{ $list->member }}</span>
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
                  <td class="ciji-amount-col">
                    <input type="text" class="form-control ciji-amount other_ciji_amount" name="other_amount[]">
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
                    <select class="form-control ciji-hjgr" name="other_hjgr_arr[]">
                      <option value="hj">合家</option>
                      <option value="gr">个人</option>
                    </select>
                  </td>
                  <td>
                    <select class="form-control ciji-display">
                      <option value="N">N</option>
                      <option value="Y">Y</option>
                    </select>
                    <input type="hidden" name="other_display[]" class="ciji-display-hidden" value="">
                  </td>
                  <td>{{ $list->xyreceipt }}</td>
                  <td>
                    @if(isset($list->lasttransaction_at))
                    <span>{{ \Carbon\Carbon::parse($list->lasttransaction_at)->format("d/m/Y") }}</span>
                    @else
                    <span>{{ $list->lasttransaction_at }}</span>
                    @endif
                  </td>
                </tr>
              @endforeach

            </tbody>

            @else

            <tbody id="appendDevoteeLists">
                <tr id="no_data">
                    <td colspan="12">No Result Found</td>
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
        <h5><b>Total Amount: S$ <span class="ciji_total"></span></b></h5>
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

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Cash
                  <input type="radio" name="ciji_mode_payment" value="cash" checked>
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Cheque
                  <input type="radio" name="ciji_mode_payment" value="cheque" class="form-control">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="cheque_no" value="" class="form-control input-small" id="ciji_cheque_no">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> NETS
                  <input type="radio" name="ciji_mode_payment" value="nets">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="nets_no" value=""
                    class="form-control input-small" id="ciji_nets_no">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Manual Receipt
                  <input type="radio" name="ciji_mode_payment" value="receipt">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="manualreceipt" value="" class="form-control input-small" id="ciji_manualreceipt">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline">Date of Receipt</label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="receipt_at" class="form-control input-small" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-end-date="0d" id="ciji_receipt_at">

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
      <input type="hidden" name="total_amount" id="ciji_total_amount" value="">
      <input type="hidden" name="minimum_amount" id="minimum_amount" value="{{ $amount[0]->minimum_amount }}">
    </div>

    @else

    <div class="form-group">
      <input type="hidden" name="focusdevotee_id" value="">
      <input type="hidden" name="total_amount" id="ciji_total_amount" value="">
      <input type="hidden" name="minimum_amount" id="minimum_amount" value="{{ $amount[0]->minimum_amount }}">
    </div>

    @endif

    <div class="form-group">

      <div class="col-md-12">

        <div class="form-actions">
          <button type="submit" class="btn blue" id="confirm_ciji_btn">Confirm
          </button>
          <button type="button" class="btn default">Cancel</button>
        </div><!-- end form-actions -->

      </div><!-- end col-md-12 -->

    </div><!-- end form-group -->

  </div><!-- end form-body -->

  <hr>

  <div class="form-body">

      <div class="form-group portlet-body">

          <table class="table table-bordered order-column" id="ciji_receipt_history_table">
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

              @if(Session::has('ciji_receipts'))

                  @php $receipts = Session::get('ciji_receipts'); @endphp

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
                          <td><a href="#tab_transactiondetail" data-toggle="tab" id="{{ $receipt->trans_no }}" class="receipt-id">Detail</a></td>
                      </tr>
                      @endforeach
                  </tbody>

              @else

              @endif


          </table>

      </div><!-- end form-group -->

  </div><!-- end form-body -->

  </form>
