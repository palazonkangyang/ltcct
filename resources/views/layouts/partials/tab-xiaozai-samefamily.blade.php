@php
  $xiaozai_focusdevotee = Session::get('xiaozai_focusdevotee');
  $xiaozai_setting_samefamily = Session::get('xiaozai_setting_samefamily');
  $focus_devotee = Session::get('focus_devotee');

  $xiaozai_setting_samefamily_last1year = Session::get('xiaozai_setting_samefamily_last1year');
@endphp

<div class="form-body">

  <form method="post" action="{{ URL::to('/fahui/xiaozai-samefamily-setting') }}"
    class="form-horizontal form-bordered" id="xiaozai_samefamily_form">

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
            <th>OPS</th>
            <th width="90px">Type</th>
            <th>Item Description</th>
            <th>M.Paid Till</th>
            <th>Paid By</th>
            <th>Last Trans</th>
          </tr>
        </thead>

        @if(Session::has('xiaozai_setting_samefamily'))

        <tbody id="has_session">
          @if(count($xiaozai_focusdevotee) > 0)

          @foreach($xiaozai_focusdevotee as $devotee)

          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same xiaozai_id" name="xiaozai_id[]"
              value="1" <?php if ($devotee->xiaozai_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_xiaozai_id" name="hidden_xiaozai_id[]"
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
            <td>{{ $devotee->ops }}</td>
            <td>
              @if($devotee->type == 'sameaddress')
              <select class="form-control type" name="type[]">
                <option value="sameaddress" selected>合家</option>
                <option value="individual">个人</option>
              </select>
              @elseif($devotee->type == 'individual')
              <select class="form-control type" name="type[]">
                <option value="sameaddress">合家</option>
                <option value="individual" selected>个人</option>
              </select>
              @elseif($devotee->type == 'home')
              <select class="form-control type" name="type[]">
                <option value="sameaddress">合家</option>
                <option value="individual" selected>个人</option>
              </select>
              @elseif($devotee->type == 'company')
              公司
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="company" selected>公司</option>
              </select>
              @elseif($devotee->type == 'stall')
              小贩
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="stall" selected>小贩</option>
              </select>
              @elseif($devotee->type == 'office')
              个人
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="individual" selected>个人</option>
              </select>
              @elseif($devotee->type == 'car')
              车辆
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="car" selected>车辆</option>
              </select>
              @else
              船只
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="ship" selected>船只</option>
              </select>
              @endif
            </td>
            <td>{{ $devotee->item_description }}</td>
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

          @endif

          @foreach($xiaozai_setting_samefamily as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="same xiaozai_id" name="xiaozai_id[]"
              value="1" <?php if ($devotee->xiaozai_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_xiaozai_id" name="hidden_xiaozai_id[]"
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
            <td>{{ $devotee->ops }}</td>
            <td>
              @if($devotee->type == 'sameaddress')
              <select class="form-control type" name="type[]">
                <option value="sameaddress" selected>合家</option>
                <option value="individual">个人</option>
              </select>
              @elseif($devotee->type == 'individual')
              <select class="form-control type" name="type[]">
                <option value="sameaddress">合家</option>
                <option value="individual" selected>个人</option>
              </select>
              @elseif($devotee->type == 'home')
              宅址
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="home" selected>宅址</option>
              </select>
              @elseif($devotee->type == 'company')
              公司
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="company" selected>公司</option>
              </select>
              @elseif($devotee->type == 'stall')
              小贩
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="stall" selected>小贩</option>
              </select>
              @elseif($devotee->type == 'office')
              办公址
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="office" selected>办公址</option>
              </select>
              @elseif($devotee->type == 'car')
              车辆
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="car" selected>车辆</option>
              </select>
              @else
              船只
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="ship" selected>船只</option>
              </select>
              @endif
            </td>
            <td>{{ $devotee->item_description }}</td>
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

    @php $this_year = date('Y'); @endphp

    <div class="form-group">

      @if(count($xiaozai_setting_samefamily_last1year) > 0)

      @if(Session::has('focus_devotee'))
      <h5 style="font-weight: bold;">
        <span class="setting-history">XZ-{{$this_year - 1}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>
      @endif

      <table class="table table-bordered" id="same_familycode_table">
        <thead>
          <tr>
            <th>#</th>
            <th>Chinese Name</th>
            <th>Devotee#</th>
            <th>RegisterBy</th>
            <th>Guiyi ID</th>
            <th>GY</th>
            <th>OPS</th>
            <th>Type</th>
            <th>Item Description</th>
            <th>M.Paid Till</th>
            <th>Paid By</th>
            <th>Last Trans</th>
          </tr>
        </thead>

        <tbody id="has_session">
          @foreach($xiaozai_setting_samefamily_last1year as $devotee)
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
              <input type="hidden" value="{{ $devotee->devotee_id }}">
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
            <td>{{ $devotee->ops }}</td>
            <td>
              @if($devotee->type == 'sameaddress')
              合家
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="sameaddress" selected>合家</option>
                <option value="individual">个人</option>
              </select>
              @elseif($devotee->type == 'individual')
              个人
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="sameaddress">合家</option>
                <option value="individual" selected>个人</option>
              </select>
              @elseif($devotee->type == 'home')
              宅址
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="home" selected>宅址</option>
              </select>
              @elseif($devotee->type == 'company')
              公司
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="company" selected>公司</option>
              </select>
              @elseif($devotee->type == 'stall')
              小贩
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="stall" selected>小贩</option>
              </select>
              @elseif($devotee->type == 'office')
              办公址
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="office" selected>办公址</option>
              </select>
              @elseif($devotee->type == 'car')
              车辆
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="car" selected>车辆</option>
              </select>
              @else
              船只
              <select class="form-control type" name="type[]" style="display: none;">
                <option value="ship" selected>船只</option>
              </select>
              @endif
            </td>
            <td>{{ $devotee->item_description }}</td>
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
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="xiaozai_focusdevotee_id">
      @else
        <input type="hidden" name="focusdevotee_id" id="xiaozai_focusdevotee_id">
      @endif
    </div><!-- end form-group -->

    <div class="form-actions">
        <button type="submit" class="btn blue" id="update_xiaozai_sameaddr_btn">Confirm</button>
        <button type="reset" class="btn default" id="cancel_samefamily_btn">Cancel</button>
    </div><!-- end form-actions -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->
