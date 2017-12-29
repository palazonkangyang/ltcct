@if($module['mod_id'] == Session::get('module.xiaozai_id'))
  @include('receipt.section-official-receipt')
  @include('receipt.section-right-transaction-detail')
@elseif($module['mod_id'] == Session::get('module.qifu_id'))
  @include('receipt.section-right-qifu')
@elseif($module['mod_id'] == Session::get('module.kongdan_id'))
  @include('receipt.section-right-kongdan')
@endif
