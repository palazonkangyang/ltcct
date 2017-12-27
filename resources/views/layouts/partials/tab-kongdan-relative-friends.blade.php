@php
$relative_and_friends = Session::get('relative_and_friends')['kongdan'];
$relative_and_friends_history = Session::get('relative_and_friends_history')['kongdan'];
$focus_devotee = Session::get('focus_devotee');

@endphp

<div class="form-body">

  <form method="post" action="{{ URL::to('/fahui/update-relative-and-friends-setting') }}"
  class="form-horizontal form-bordered" id="kongdan_differentfamily_form">

  {!! csrf_field() !!}
  <input type="hidden" name="mod_id" value=10>
  <div class="form-group">

    <table class="table table-bordered" id="different_kongdan_familycode_table">
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
          <th width="80px">Paid By</th>
          <th>Trans Date</th>
        </tr>
      </thead>

      <tbody id="appendDifferentFamilyCodeTable">

        @if(Session::has('relative_and_friends.kongdan'))

        @foreach($relative_and_friends as $devotee)
        <tr>
          <input type="hidden" name="raf_id[]" value="{{$devotee->raf_id}}">
          <td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true' data-devotee_id='{{ $devotee->devotee_id }}' ></i></td>
          <td class="checkbox-col">
            <input type="checkbox" class="kongdan_id checkbox-multi-select-module-kongdan-tab-raf-section-raf" name="kongdan_id[]" value="1" <?php if ($devotee->is_checked == '1'){ ?>checked="checked"<?php }?>>
            <input type="hidden" class="hidden_kongdan_id" name="is_checked[]" value="">
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

  <p><br /></p>

  @php $this_year = date('Y'); @endphp

  <div class="form-group">

    @if(Session::has('focus_devotee'))
    <h5 style="font-weight: bold;">
      Past Year Record
    </h5>
    @endif

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
        @if(isset($relative_and_friends_history))
          @foreach($relative_and_friends_history as $devotee)
          <tr>
            <td class="checkbox-col">
              <input type="checkbox" class="devotee_id_list checkbox-multi-select-module-kongdan-tab-raf-section-raf-history" name="devotee_id_list[]" value="{{ $devotee->devotee_id }}">
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

  <p><br /><br /></p>

  <div class="form-actions">
    <button type="submit" class="btn blue" id="update_kongdan_differentaddr_btn">Update</button>
    <button type="button" class="btn default" id="cancel_kongdan_differentaddr_btn">Cancel</button>
  </div><!-- end form-actions -->

  <div class="clearfix"></div><!-- end clearfix -->

</form>

</div><!-- end form-group -->
