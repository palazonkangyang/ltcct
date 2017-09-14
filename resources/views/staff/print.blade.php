<!DOCTYPE html>
<html>
<head>
	<title>Receipt Print Preview</title>

	<link href="{{ asset('/css/normalize.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/paper.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/print.css') }}" rel="stylesheet" type="text/css" />

	<style type="text/css">
		.right{
			text-align: right;
		}

		@page { size: A5 landscape }
	</style>

</head>
<body class="A5 landscape">

  @php

    $count = count($receipts);
    $familycode = $receipts[0]->familycode_id;
		$flag_familymorethan8 = 0;
    $count_family8 = 0;
    $count_receipt = 0;
    $devotee_count = 0;
    $receipt_no = 0;

  @endphp

	@if($samefamily_no > 8)
		@php
		$receipt_count = $samefamily_no % 6;
		$receipt_count = $receipt_count + 6;
		@endphp
	@else
		@php $receipt_count = 6; @endphp
	@endif

  @if($print_format == 'hj')

  @for($j = 0; $j < $loop; $j++)

  @if($receipts[0]->familycode_id == $receipts[$count_family8]->familycode_id)

	  @if($samefamily_no > 0)

		<section class="sheet padding-5mm">

		  <header>

		  </header>

		  <div id="leftcontent">

		    <div style="width: 100%; border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 5px; font-size: 14px; font-weight: bold">
		      OFFICIAL RECEIPT - 正式收据
		    </div>

		    <div class="receipt-info">

		      <div class="label-wrapper">
		        <div class="label-left">Receipt Date <br />(日期)</div><!-- end label-left -->
		        <div class="label-right">{{ $receipts[0]->trans_date }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      @if($count > 1)

		      <div class="label-wrapper2">
		        <div class="label-left">Receipt No <br /> (收据)</div><!-- end label-left -->

		        @if($count > 6)
		          <div class="label-right2">{{ $receipts[0]->xy_receipt }} - {{ $receipts[$samefamily_no - 1]->xy_receipt }}</div><!-- end label-right -->
		        @else
		          <div class="label-right2">{{ $receipts[$count_family8]->xy_receipt }} - {{ $receipts[$samefamily_no - 1]->xy_receipt }}</div><!-- end label-right -->
		        @endif
		      </div><!-- end label-wrapper -->

		      @else

		      <div class="label-wrapper2">
		        <div class="label-left">Receipt No <br /> (收据)</div><!-- end label-left -->
		        <div class="label-right2">{{ $receipts[0]->xy_receipt }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      @endif

		      <div class="label-wrapper">
		        <div class="label-left">Paid By <br /> (付款者)</div><!-- end label-left -->
		        <div class="label-right">{{ $receipts[0]->chinese_name }} (D - {{ $receipts[0]->focusdevotee_id }})</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-wrapper2">
		        <div class="label-left">Transaction No <br /> (交易)</div><!-- end label-left -->
		        <div class="label-right2">{{ $receipts[0]->trans_no }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-wrapper" style="font-weight: bold;">
		        <div class="label-left">Description <br /> (项目)</div><!-- end label-left -->
		        <div class="label-right">
		          @if($receipts[0]->description == 'General Donation - 香油')
		          香油
		          @elseif($receipts[0]->description == 'General Donation - 慈济')
		          慈济
		          @endif
		        </div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-wrapper2">
		        <div class="label-left">Attended By <br /> (接待者)</div><!-- end label-left -->
		        <div class="label-right2">{{ $receipts[0]->first_name }} {{ $receipts[0]->last_name }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

					<div class="label-mainwrapper">
		        <div class="label-left">Address</div><!-- end label-left -->

							@if(isset($receipts[0]->oversea_addr_in_chinese))
								{{ $receipts[0]->oversea_addr_in_chinese }}
							@elseif(isset($receipts[0]->address_unit1) && isset($receipts[0]->address_unit2))
								{{ $receipts[0]->address_houseno }}, #{{ $receipts[0]->address_unit1 }}-{{ $receipts[0]->address_unit2 }}, {{ $receipts[0]->address_street }}, {{ $receipts[0]->address_postal }}
							@else
								{{ $receipts[0]->address_houseno }}, {{ $receipts[0]->address_street }}, {{ $receipts[0]->address_postal }}
							@endif

		      </div><!-- end label-wrapper -->

		    </div><!-- end receipt-info -->

		    <hr>

		    <div class="receipt-list">

		      <table class="receipt-table">
		        <thead>
		          <tr>
		            <th width="1%">S/No</th>
		            <th width="15%">Chinese Name</th>
		            <th width="5%">Devotee</th>
		            <th width="11%">HJ/ GR</th>
		            <th width="10%">Receipt</th>
		            <th width="10%">Amount</th>
		          </tr>
		        </thead>

		        <tbody>

		          @php $rowno = 1; $sum= 0; @endphp

		          @if ($samefamily_no > 8)

		          @php
		            $startno = $count_family8;
		            $divide = intval(round($samefamily_no / 6, 0));
								$modulus = $samefamily_no % 6;
		          @endphp

							@if($j < 1)
								@php $endno = 6; @endphp
							@else
								@php $endno = $count_family8 + $modulus; @endphp
							@endif

		          @else

		          @php
								$divide = 1;
		            $startno = $count_family8;
		            $endno = $samefamily_no;
		          @endphp

		          @endif

		          @for($i = $startno; $i < $endno; $i++)

		          @if($receipts[$count_family8]->familycode_id == $receipts[$i]->familycode_id)

		          <tr>
		            <td>{{ $rowno }}</td>
		            @if(isset($receipts[$i]->deceased_year))
								<td>{{ $receipts[$i]->chinese_name }} (已故)</td>
								@else
								<td>{{ $receipts[$i]->chinese_name }}</td>
								@endif
		            <td>{{ $receipts[$i]->devotee_id }}</td>
		            <td>
		              @if($receipts[$i]->hjgr == 'hj')
		                合家
		              @else
		                个人
		              @endif
		            </td>
		            <td>{{ $receipts[$i]->xy_receipt }}</td>
		            <td>{{ number_format( $receipts[$i]->amount, 2) }}</td>
		          </tr>

		          @php $devotee_count++; $rowno++;  $sum += $receipts[$i]->amount; $count_family8++; @endphp

		          @endif

		          @endfor
		        </tbody>
		      </table>

		    </div><!-- end receipt-list -->

		    <div style="overflow: hidden;">

		      <div style="float:left; width: 60%;">
		        <p style="font-weight: bold;">Payment Mode : {{ $receipts[0]->mode_payment }}
						<br />(付款方式)</p>
		      </div>

		      <div class="float: right: width: 40%;">
		        <p style="font-weight: bold;">Total Amount: S$ {{ $total_amount }} <br />
						(总额)</p>
		      </div>

		    </div>

		  </div><!-- end leftcontent -->

		  <div id="rightcontent">

		    <div style="border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 10px; font-size: 14px; font-weight: bold">
		      OFFICIAL RECEIPT - 正式收据
		    </div>

		    <div class="receipt-info">
		      <div class="label-rightwrapper">
		        <div class="rightlabel-left">Receipt Date (日期)</div><!-- end label-left -->
		        <div class="rightlabel-right">{{ $receipts[0]->trans_date }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-rightwrapper" style="font-weight: bold">
		        <div class="rightlabel-left">Description (项目)</div><!-- end label-left -->
		        <div class="rightlabel-right">
		          @if($receipts[0]->description == 'General Donation - 香油')
		          香油
		          @elseif($receipts[0]->description == 'General Donation - 慈济')
		          慈济
		          @endif
		        </div><!-- end label-right -->
		      </div><!-- end label-wrapper -->
		    </div><!-- end receipt-info -->

		    <hr>
		    <hr>

		    <div class="receipt-info">
		      <div class="label-rightwrapper" style="font-weight: bold">
		        <div class="rightlabel-left">Next Event (下个法会)</div><!-- end label-left -->
		        <div class="rightlabel-right">{{ $receipts[0]->event }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-rightwrapper">
		        <div class="rightlabel-left">Event Date (法会日期)</div><!-- end label-left -->
		        <div class="rightlabel-right">{{ $receipts[0]->start_at }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-rightwrapper">
		        <div class="rightlabel-left" style="color: #fff;">&nbsp;</div><!-- end label-left -->
		        <div class="rightlabel-right">{{ $receipts[0]->lunar_date }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-rightwrapper">
		        <div class="rightlabel-left">Time (时间)</div><!-- end label-left -->
		        <div class="rightlabel-right">{{ $receipts[0]->time }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->
		    </div><!-- end receipt-info -->

		    <div class="receipt-info">
		      <div class="paidby" style="width: 49mm; float: left;">
		        <p style="font-weight: bold">Paid By (付款者)</p>
		        <p>{{ $paid_by[0]->chinese_name }}<br />
		        D - {{ $paid_by[0]->devotee_id }}</p>
		        <p style="margin-top: 15px; font-weight: bold">No of Set(s) / 份数</p>
		      </div>

		      <div style="width: 22mm; float: left; border: 1px solid black; height: 2.7cm; line-height: 2.7cm; text-align: center; vertical-align: middle;">
		        <span style="font-size: 70px; font-weight: bold;">{{ $samefamily_no }}</span>
		      </div>
		    </div><!-- end receipt-info -->

		    <div class="receipt-info">
		      <div class="label-rightwrapper" style="font-weight: bold">
		        <div class="rightlabel-left"><b>Total Amount (总额)</b></div><!-- end label-left -->
		        <div class="rightlabel-right">S$ {{ $total_amount }}</div><!-- end label-right -->
		      </div><!-- end label-wrapper -->

		      <div class="label-rightwrapper" style="font-weight: bold">
		        <div class="rightlabel-left"><b>Receipt No (收据)</b></div><!-- end label-left -->
		        @if($count > 8)
		          <div class="rightlabel-right">{{ $receipts[0]->xy_receipt }} - {{ $receipts[$samefamily_no - 1]->xy_receipt }}</div><!-- end label-right -->
		        @else
		          <div class="rightlabel-right">{{ $receipts[0]->xy_receipt }} - {{ $receipts[$samefamily_no - 1]->xy_receipt }}</div><!-- end label-right -->
		        @endif
		      </div><!-- end label-wrapper -->
		    </div><!-- end receipt-info -->

		  </div><!-- end rightcontent -->

		</section>

		@endif

  @endif

  @php $receipt_no++; @endphp

  @endfor

  @for($i = $devotee_count; $i < count($receipts); $i++)

  <section class="sheet padding-5mm">

    <header>

    </header>

    <div id="leftcontent">

      <div style="width: 100%; border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 5px; font-size: 14px; font-weight: bold">
        OFFICIAL RECEIPT - 正式收据
      </div>

      <div class="receipt-info">

        <div class="label-wrapper">
          <div class="label-left">Receipt Date <br />(日期)</div><!-- end label-left -->
          <div class="label-right">{{ $receipts[$i]->trans_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Receipt No <br />(收据)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipts[$i]->xy_receipt }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper">
          <div class="label-left">Paid By <br />(付款者)</div><!-- end label-left -->
          <div class="label-right">{{ $receipts[0]->chinese_name }} (D - {{ $receipts[0]->focusdevotee_id }})</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Transaction No <br />(交易)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipts[$i]->trans_no }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper">
          <div class="label-left" style="font-weight: bold">Description <br />(项目)</div><!-- end label-left -->
          <div class="label-right">
						@if($receipts[0]->description == 'General Donation - 香油')
						香油
						@elseif($receipts[0]->description == 'General Donation - 慈济')
						慈济
						@endif
					</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Attended By <br />(接待者)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipts[$i]->first_name }} {{ $receipts[$i]->last_name }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

				<div class="label-mainwrapper">
					<div class="label-left">Address</div><!-- end label-left -->

					@if(isset($receipts[$i]->oversea_addr_in_chinese))
						{{ $receipts[$i]->oversea_addr_in_chinese }}
					@elseif(isset($receipts[$i]->address_unit1) && isset($receipts[$i]->address_unit2))
						{{ $receipts[$i]->address_houseno }}, #{{ $receipts[$i]->address_unit1 }}-{{ $receipts[$i]->address_unit2 }}, {{ $receipts[$i]->address_street }}, {{ $receipts[$i]->address_postal }}
					@else
						{{ $receipts[$i]->address_houseno }}, {{ $receipts[$i]->address_street }}, {{ $receipts[$i]->address_postal }}
					@endif

				</div><!-- end label-wrapper -->

      </div><!-- end receipt-info -->

      <hr>

      <div class="receipt-list">

        <table class="receipt-table">
          <thead>
            <tr>
							<th width="1%">S/No</th>
              <th width="15%">Chinese Name</th>
              <th width="5%">Devotee</th>
              <th width="11%">HJ/ GR</th>
              <th width="15%">Receipt</th>
              <th width="18%">Amount</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>1</td>
              <td>{{ $receipts[$i]->chinese_name }}</td>
              <td>{{ $receipts[$i]->devotee_id }}</td>
              <td>
                @if($receipts[$i]->hjgr == 'hj')
                  合家
                @else
                  个人
                @endif
              </td>
              <td>{{ $receipts[$i]->xy_receipt }}</td>
              <td>{{ number_format( $receipts[$i]->amount, 2) }}</td>
            </tr>
          </tbody>
        </table>

      </div><!-- end receipt-list -->

      <div style="overflow: hidden;">

        <div style="float:left; width: 60%;">
          <p style="font-weight: bold;">Payment Mode : {{ $receipts[$i]->mode_payment }}<br /> (付款方式)</p>
        </div>

        <div class="float: right: width: 40%;">
          <p style="font-weight: bold;">Total Amount S$ {{ number_format( $receipts[$i]->amount, 2) }} <br /> (总额)</p>
        </div>

      </div>

    </div><!-- end leftcontent -->

    <div id="rightcontent">

      <div style="border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 10px; font-weight: bold">
        OFFICIAL RECEIPT - 正式收据
      </div>

      <div class="receipt-info">
        <div class="label-rightwrapper">
          <div class="rightlabel-left">Receipt Date (日期)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->trans_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper" style="font-weight: bold">
          <div class="rightlabel-left">Description (项目)</div><!-- end label-left -->
          <div class="rightlabel-right">
						@if($receipts[0]->description == 'General Donation - 香油')
						香油
						@elseif($receipts[0]->description == 'General Donation - 慈济')
						慈济
						@endif
					</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

      <hr>
      <hr>

      <div class="receipt-info">
        <div class="label-rightwrapper" style="font-weight: bold">
          <div class="rightlabel-left">Next Event (下个法会)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->event }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left">Event Date (法会日期)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->start_at }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left" style="color: #fff;">&nbsp;</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->lunar_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left">Time (时间)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->time }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

      <div class="receipt-info">
        <div class="paidby" style="width: 49mm; float: left;">
          <p style="font-weight: bold">Paid By (付款者)</p>
          <p>{{ $paid_by[0]->chinese_name }}<br />
          D - {{ $paid_by[0]->devotee_id }}</p>
          <p style="margin-top: 15px; font-weight: bold">No of Set(s) / 份数</p>
        </div>

        <div style="width: 22mm; float: left; border: 1px solid black; height: 2.7cm; line-height: 2.7cm; text-align: center; vertical-align: middle;">
          <span style="font-size: 70px; font-weight: bold;">1</span>
        </div>
      </div><!-- end receipt-info -->

      <div class="receipt-info" style="font-weight: bold">
        <div class="label-rightwrapper">
          <div class="rightlabel-left">Total Amount (总额)</div><!-- end label-left -->
          <div class="rightlabel-right">S$ {{ number_format( $receipts[$i]->amount, 2) }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left">Receipt No (收据)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipts[$i]->xy_receipt }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

    </div><!-- end rightcontent -->

  </section>

  @endfor

  @else

  @foreach($receipts as $receipt)
  <section class="sheet padding-5mm">

    <header>
    </header>

    <div id="leftcontent">

      <div style="width: 100%; border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 10px; font-weight: bold">
        OFFICIAL RECEIPT - 正式收据
      </div>

      <div class="receipt-info">

        <div class="label-wrapper">
          <div class="label-left">Receipt Date <br /> (日期)</div><!-- end label-left -->
          <div class="label-right">{{ $receipt->trans_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Receipt No <br /> (收据)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipt->xy_receipt }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper">
          <div class="label-left">Paid By <br /> (付款者)</div><!-- end label-left -->
          <div class="label-right">{{ $receipts[0]->chinese_name }} (D - {{ $receipts[0]->focusdevotee_id }})</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Transaction No <br /> (交易)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipt->trans_no }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper" style="font-weight: bold">
          <div class="label-left">Description <br /> (项目)</div><!-- end label-left -->
          <div class="label-right">
						@if($receipts[0]->description == 'General Donation - 香油')
						香油
						@elseif($receipts[0]->description == 'General Donation - 慈济')
						慈济
						@endif
					</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-wrapper2">
          <div class="label-left">Attended By <br /> (接待者)</div><!-- end label-left -->
          <div class="label-right2">{{ $receipt->first_name }} {{ $receipt->last_name }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

				<div class="label-mainwrapper">
					<div class="label-left">Address</div><!-- end label-left -->

					@if(isset($receipt->oversea_addr_in_chinese))
						{{ $receipt->oversea_addr_in_chinese }}
					@elseif(isset($receipt->address_unit1) && isset($receipt->address_unit2))
						{{ $receipt->address_houseno }}, #{{ $receipt->address_unit1 }}-{{ $receipt->address_unit2 }}, {{ $receipt->address_street }}, {{ $receipt->address_postal }}
					@else
						{{ $receipt->address_houseno }}, {{ $receipt->address_street }}, {{ $receipt->address_postal }}
					@endif

				</div><!-- end label-wrapper -->

      </div><!-- end receipt-info -->

      <hr>

      <div class="receipt-list">

        <table class="receipt-table">
          <thead>
            <tr>
							<th width="1%">S/No</th>
              <th width="20%">Chinese Name</th>
              <th width="8%">Devotee</th>
              <th width="11%">HJ/ GR</th>
              <th width="15%">Receipt</th>
              <th width="10%">Amount</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>1</td>
							@if(isset($receipt->deceased_year))
							<td>{{ $receipt->chinese_name }} (已故)</td>
							@else
							<td>{{ $receipt->chinese_name }}</td>
							@endif
              <td>{{ $receipt->devotee_id }}</td>
              <td>
                @if($receipt->hjgr == 'hj')
                  合家
                @else
                  个人
                @endif
              </td>
              <td>{{ $receipt->xy_receipt }}</td>
              <td>{{ number_format( $receipt->amount, 2) }}</td>
            </tr>

            @php  $sum = $receipt->amount; @endphp
          </tbody>
        </table>

      </div><!-- end receipt-list -->

      <div style="overflow: hidden;">

        <div style="float:left; width: 60%;">
          <p style="font-weight: bold;">Payment Mode : {{ $receipt->mode_payment }} <br /> (付款方式)</p>
        </div>

        <div class="float: right: width: 40%;">
          <p style="font-weight: bold;">Total Amount S$ {{ number_format( $receipt->amount, 2) }} <br /> (总额)</p>
        </div>

      </div>

    </div><!-- end leftcontent -->

    <div id="rightcontent">

      <div style="border: 1px solid black; line-height: 0.8cm; text-align: center; vertical-align: middle; margin-bottom: 10px; font-weight: bold">
        OFFICIAL RECEIPT - 正式收据
      </div>

      <div class="receipt-info">
        <div class="label-rightwrapper">
          <div class="rightlabel-left">Receipt Date (日期)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->trans_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper" style="font-weight: bold">
          <div class="rightlabel-left">Description (项目)</div><!-- end label-left -->
          <div class="rightlabel-right">
						@if($receipts[0]->description == 'General Donation - 香油')
						香油
						@elseif($receipts[0]->description == 'General Donation - 慈济')
						慈济
						@endif
					</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

      <hr>
      <hr>

      <div class="receipt-info">
        <div class="label-rightwrapper" style="font-weight: bold">
          <div class="rightlabel-left">Next Event (下个法会)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->event }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left">Event Date (法会日期)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->start_at }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left" style="color: #fff;">&nbsp;</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->lunar_date }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left">Time (时间)</div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->time }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

      <div class="receipt-info">
        <div class="paidby" style="width: 49mm; float: left;">
          <p style="font-weight: bold">Paid By (付款者)</p>
          <p>{{ $paid_by[0]->chinese_name }}<br />
          D - {{ $paid_by[0]->devotee_id }}</p>
          <p style="margin-top: 15px; font-weight: bold">No of Set(s) / 份数</p>
        </div>

        <div style="width: 22mm; float: left; border: 1px solid black; height: 2.7cm; line-height: 2.7cm; text-align: center; vertical-align: middle;">
          <span style="font-size: 70px; font-weight: bold;">1</span>
        </div>
      </div><!-- end receipt-info -->

      <div class="receipt-info" style="font-weight: bold">
        <div class="label-rightwrapper">
          <div class="rightlabel-left"><b>Total Amount (总额)</b></div><!-- end label-left -->
          <div class="rightlabel-right"><b>S$ {{ number_format( $receipt->amount, 2) }}</b></div><!-- end label-right -->
        </div><!-- end label-wrapper -->

        <div class="label-rightwrapper">
          <div class="rightlabel-left"><b>Receipt No (收据)</b></div><!-- end label-left -->
          <div class="rightlabel-right">{{ $receipt->xy_receipt }}</div><!-- end label-right -->
        </div><!-- end label-wrapper -->
      </div><!-- end receipt-info -->

    </div><!-- end rightcontent -->

  </section>

  @endforeach

  @endif

</body>
</html>

<script type="text/javascript">
</script>
