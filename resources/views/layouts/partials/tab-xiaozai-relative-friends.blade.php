@php
  $relative_and_friends = Session::get('relative_and_friends')['xiaozai'];

  $xiaozai_setting_differentfamily_last1year = Session::get('xiaozai_setting_differentfamily_last1year');

  $focus_devotee = Session::get('focus_devotee');
@endphp

<div class="form-body">

  <form method="post" action="{{ URL::to('/fahui/xiaozai-differentfamily-setting') }}"
    class="form-horizontal form-bordered" id="xiaozai_differentfamily_form">

    {!! csrf_field() !!}

    <div class="form-group">

      <table class="table table-bordered" id="different_xiaozai_familycode_table">
        <thead>
          <tr>
            <th></th>
            <th>#</th>
            <th width="120px">Chinese Name</th>
            <th width="80px">Devotee#</th>
            <th width="80px">RegisterBy</th>
            <th>Guiyi ID</th>
            <th>GY</th>
            <th>OPS</th>
            <th>Type</th>
            <th width="170px">Item Description</th>
            <th width="80px">Paid By</th>
            <th>Trans Date</th>
          </tr>
        </thead>

        <tbody id="appendDifferentFamilyCodeTable">

          @if(Session::has('relative_and_friends'))

          @foreach($relative_and_friends as $index=>$devotee)
          <input type="hidden" name="mod_id" value=5>
          <input type="hidden" name="raf_id[]" value="{{$devotee->raf_id}}">
          <input type="hidden" class="form-control hidden_xiaozai_id" name="hidden_xiaozai_id[]">
          <tr>
            @if($index == 0)
            <td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>
            @elseif($index != 0)
            <td></td>
            @endif
            <td class='checkbox-col'>
              <input type='checkbox' class='xiaozai_id checkbox-multi-select-module-xiaozai-tab-raf-section-raf' name='xiaozai_id[]' value='1' <?php if ($devotee->is_checked == '1'){ ?>checked='checked'<?php }?>>
              <input type='hidden' class='hidden_xiaozai_id' name='is_checked[]' value=''>
            </td>
            <td>
              @if($devotee->deceased_year != null)
              <span class="text-danger">{{ $devotee->chinese_name }}</span>
              @else
              <span>{{ $devotee->chinese_name }}</span>
              @endif
            </td>
            <td>
              <input type="hidden" name="devotee_id[]" class="append-devotee-id" value="{{ $devotee->devotee_id }}">
              @if($devotee->specialremarks_devotee_id == null)
              <span>{{ $devotee->devotee_id }}</span>
              @else
              <span class="text-danger">{{ $devotee->devotee_id }}</span>
              @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $devotee->ops }}</td>
            <td>
              @if($devotee->type == 'base_home')
              {{ Form::select('hjgr[]', ['hj' => '合家','gr' => '个人'],$devotee->hjgr) }}
              @elseif($devotee->type == 'home')
              {{ Form::select('hjgr[]', ['hj' => '合家','gr' => '个人'],$devotee->hjgr) }}
              @elseif($devotee->type == 'company')
              公司
              {{ Form::hidden('hjgr[]','')}}
              @elseif($devotee->type == 'stall')
              小贩
              {{ Form::hidden('hjgr[]','')}}
              @elseif($devotee->type == 'office')
              个人
              {{ Form::hidden('hjgr[]','gr')}}
              @elseif($devotee->type == 'car')
              车辆
              {{ Form::hidden('hjgr[]','')}}
              @elseif($devotee->type == 'ship')
              船只
              {{ Form::hidden('hjgr[]','')}}
              @else
              {{ Form::hidden('hjgr[]','')}}
              @endif
            </td>
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

    <div class="col-md-4">

      <div class="form-group">
        <label class="col-md-5">Devotee ID</label>
        <div class="col-md-7">
            <input type="text" class="form-control" id="search_devotee_id">
        </div><!-- end col-md-7 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-5">Member ID</label>
        <div class="col-md-7">
            <input type="text" class="form-control" id="search_member_id">
        </div><!-- end col-md-7 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-5">Chinese Name</label>
        <div class="col-md-7">
            <input type="text" class="form-control" id="search_chinese_name">
        </div><!-- end col-md-7 -->
      </div><!-- end form-group -->

      <div class="form-actions">
          <button type="button" class="btn default" id="insert_devotee" style="padding: 7px; 15px;">Insert</button>
          <button type="button" class="btn default" id="search_detail_btn" style="padding: 7px; 15px;">Detail</button>
      </div><!-- end form-actions -->

    </div><!-- end col-md-4 -->

    <div class="col-md-2">

      <table class="table table-bordered" id="search_devotee_lists">
        <tbody>
          <tr class="no-record">
            <td>No Result Found!</td>
          </tr>
        </tbody>
      </table>

    </div><!-- end col-md-2 -->

    <div class="col-md-6">

      <div class="col-md-12">
        <div class="form-group">
          <label class="col-md-3">Title</label>
          <div class="col-md-6">
              <input type="text" class="form-control" id="search_title">
          </div><!-- end col-md-6 -->
          <label class="col-md-3">Devotee ID</label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Chinese Name</label>
          <div class="col-md-6">
              <input type="text" class="form-control" id="searchby_chinese_name">
          </div><!-- end col-md-6 -->
          <div class="col-md-3">
              <input type="text" class="form-control" id="searchby_devotee_id">
          </div><!-- end col-md-3 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">English Name</label>
          <div class="col-md-6">
              <input type="text" class="form-control" id="search_english_name">
          </div><!-- end col-md-6 -->
          <label class="col-md-3">Member ID</label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Guiyi Name</label>
          <div class="col-md-6">
              <input type="text" class="form-control" id="search_guiyi_name">
          </div><!-- end col-md-6 -->
          <div class="col-md-3">
              <input type="text" class="form-control" id="searchby_member_id">
          </div><!-- end col-md-3 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Contact#</label>
          <div class="col-md-6">
              <input type="text" class="form-control" id="search_contact">
          </div><!-- end col-md-8 -->
        </div><!-- end form-group -->
      </div><!-- end col-md-12 -->

      <div class="col-md-12">

        <div class="form-group">
          <label class="col-md-3">Addr - House no</label>
          <div class="col-md-3">
              <input type="text" class="form-control" id="search_address_houseno">
          </div><!-- end col-md-4 -->

          <label class="col-md-1">Unit</label>
          <div class="col-md-5">
              <input type="text" class="form-control" id="search_address_unit">
          </div><!-- end col-md-4 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Address - Street</label>
          <div class="col-md-9">
              <input type="text" class="form-control" id="search_address_street">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Address - Postal</label>
          <div class="col-md-3">
              <input type="text" class="form-control" id="search_address_postal">
          </div><!-- end col-md-3 -->

          <label class="col-md-2">Country</label>
          <div class="col-md-4">
              <input type="text" class="form-control" id="search_country">
          </div><!-- end col-md-3 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3">Oversea Addr in Chinese</label>
          <div class="col-md-9">
              <input type="text" class="form-control" id="search_oversea_addr_in_chinese">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

      </div><!-- end col-md-12 -->

    </div><!-- end col-md-6 -->

    <div class="clearfix">
    </div><!-- end clearfix -->

    <p><br /><br /></p>

    <p></p>

    @php $this_year = date('Y'); @endphp

    <div class="form-group">

      @if(count($xiaozai_setting_differentfamily_last1year) > 0)

      @if(Session::has('focus_devotee'))
      <h5 style="font-weight: bold;">
        <span class="setting-history">XZ-{{$this_year - 1}}-FC{{ $focus_devotee[0]->devotee_id }}</span>
      </h5>
      @endif

      <table class="table table-bordered xiaozai_history_table">
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

        <tbody id="has_session">
          @foreach($xiaozai_setting_differentfamily_last1year as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" name="" value="{{ $devotee->devotee_id }}">
            </td>
            <td>
              @if($devotee->deceased_year != null)
              <span class="text-danger">{{ $devotee->chinese_name }}</span>
              @else
              <span>{{ $devotee->chinese_name }}</span>
              @endif
            </td>
            <td>
              <input type="hidden" value="{{ $devotee->devotee_id }}" class="xiaozai-history-id">
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
      <div class="form-actions" style="margin-left: 20px;">
        <p>
          To INSERT records from the below Tick List, tick on the records and Click on the ADD FROM TICK LIST Button.
        </p>

        <button id="add_trick_list" type="button" name="button" class="btn blue">Add from Tick List</button>
      </div>
    </div><!-- end form-group -->

    <p><br /><br /></p>

    @php $focus_devotee = Session::get('focus_devotee'); @endphp

    <div class="form-group">
      @if(count($focus_devotee) > 0)
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="focusdevotee_id">
      @else
        <input type="hidden" name="focusdevotee_id" value="" id="focusdevotee_id">
      @endif
    </div><!-- end form-group -->

    <div class="form-actions">
        <button type="submit" class="btn blue" id="update_xiaozai_differentaddr_btn">Update</button>
        <button type="button" class="btn default" id="cancel_xiaozai_differentaddr_btn">Cancel</button>
    </div><!-- end form-actions -->

    <div class="clearfix"></div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->
