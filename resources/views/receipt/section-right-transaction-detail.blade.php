<div class="receipt-info">
  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Receipt Date (日期)</div><!-- end label-left -->
    <div class="rightlabel-right">{{ $transaction->trans_at }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Description (项目)</div><!-- end label-left -->
    <div class="rightlabel-right">{{ $module->chinese_name }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->
</div><!-- end receipt-info -->

<hr>

<div class="receipt-info">
  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Next Event (下个法会)</div><!-- end label-left -->
    <div class="rightlabel-right">{{ $next_event->event }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Event Date (法会日期)</div><!-- end label-left -->
    <div class="rightlabel-right">{{ $next_event->start_at }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="color: #fff;">&nbsp;</div><!-- end label-left -->
    <div class="rightlabel-right"></div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Time (时间)</div><!-- end label-left -->
    <div class="rightlabel-right">{{ $time_now }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->
</div><!-- end receipt-info -->

<div class="receipt-info">
  <div class="paidby" style="width: 49mm; float: left;">
    <p style="font-weight: bold">Paid By (付款者)</p>
    <p>{{ $transaction->paid_by }} ({{ $transaction->focusdevotee_id }})</p>
    <p style="margin-top: 15px; font-weight: bold">No of Set(s) / 份数</p>
  </div>

  <div style="width: 22mm; float: left; border: 1px solid black; height: 2.7cm; line-height: 2.7cm; text-align: center; vertical-align: middle;">
    <span style="font-size: 70px; font-weight: bold;">{{ $paginate_receipt['no_of_set'] }}</span>
  </div>
</div><!-- end receipt-info -->

<div class="receipt-info">
  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Total Amount (总额)</div><!-- end label-left -->
    <div class="rightlabel-right">S$ {{ $paginate_receipt['total_amount'] }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-rightwrapper">
    <div class="rightlabel-left" style="font-weight: bold">Receipt No (收据)</div><!-- end label-left -->
    <div class="rightlabel-right">
      @if($paginate_receipt['first_receipt_no'] == $paginate_receipt['last_receipt_no'])
        {{ $paginate_receipt['first_receipt_no'] }}
      @elseif($paginate_receipt['first_receipt_no'] != $paginate_receipt['last_receipt_no'])
        {{ $paginate_receipt['first_receipt_no'] }} -{{ $paginate_receipt['last_receipt_no'] }} &nbsp;
      @endif
    </div><!-- end label-right -->
  </div><!-- end label-wrapper -->
</div><!-- end receipt-info -->
