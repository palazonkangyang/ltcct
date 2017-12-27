@include('receipt.section-official-receipt')
@include('receipt.section-left-transaction-detail')
<hr>
@include('receipt.section-left-receipt-detail')
@include('receipt.section-left-payment-mode-total-amount')

@if($module['mod_id'] == Session::get('module.qifu_id') || $module['mod_id'] == Session::get('module.kongdan_id'))
<hr />
@include('receipt.section-left-next-event-event-date')
@endif
