<div class="form-body">
  <form method="post" action="{{ URL::to('/staff/differentfamily-setting') }}"
    class="form-horizontal form-bordered" id="differentfamily_form">

    {!! csrf_field() !!}

    <div class="form-group">

      <table class="table table-bordered" id="different_familycode_table">
        <thead>
            <tr>
              <th></th>
              <th>香/ 慈</th>
              <th>月捐</th>
              <th>Chinese Name</th>
              <th>Devotee#</th>
              <th>Member#</th>
              <th>Address</th>
              <th>Guiyi Name</th>
              <th>Contact</th>
              <th>Paid Till</th>
              <th>Mailer</th>
              <th>Last Trans</th>
              <th>Family Code</th>
            </tr>
        </thead>

        <tbody id="appendDifferentFamilyCodeTable">

          @if(Session::has('setting_differentfamily'))

          @php
            $setting_differentfamily = Session::get('setting_differentfamily');
            $focus_devotee = Session::get('focus_devotee');
            $setting_generaldonation = Session::get('setting_generaldonation');
          @endphp

          @foreach($setting_differentfamily as $devotee)
          <tr>
            <td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>
            <td class="checkbox-col">
              <input type="checkbox" class="same xiangyou_ciji_id" name="xiangyou_ciji_id[]"
              value="1" <?php if ($devotee->xiangyou_ciji_id == '1'){ ?>checked="checked"<?php }?>>
              <input type="hidden" class="form-control hidden_xiangyou_ciji_id" name="hidden_xiangyou_ciji_id[]"
              value="">
            </td>
            <td class="checkbox-col">
              <input type="checkbox" class="same yuejuan_id" name="yuejuan_id[]"
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
              <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}" class="append-devotee-id">
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

          @else

          <tr>
            <td colspan="12">No Result Found</td>
          </tr>

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
