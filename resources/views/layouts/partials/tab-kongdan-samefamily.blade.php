@php
  $same_family_code = Session::get('same_family_code')['kongdan'];
  $same_family_code_history = Session::get('same_family_code_history')['kongdan'];
  $focus_devotee = Session::get('focus_devotee');


  $kongdan_setting_samefamily_last1year = Session::get('kongdan_setting_samefamily_last1year');
  $kongdan_setting_samefamily_last2year = Session::get('kongdan_setting_samefamily_last2year');
  $kongdan_setting_samefamily_last3year = Session::get('kongdan_setting_samefamily_last3year');
  $kongdan_setting_samefamily_last4year = Session::get('kongdan_setting_samefamily_last4year');
  $kongdan_setting_samefamily_last5year = Session::get('kongdan_setting_samefamily_last5year');

@endphp

<div class="form-body">

  <div class="form-group">
    @if(count($focus_devotee) > 0)
    <p class="text-right text-danger" style="margin-right: 30px; margin-bottom:0;">
      Family Code: {{ $focus_devotee[0]->familycode_id }}
    </p>
    @endif
  </div>

  <form method="post" action="{{ URL::to('/fahui/kongdan-samefamily-setting') }}"
    class="form-horizontal form-bordered" id="kongdan_samefamily_form">
    <input type="hidden" name="mod_id" value=10>
    {!! csrf_field() !!}

    <div class="form-group">

      <table class="table table-bordered" id="same_familycode_table">
        <thead>
          <tr>
            <th>#</th>
            <th>Chinese Name</th>
            <th>Devotee#</th>
            <th>Register By</th>
            <th>Guiyi ID</th>
            <th>GY</th>
            <th>Item Description</th>
            <th>Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        @if(Session::has('same_family_code'))

        <tbody id="has_session">
          @foreach($same_family_code as $devotee)
          <input type="hidden" name="sfc_id[]" value="{{ $devotee->sfc_id }}">
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same kongdan_id checkbox-multi-select-module-kongdan-tab-sfc-section-sfc" name="kongdan_id[]"
              value="1" <?php if ($devotee->is_checked == 1){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_kongdan_id" name="is_checked[]"
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

    @php $this_year = date('Y'); @endphp

    <div class="form-group">

      <table class="table table-bordered kongdan_history_table">
        <thead>
          <tr>
            <th>#</th>
            <th>Chinese Name</th>
            <th>Devotee#</th>
            <th>Register By</th>
            <th>Guiyi ID</th>
            <th>GY</th>
            <th>Item Description</th>
            <th>Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        <tbody id="has_session">
          @if(isset($same_family_code_history))
            @foreach($same_family_code_history as $devotee)
            <tr>
              <td></td>
              <td>
                @if($devotee->deceased_year != null)
                <span class="text-danger">{{ $devotee->chinese_name }}</span>
                @else
                <span>{{ $devotee->chinese_name }}</span>
                @endif
              </td>
              <td>
                <input type="hidden" value="{{ $devotee->devotee_id }}" class="kongdan-history-id">
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
              <td>{{ $devotee->item_description }}</td>
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
          @endif
        </tbody>
      </table>

    </div><!-- end form-group -->

    <div class="form-group">
      @if(count($focus_devotee) > 0)
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="kongdan_focusdevotee_id">
      @else
        <input type="hidden" name="focusdevotee_id" id="kongdan_focusdevotee_id">
      @endif
    </div><!-- end form-group -->

    <div class="form-actions">
        <button type="submit" class="btn blue" id="update_kongdan_sameaddr_btn">Confirm</button>
        <button type="reset" class="btn default" id="cancel_samefamily_btn">Cancel</button>
    </div><!-- end form-actions -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->
