@if($module['mod_id'] == Session::get('module.xiaozai_id'))
<div class="receipt-list">
@elseif($module['mod_id'] == Session::get('module.qifu_id'))
<div class="receipt-list-max-1">
@elseif($module['mod_id'] == Session::get('module.kongdan_id'))
<div class="receipt-list-max-1">
@else
<div class="receipt-list">
@endif


  <table class="receipt-table">
    <thead>
      <tr style="text-align:left;">
        @if( $module['mod_id'] == Session::get('module.xiaozai_id') )
          <th width="1%">S/No</th>
  				<th width="12%">Name</th>
          <th width="85%">Description</th>
          <th width="1%">Receipt</th>
          <th width="1%">Amount</th>

        @elseif( $module['mod_id'] == Session::get('module.kongdan_id') || $module['mod_id'] == Session::get('module.qifu_id') )
          <th width="9%">S/No</th>
  				<th width="20%">Devotee Id</th>
          <th width="55%">Chinese Name</th>
          <th width="15%">Receipt</th>
          <th width="1%">Amount</th>
        @endif

      </tr>
    </thead>

    <tbody>
			@foreach($paginate_receipt['receipt'] as $index=>$rct)
			<tr>
        @if( $module['mod_id'] == Session::get('module.xiaozai_id') )
          <td style="text-align: left;">{{$rct['sn_no']}}</td>
          @if($rct['is_receipt'] == false)
  					<td colspan="4" style="text-align: left; font-weight:bold;">{{$rct['name']}} {{ $rct['item_description'] }}</td>
  				@else
  					<td style="text-align: left;">{{$rct['name']}}</td>
  					<td style="text-align: left; font-size:0.8em;">{{ $rct['item_description'] }}</td>
  				@endif
          <td style="text-align: left;">{{ $rct['receipt_no'] }}</td>
  				<td style="text-align: right;">{{ $rct['amount'] }}</td>
        @else
          <td style="text-align: left;">{{$rct['sn_no']}}</td>
          <td style="text-align: left;">{{$rct['devotee_id']}}</td>
          <td style="text-align: left;">{{$rct['name']}}</td>
          <td style="text-align: left;">{{ $rct['receipt_no'] }}</td>
  				<td style="text-align: right;">{{ $rct['amount'] }}</td>
        @endif






			</tr>
			@endforeach

    </tbody>
  </table>

</div><!-- end receipt-list -->
