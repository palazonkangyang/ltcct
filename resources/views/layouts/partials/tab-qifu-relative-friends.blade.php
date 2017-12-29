@php
  $relative_and_friends = Session::get('relative_and_friends')['qifu'];
@endphp

<div class="form-body">

  <form method="post" action="{{ URL::to('/fahui/qifu-differentfamily-setting') }}"
  class="form-horizontal form-bordered" id="qifu_differentfamily_form">

  {!! csrf_field() !!}

  <div class="form-group">

    <table class="table table-bordered" id="different_qifu_familycode_table">
      <thead>
        <tr>
          <th>#</th>
          <th>#</th>
          <th width="120px">Chinese Name</th>
          <th width="80px">Devotee#</th>
          <th width="80px">RegisterBy</th>
          <th>Guiyi ID</th>
          <th>GY</th>
          <th width="170px">Item Description</th>
          <th width="100px">M.Paid Till</th>
          <th width="80px">Paid By</th>
          <th>Trans Date</th>
        </tr>
      </thead>

      <tbody id="appendDifferentFamilyCodeTable">

        @if(Session::has('qifu_setting_differentfamily'))

        @foreach($relative_and_friends as $devotee)
        <tr>
          <td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>
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
            <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}" class="append-devotee-id">
            @if($devotee->specialremarks_devotee_id == null)
            <span>{{ $devotee->devotee_id }}</span>
            @else
            <span class="text-danger">{{ $devotee->devotee_id }}</span>
            @endif
          </td>
          <td></td>
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

  <div class="form-group">
    <div class="form-actions" style="margin-left: 20px;">
      <p>
        To INSERT records from the below Tick List, tick on the records and Click on the ADD FROM TICK LIST Button.
      </p>

      <button type="button" name="button" class="btn blue" id="add_trick_list">Add from Tick List</button>
    </div>
  </div><!-- end form-group -->

  @php $focus_devotee = Session::get('focus_devotee'); @endphp

  <div class="form-group">
    @if(count($focus_devotee) > 0)
    <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="focusdevotee_id">
    @else
    <input type="hidden" name="focusdevotee_id" value="" id="focusdevotee_id">
    @endif
  </div><!-- end form-group -->

  <div class="form-actions">
    <button type="submit" class="btn blue" id="update_differentaddr_btn">Update</button>
    <button type="button" class="btn default" id="cancel_differentaddr_btn">Cancel</button>
  </div><!-- end form-actions -->

  <div class="clearfix"></div><!-- end clearfix -->

</form>

</div><!-- end form-body -->
