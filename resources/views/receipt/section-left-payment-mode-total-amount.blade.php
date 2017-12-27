<div style="overflow: hidden;">

  <div style="float:left; width: 60%;">
    <p><span style="font-weight:bold;">Payment Mode:</span>
			{{ $transaction->mode_payment }}
		<br /><span style="font-weight:bold;">(付款方式)</span></p>
  </div>

  <div class="float: right: width: 40%;">
    <p><span style="font-weight: bold;">Total Amount:</span> S$ {{ $paginate_receipt['total_amount'] }}  <br /><span style="font-weight: bold;">(总额)</span></p>
  </div>

</div>
