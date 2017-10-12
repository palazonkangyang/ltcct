@php
  $kongdan_focusdevotee = Session::get('kongdan_focusdevotee');
  $kongdan_setting_samefamily = Session::get('kongdan_setting_samefamily');
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

        @if(Session::has('kongdan_setting_samefamily'))

        <tbody id="has_session">
          @if(count($kongdan_focusdevotee) > 0)

          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same kongdan_id" name="kongdan_id[]"
              value="1" <?php if ($kongdan_focusdevotee[0]->kongdan_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_kongdan_id" name="hidden_kongdan_id[]"
              value="">
            </td>
            <td>
              @if($kongdan_focusdevotee[0]->deceased_year != null)
              <span class="text-danger">{{ $kongdan_focusdevotee[0]->chinese_name }}</span>
              @else
              <span>{{ $kongdan_focusdevotee[0]->chinese_name }}</span>
              @endif
            </td>
            <td>
              <input type="hidden" name="devotee_id[]" value="{{ $kongdan_focusdevotee[0]->devotee_id }}">
              @if($kongdan_focusdevotee[0]->specialremarks_devotee_id == null)
              <span>{{ $kongdan_focusdevotee[0]->devotee_id }}</span>
              @else
              <span class="text-danger">{{ $kongdan_focusdevotee[0]->devotee_id }}</span>
              @endif
            </td>
            <td></td>
            <td>{{ $kongdan_focusdevotee[0]->guiyi_name }}</td>
            <td></td>
            <td>
              @if(isset($kongdan_focusdevotee[0]->oversea_addr_in_chinese))
                {{ $kongdan_focusdevotee[0]->oversea_addr_in_chinese }}
              @elseif(isset($kongdan_focusdevotee[0]->address_unit1) && isset($kongdan_focusdevotee[0]->address_unit2))
                {{ $kongdan_focusdevotee[0]->address_houseno }}, #{{ $kongdan_focusdevotee[0]->address_unit1 }}-{{ $kongdan_focusdevotee[0]->address_unit2 }}, {{ $kongdan_focusdevotee[0]->address_street }}, {{ $kongdan_focusdevotee[0]->address_postal }}
              @else
                {{ $kongdan_focusdevotee[0]->address_houseno }}, {{ $kongdan_focusdevotee[0]->address_street }}, {{ $kongdan_focusdevotee[0]->address_postal }}
              @endif
            </td>
            <td>
              @if(isset($kongdan_focusdevotee[0]->paytill_date) && \Carbon\Carbon::parse($kongdan_focusdevotee[0]->paytill_date)->lt($now))
              <span class="text-danger">{{ \Carbon\Carbon::parse($kongdan_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
              @elseif(isset($kongdan_focusdevotee[0]->paytill_date))
              <span>{{ \Carbon\Carbon::parse($kongdan_focusdevotee[0]->paytill_date)->format("d/m/Y") }}</span>
              @else
              <span>{{ $kongdan_focusdevotee[0]->paytill_date }}</span>
              @endif
            </td>
            <td></td>
            <td>
              @if(isset($kongdan_focusdevotee[0]->lasttransaction_at))
              {{ \Carbon\Carbon::parse($kongdan_focusdevotee[0]->lasttransaction_at)->format("d/m/Y") }}
              @else
              {{ $kongdan_focusdevotee[0]->lasttransaction_at }}
              @endif
            </td>
          </tr>

          @endif

          @foreach($kongdan_setting_samefamily as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same kongdan_id" name="kongdan_id[]"
              value="1" <?php if ($devotee->kongdan_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_kongdan_id" name="hidden_kongdan_id[]"
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

      @if(count($kongdan_setting_samefamily_last1year) > 0)

      <h5 style="font-weight: bold;">
        <span class="setting-history">KD-{{$this_year - 1}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>

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

        <tbody id="has_session">
          @foreach($kongdan_setting_samefamily_last1year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" disabled>
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
      </table>

      @endif

      @if(count($kongdan_setting_samefamily_last2year) > 0)

      <h5 style="font-weight: bold;">
        <span class="setting-history">KD-{{$this_year - 2}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>

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

        <tbody id="has_session">
          @foreach($kongdan_setting_samefamily_last2year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" disabled>
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
      </table>

      @endif

      @if(count($kongdan_setting_samefamily_last3year) > 0)

      <h5 style="font-weight: bold;">
        <span class="setting-history">KD-{{$this_year - 3}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>

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

        <tbody id="has_session">
          @foreach($kongdan_setting_samefamily_last3year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" disabled>
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
      </table>

      @endif

      @if(count($kongdan_setting_samefamily_last4year) > 0)

      <h5 style="font-weight: bold;">
        <span class="setting-history">KD-{{$this_year - 4}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>

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

        <tbody id="has_session">
          @foreach($kongdan_setting_samefamily_last4year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" disabled>
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
      </table>

      @endif

      @if(count($kongdan_setting_samefamily_last5year) > 0)

      <h5 style="font-weight: bold;">
        <span class="setting-history">KD-{{$this_year - 5}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>

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

        <tbody id="has_session">
          @foreach($kongdan_setting_samefamily_last5year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" disabled>
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
      </table>

      @endif

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
