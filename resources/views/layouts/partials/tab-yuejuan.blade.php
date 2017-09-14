
  <form method="post" action="{{ URL::to('/staff/postyuejuandoantion') }}"
  class="form-horizontal form-bordered" id="yuejuan-donation-form" target="_blank">

  {!! csrf_field() !!}

  <div class="form-body">

    <div class="form-group">

      <h4>Same Family Code 同址善信</h4>

      <table class="table table-bordered" id="yuejuan_generaldonation_table">
          <thead>
              <tr>
                  <th>Chinese Name</th>
                  <th>Devotee#</th>
                  <th>Member#</th>
                  <th>Address</th>
                  <th>Guiyi Name</th>
                  <th width="190px">Amount</th>
                  <th width="80px">Paid Till</th>
                  <th>XYReceipt</th>
                  <th>Trans Date</th>
              </tr>
          </thead>

          @if(count($yuejuan_same_family) > 0)

          <tbody id="has_session">

              @php $i = 0; @endphp
              @foreach($yuejuan_same_family as $yj_family)

              <tr>
                <td>
                  @if($yj_family->deceased_year != null)
                  <span class="text-danger">{{ $yj_family->chinese_name }}</span>
                  @else
                  <span>{{ $yj_family->chinese_name }}</span>
                  @endif
                </td>
                <td>
                  @if($yj_family->specialremarks_devotee_id == null)
                  <span id="devotee">{{ $yj_family->devotee_id }}</span>
                  @else
                  <span class="text-danger" id="devotee">{{ $yj_family->devotee_id }}</span>
                  @endif
                  <input type="hidden" name="devotee_id[]" value="{{ $yj_family->devotee_id }}" id="devotee-hidden">
                </td>
                <td>
                  @if(\Carbon\Carbon::parse($yj_family->lasttransaction_at)->lt($date))
                  <span style="color: #a5a5a5">
                    <input type="hidden" name="member_id[]" value="{{ $yj_family->member_id }}" class="member_id" id="member-hidden">
                    {{ $yj_family->member_id }}
                  </span>
                  @else
                  <span>
                    <input type="hidden" name="member_id[]" value="{{ $yj_family->member_id }}" class="member_id" id="member-hidden">
                    {{ $yj_family->member_id }}
                  </span>
                  @endif
                </td>
                <td>
                  @if(isset($yj_family->oversea_addr_in_chinese))
                    {{ $yj_family->oversea_addr_in_chinese }}
                  @elseif(isset($yj_family->address_unit1) && isset($yj_family->address_unit2))
                    {{ $yj_family->address_houseno }}, #{{ $yj_family->address_unit1 }}-{{ $yj_family->address_unit2 }}, {{ $yj_family->address_street }}, {{ $yj_family->address_postal }}
                  @else
                    {{ $yj_family->address_houseno }}, {{ $yj_family->address_street }}, {{ $yj_family->address_postal }}
                  @endif
                </td>
                <td>{{ $yj_family->guiyi_name }}</td>
                <td width="80px" class="yuejuan-amount-col">
                  @if(count($samefamily_amount) > 0)
                    <select class="form-control yuejuan-amount" name="amount[]">
                        @if(count($samefamily_amount[$i]) > 0)

                        @if(!empty($yj_family->paytill_date))
                            <option value=""></option>
                          @for($j = 1; $j <= 10; $j++)
                            <option value="{{ $j }}">{{ $samefamily_amount[$i][$j] }}</option>
                          @endfor

                        @endif

                        @else

                        <option value=""></option>
                        <option value="0">10.00 -- Entrance Fee</option>

                        @endif
                    </select>
                  @endif
                </td>
                <td width="80px">
                  @if(isset($yj_family->paytill_date) && \Carbon\Carbon::parse($yj_family->paytill_date)->lt($now))
                  <span class="text-danger">{{ \Carbon\Carbon::parse($yj_family->paytill_date)->format("d/m/Y") }}</span>
                  @elseif(isset($yj_family->paytill_date))
                  <span>{{ \Carbon\Carbon::parse($yj_family->paytill_date)->format("d/m/Y") }}</span>
                  @else
                  <span>{{ $yj_family->paytill_date }}</span>
                  @endif
                </td>
                <td></td>
                <td></td>
              </tr>

              @php $i++; @endphp
              @endforeach

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

      <table class="table table-bordered" id="yuejuan_generaldonation_table2">
          <thead>
            <tr>
              <th>Chinese Name</th>
              <th>Devotee#</th>
              <th>Member#</th>
              <th>Address</th>
              <th>Guiyi Name</th>
              <th width="190px">Amount</th>
              <th width="80px">Paid Till</th>
              <th>XYReceipt</th>
              <th>Trans Date</th>
            </tr>
          </thead>

          @if(count($yuejuan_different_family) > 0)

          <tbody id="appendDevoteeLists">

            @php $i = 0; @endphp
            @foreach($yuejuan_different_family as $list)

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
                <input type="hidden" name="devotee_id[]" value="{{ $list->devotee_id }}">
                </td>
                <td>
                  @if(\Carbon\Carbon::parse($list->lasttransaction_at)->lt($date))
                  <span style="color: #a5a5a5">
                    <input type="hidden" name="member_id[]" value="{{ $list->member_id }}" class="member_id">
                    {{ $list->member_id }}
                  </span>
                  @else
                  <span>
                    <input type="hidden" name="member_id[]" value="{{ $list->member_id }}" class="member_id">
                    {{ $list->member_id }}
                  </span>
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
                <td class="yuejuan-amount-col">
                  @if(count($differentfamily_amount) > 0)
                    <select class="form-control yuejuan-amount" name="amount[]">
                        @if(count($differentfamily_amount[$i]) > 0)

                        @if(!empty($list->paytill_date))
                            <option value=""></option>
                          @for($j = 1; $j <= 10; $j++)
                            <option value="{{ $j }}">{{ $differentfamily_amount[$i][$j] }}</option>
                          @endfor

                        @endif

                        @else

                        <option value=""></option>
                        <option value="0">10.00 -- Entrance Fee</option>

                        @endif
                    </select>
                  @endif
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
                <td></td>
                <td></td>
              </tr>

              @php $i++; @endphp
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
        <h5><b>Total Amount: S$ <span class="yuejuan_total"></span></b></h5>
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
                  <input type="radio" name="yuejuan_mode_payment" value="cash" checked>
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Cheque
                  <input type="radio" name="yuejuan_mode_payment" value="cheque" class="form-control">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="cheque_no" value="" class="form-control input-small" id="yuejuan_cheque_no">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> NETS
                  <input type="radio" name="yuejuan_mode_payment" value="nets">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="nets_no" value=""
                    class="form-control input-small" id="yuejuan_nets_no">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline"> Manual Receipt
                  <input type="radio" name="yuejuan_mode_payment" value="receipt">
                  <span></span>
                  </label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="manualreceipt" value="" class="form-control input-small" id="yuejuan_manualreceipt">
                </div><!-- end col-md-6 -->

                <div class="clearfix"></div>

                <div class="col-md-6 payment">
                  <label class="mt-radio mt-radio-outline">Date of Receipts</label>
                </div><!-- end col-md-6 -->

                <div class="col-md-6">
                  <input type="text" name="receipt_at" class="form-control input-small"
                    data-provide="datepicker" data-date-format="dd/mm/yyyy" id="yuejuan_receipt_at">
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
      <input type="hidden" name="total_amount" id="yuejuan_total_amount" value="">
      <input type="hidden" name="membership_fee" id="membership_fee" value="{{ $membership[0]->membership_fee }}">
    </div>

    @else

    <div class="form-group">
      <input type="hidden" name="focusdevotee_id" value="">
      <input type="hidden" name="total_amount" id="yuejuan_total_amount" value="">
      <input type="hidden" name="membership_fee" id="membership_fee" value="{{ $membership[0]->membership_fee }}">
    </div>

    @endif

    <div class="form-group">

      <div class="col-md-12">

        <div class="form-actions">
          <button type="submit" class="btn blue" id="confirm_yuejuan_btn">Confirm
          </button>
          <button type="button" class="btn default">Cancel</button>
        </div><!-- end form-actions -->

      </div><!-- end col-md-12 -->

    </div><!-- end form-group -->

  </div><!-- end form-body -->

  <hr>

  <div class="form-body">

      <div class="form-group portlet-body">

          <table class="table table-bordered order-column" id="yuejuan_receipt_history_table">
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

              @if(Session::has('yuejuan_receipts'))

                  @php $receipts = Session::get('yuejuan_receipts'); @endphp

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

  </form>
