@php
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
            <td>No Result Record!</td>
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

        <button type="button" name="button" class="btn blue">Add from Trick List</button>
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
