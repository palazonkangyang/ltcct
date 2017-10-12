@php
  $focus_devotee = Session::get('focus_devotee');
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
            <th>Item Description</th>
            <th>M.Paid Till</th>
            <th>Paid By</th>
            <th>Last Trans</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td colspan="10">No Result Found</td>
          </tr>
        </tbody>

      </table>

    </div><!-- end form-group -->

    <div class="form-group">
      @if(count($focus_devotee) > 0)
        <input type="hidden" name="focusdevotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="kongdan_focusdevotee_id">
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
