<div class="label-rightwrapper section-left-transaction-detail">
  <div class="label-wrapper">
    <div class="label-left" style="font-weight: bold">Receipt Date <br />(日期)</div><!-- end label-left -->
    <div class="label-right">{{ $transaction->trans_at }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-wrapper2">
    <div class="label-left" style="font-weight: bold">Receipt No <br />(收据)</div><!-- end label-left -->
    <div class="label-right2">
      @if($paginate_receipt['first_receipt_no'] == $paginate_receipt['last_receipt_no'])
        {{ $paginate_receipt['first_receipt_no'] }}
      @elseif($paginate_receipt['first_receipt_no'] != $paginate_receipt['last_receipt_no'])
        {{ $paginate_receipt['first_receipt_no'] }} - {{ $paginate_receipt['last_receipt_no'] }}
      @endif
    </div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-wrapper">
    <div class="label-left" style="font-weight: bold">Paid By <br />(付款者)</div><!-- end label-left -->
    <div class="label-right">{{ $transaction->paid_by }} ({{ $transaction->focusdevotee_id }})</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-wrapper2">
    <div class="label-left" style="font-weight: bold">Transaction No <br />(交易)</div><!-- end label-left -->
    <div class="label-right2">{{ $transaction->trans_no }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-wrapper">
    <div class="label-left" style="font-weight: bold">Description <br />(项目)</div><!-- end label-left -->
    <div class="label-right">{{ $module->chinese_name }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-wrapper2">
    <div class="label-left" style="font-weight: bold">Attended By <br />(接待者)</div><!-- end label-left -->
    <div class="label-right2">{{ $transaction->attended_by }}</div><!-- end label-right -->
  </div><!-- end label-wrapper -->

  <div class="label-mainwrapper">
    <div class="label-left" style="font-weight: bold">Address</div><!-- end label-left -->
    <div class="">{{ $family_address}}</div>


  </div><!-- end label-wrapper -->
</div><!-- end section-left-transaction-detail -->
