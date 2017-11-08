@php

  $qifu_focusdevotee = Session::get('qifu_focusdevotee');
  $qifu_setting_samefamily = Session::get('qifu_setting_samefamily');
  $focus_devotee = Session::get('focus_devotee');

@endphp

<div class="form-body">

  <div class="form-group">
    @if(count($focus_devotee) > 0)
    <p class="text-right text-danger" style="margin-right: 30px; margin-bottom:0;">
      Family Code: {{ $focus_devotee[0]->familycode_id }}
    </p>
    @endif
  </div>

  <form method="post" action="{{ URL::to('/fahui/qifu-samefamily-setting') }}"
    class="form-horizontal form-bordered" id="qifu_samefamily_form">

    {!! csrf_field() !!}

    <div class="form-group">

      <table class="table table-bordered" id="same_familycode_table">
        <thead>
          <tr>
            <th>#</th>
            <th>Chinese Name</th>
            <th>Devotee#</th>
            <th>RegisterBy</th>
            <th>Guiyi ID</th>
            <th>GY</th>
            <th>Item Description</th>
            <th>M.Paid Till</th>
            <th>Paid By</th>
            <th>Last Trans</th>
          </tr>
        </thead>

        @if(Session::has('qifu_setting_samefamily'))

        <tbody id="has_session">
          @if(count($qifu_focusdevotee) > 0)

          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same qifu_id" name="qifu_id[]"
              value="1" <?php if ($qifu_focusdevotee[0]->qifu_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_qifu_id" name="hidden_qifu_id[]"
              value="">
            </td>
            <td>
              @if($qifu_focusdevotee[0]->deceased_year != null)
              <span class="text-danger">{{ $qifu_focusdevotee[0]->chinese_name }}</span>
              @else
              <span>{{ $qifu_focusdevotee[0]->chinese_name }}</span>
              @endif
            </td>
            <td>
              <input type="hidden" name="devotee_id[]" value="{{ $qifu_focusdevotee[0]->devotee_id }}">
              @if($qifu_focusdevotee[0]->specialremarks_devotee_id == null)
              <span>{{ $qifu_focusdevotee[0]->devotee_id }}</span>
              @else
              <span class="text-danger">{{ $qifu_focusdevotee[0]->devotee_id }}</span>
              @endif
            </td>
            <td></td>
            <td>{{ $qifu_focusdevotee[0]->guiyi_name }}</td>
            <td></td>
            <td>
              @if(isset($qifu_focusdevotee[0]->oversea_addr_in_chinese))
                {{ $qifu_focusdevotee[0]->oversea_addr_in_chinese }}
              @elseif(isset($qifu_focusdevotee[0]->address_unit1) && isset($qifu_focusdevotee[0]->address_unit2))
                {{ $qifu_focusdevotee[0]->address_houseno }}, #{{ $qifu_focusdevotee[0]->address_unit1 }}-{{ $qifu_focusdevotee[0]->address_unit2 }}, {{ $qifu_focusdevotee[0]->address_street }}, {{ $qifu_focusdevotee[0]->address_postal }}
              @else
                {{ $qifu_focusdevotee[0]->address_houseno }}, {{ $qifu_focusdevotee[0]->address_street }}, {{ $qifu_focusdevotee[0]->address_postal }}
              @endif
            </td>
            <td>
              @if(isset($qifu_focusdevotee[0]->paytill_date) && \Carbon\Carbon::parse($qifu_focusdevotee[0]->paytill_date)->lt($now))
              <span class="text-danger">{{ \Carbon\Carbon::parse($qifu_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
              @elseif(isset($qifu_focusdevotee[0]->paytill_date))
              <span>{{ \Carbon\Carbon::parse($qifu_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
              @else
              <span>{{ $qifu_focusdevotee[0]->paytill_date }}</span>
              @endif
            </td>
            <td></td>
            <td>
              @if(isset($qifu_focusdevotee[0]->lasttransaction_at))
              {{ \Carbon\Carbon::parse($qifu_focusdevotee[0]->lasttransaction_at)->format("d/m/Y") }}
              @else
              {{ $qifu_focusdevotee[0]->lasttransaction_at }}
              @endif
            </td>
          </tr>

          @endif

          @foreach($qifu_setting_samefamily as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same qifu_id" name="qifu_id[]"
              value="1" <?php if ($devotee->qifu_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_qifu_id" name="hidden_qifu_id[]"
              value="">
            </td>
            <td>
              @if($devotee->deceased_year != null)
              <span class="text-danger">{{ $devotee->chinese_name }}</span>
              @else
              <span>{{ $devotee->chinese_name }}</span>
              @endif
            </td>
            <td>
              <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
              @if($devotee->specialremarks_devotee_id == null)
              <span>{{ $devotee->devotee_id }}</span>
              @else
              <span class="text-danger">{{ $devotee->devotee_id }}</span>
              @endif
            </td>
            <td>
              @if(\Carbon\Carbon::parse($devotee->lasttransaction_at)->lt($date))
              <span style="color: #a5a5a5">{{ $devotee->member_id }}</span>
              @else
              <span>{{ $devotee->member_id }}</span>
              @endif
            </td>
            <td>{{ $devotee->guiyi_name }}</td>
            <td></td>
            <td>
              @if(isset($devotee->oversea_addr_in_chinese))
                {{ $devotee->oversea_addr_in_chinese }}
              @elseif(isset($devotee->address_unit1) && isset($devotee->address_unit2))
                {{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
              @else
                {{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
              @endif
            </td>
            <td>
              @if(isset($devotee->paytill_date) && \Carbon\Carbon::parse($devotee->paytill_date)->lt($now))
              <span class="text-danger">{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
              @elseif(isset($devotee->paytill_date))
              <span>{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
              @else
              <span>{{ $devotee->paytill_date }}</span>
              @endif
            </td>
            <td></td>
            <td>
              @if(isset($devotee->lasttransaction_at))
              {{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}
              @else
              {{ $devotee->lasttransaction_at }}
              @endif
            </td>
          </tr>
          @endforeach

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

    <p></p>

    <hr>

    <p></p>

    <div class="form-group">
      @if(count($focus_devotee) > 0)
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="qifu_focusdevotee_id">
      @else
        <input type="hidden" name="focusdevotee_id" id="qifu_focusdevotee_id">
      @endif
    </div><!-- end form-group -->

    <div class="form-actions">
      <button type="submit" class="btn blue" id="update_sameaddr_btn">Confirm</button>
      <button type="reset" class="btn default" id="cancel_samefamily_btn">Cancel</button>
    </div><!-- end form-actions -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->
