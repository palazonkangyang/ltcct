<div class="form-body">
  <form method="post" action="{{ URL::to('/staff/samefamily-setting') }}"
    class="form-horizontal form-bordered" id="samefamily_form">

    {!! csrf_field() !!}

    <div class="form-group">

      <table class="table table-bordered" id="same_familycode_table">
        <thead>
            <tr>
              <th>香/ 慈</th>
              <th>月捐</th>
              <th>Chinese Name</th>
              <th>Devotee#</th>
              <th>Member#</th>
              <th>Address</th>
              <th>Guiyi Name</th>
              <th>Contact</th>
              <th>Pay Till</th>
              <th>Mailer</th>
              <th>Last Trans</th>
              <th>Family Code</th>
            </tr>
        </thead>

        @if(Session::has('setting_samefamily'))

        @php

            $setting_samefamily = Session::get('setting_samefamily');
            $focus_devotee = Session::get('focus_devotee');
            $setting_generaldonation = Session::get('setting_generaldonation');
        @endphp

        <tbody id="has_session">

          @foreach($setting_samefamily as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="form-control same xiangyou_ciji_id" name="xiangyou_ciji_id[]"
              value="1" <?php if ($devotee->xiangyou_ciji_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_xiangyou_ciji_id" name="hidden_xiangyou_ciji_id[]"
              value="">
            </td>
            <td class="checkbox-col">
              <input type="checkbox" class="form-control same yuejuan_id" name="yuejuan_id[]"
              value="1" <?php if ($devotee->yuejuan_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_yuejuan_id" name="hidden_yuejuan_id[]"
              value="0">
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
            <td>
              @if(isset($devotee->oversea_addr_in_chinese))
                {{ $devotee->oversea_addr_in_chinese }}
              @elseif(isset($devotee->address_unit1) && isset($devotee->address_unit2))
                {{ $devotee->address_houseno }}, #{{ $devotee->address_unit1 }}-{{ $devotee->address_unit2 }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
              @else
                {{ $devotee->address_houseno }}, {{ $devotee->address_street }}, {{ $devotee->address_postal }}
              @endif
            </td>
            <td>{{ $devotee->guiyi_name }}</td>
            <td>{{ $devotee->contact }}</td>
            <td>
              @if(isset($devotee->paytill_date) && \Carbon\Carbon::parse($devotee->paytill_date)->lt($now))
              <span class="text-danger">{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
              @elseif(isset($devotee->paytill_date))
              <span>{{ \Carbon\Carbon::parse($devotee->paytill_date)->format("d/m/Y") }}</span>
              @else
              <span>{{ $devotee->paytill_date }}</span>
              @endif
            </td>
            <td>{{ $devotee->mailer }}</td>
            <td>
              @if(isset($devotee->lasttransaction_at))
              {{ \Carbon\Carbon::parse($devotee->lasttransaction_at)->format("d/m/Y") }}
              @else
              {{ $devotee->lasttransaction_at }}
              @endif
            </td>
            <td>{{ $devotee->familycode }}</td>
          </tr>
          @endforeach

        </tbody>

        @else

        <tbody id="no_session">
            <tr>
            <td colspan="12">No Data</td>
          </tr>
        </tbody>

        @endif
      </table>

    </div><!-- end form-group -->

    @php $focus_devotee = Session::get('focus_devotee'); @endphp

    <div class="form-group">
      @if(count($focus_devotee) > 0)
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}">
      @else
        <input type="hidden" name="focusdevotee_id" value="">
      @endif
    </div><!-- end form-group -->

    <div class="form-actions">
        <button type="submit" class="btn blue" id="update_sameaddr_btn">Update</button>
        <button type="reset" class="btn default" id="cancel_samefamily_btn">Cancel</button>
    </div><!-- end form-actions -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>
</div><!-- end form-body -->