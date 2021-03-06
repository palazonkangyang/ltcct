<div class="col-md-3">

    <div class="inbox-sidebar">

        @if(Session::has('focus_devotee'))

            @php $focus_devotee = Session::get('focus_devotee'); @endphp

            @if(count($focus_devotee) > 1)

            <div class="row" id="no_session">

                <div class="col-md-12">
                    <h4>Focus Devotee 焦点善信</h4>
                </div><!-- end col-md-12 -->

            </div><!-- end row -->

            <form class="form-horizontal form-bordered" id="focus_devotee_form" method="post"
                action="{{ URL::to('/operator/focus-devotee') }}">

                {!! csrf_field() !!}

                <div class="row">

                    <div class="col-md-12">

                      <div class="col-md-3">

                          <div class="form-group">
                              <label>By Person</label>
                          </div><!-- end form-group -->

                      </div><!-- end col-md-3 -->

                      <div class="col-md-9">
                          <div class="form-group">
                              <label>Chinese Name</label>
                              <input type="text" class="form-control" name="chinese_name" value="{{ old('chinese_name') }}">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>English Name</label>
                              <input type="text" class="form-control" name="english_name" value="{{ old('english_name') }}">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Devotee ID</label>
                              <input type="number" class="form-control" name="devotee_id" value="{{ old('devotee_id') }}" min="1">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Member ID</label>
                              <input type="number" class="form-control" name="member_id" value="{{ old('member_id') }}" min="1">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Bridging ID</label>
                              <input type="number" class="form-control" name="bridging_id" value="{{ old('bridging_id') }}" min="1">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Family Code</label>
                              <input type="text" class="form-control" name="familycode" value="{{ old('familycode') }}">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>NRIC No</label>
                              <input type="text" class="form-control" name="nric" value="{{ old('nric') }}">
                          </div><!-- end form-group -->

                      </div><!-- end col-md-9 -->
                    </div><!-- end col-md-12 -->

                </div><!-- end row -->

                <div class="row">

                    <div class="col-md-12">
                      <div class="col-md-3">

                          <div class="form-group">
                              <label>By Address</label>
                          </div><!-- end form-group -->
                      </div><!-- end col-md-3 -->

                      <div class="col-md-9">

                          <div class="form-group">
                              <label>Street Name</label>
                              <input type="text" class="form-control" name="address_street" value="{{ old('address_street') }}" id="address_street">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>House/ Block No</label>
                              <input type="text" class="form-control" name="address_houseno" value="{{ old('address_houseno') }}">
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Unit</label>

                              <div class="clearfix">
                              </div>

                              <div style='width:30;float:left;'>
                                <input type="text" class="form-control" name="address_unit1" value="{{ old('address_unit1') }}" maxlength="3">
                              </div>

                              <label style='width:7;float:left;' class="separator">-</label>

                              <div style='width:40;float:left;'>
                                <input type="text" class="form-control" name="address_unit2" value="{{ old('address_unit2') }}" maxlength="3">
                              </div>

                              <div class="clearfix">
                              </div>
                          </div><!-- end form-group -->

                          <div class="form-group">
                              <label>Postal Code</label>
                              <input type="text" class="form-control" name="address_postal" value="{{ old('address_postal') }}">
                          </div><!-- end form-group -->

                      </div><!-- end col-md-9 -->
                    </div><!-- end col-md-12 -->

                </div><!-- end row -->

                <div class="row">

                    <div class="col-md-12">
                      <div class="col-md-3">

                          <div class="form-group">
                              <label>By Contact</label>
                          </div><!-- end form-group -->

                      </div><!-- end col-md-3 -->

                      <div class="col-md-9">

                          <div class="form-group">
                              <label>Phone No</label>
                              <input type="text" class="form-control" name="contact" value="{{ old('contact') }}">
                          </div><!-- end form-group -->

                      </div><!-- end col-md-9 -->
                    </div><!-- end col-md-12 -->

                </div><!-- end row -->

            @else

            <div class="row" id="has_session">

                <div class="col-md-12">
                    <h4>Focus Devotee 焦点善信</h4>
                </div><!-- end col-md-12 -->

                <div class="col-md-12">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-12">Devotee ID : <br />
                                  @if(isset($focus_devotee[0]->specialremarks_id))
                                    <span id="devodee_id" class="text-danger">{{ $focus_devotee[0]->devotee_id }}</span>
                                  @else
                                    <span id="devodee_id">{{ $focus_devotee[0]->devotee_id }}</span>
                                  @endif
                                </label>
                            </div><!-- end form-group -->

                            <div class="form-group">
                                <label class="col-md-12">Member ID : <br />
                                  <span id="member_id">{{ $focus_devotee[0]->member }}</span>
                                </label>
                            </div><!-- end form-group -->

                        </div><!-- end col-md-6 -->

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-12">Family Code : <br />
                                  <span id="family_code">{{ $focus_devotee[0]->familycode }}</span>
                                </label>
                            </div><!-- end form-group -->

                            <div class="form-group">
                                <label class="col-md-12">Bridging ID : <br />
                                  <span id="bridging_id">0</span>
                                </label>
                            </div><!-- end form-group -->

                        </div><!-- end col-md-6 -->
                    </div><!-- end row -->

                    <div class="clearfix">
                    </div><!-- end clearfix -->

                    @if(Session::has('focusdevotee_specialremarks'))

                    @php $focusdevotee_specialremarks = Session::get('focusdevotee_specialremarks');
                    @endphp

                      @if($focusdevotee_specialremarks[0]->specialremarks_id != null)

                        <div class="form-group">
                          <label class="col-md-12"><b>Special Remarks</b></label>

                          @foreach($focusdevotee_specialremarks as $specialremark)
                          <span class="text-danger" style="display: block; margin-bottom: 10px;">{{ $specialremark->data }}</span>
                          @endforeach
                        </div><!-- end col-md-12 -->

                      @endif

                    @endif

                </div><!-- end col-md-12 -->

            </div><!-- end row -->

            <form class="form-horizontal form-bordered" id="focus_devotee_form" method="post"
                action="{{ URL::to('/operator/focus-devotee') }}">

                {!! csrf_field() !!}

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group">
                        <label class="col-md-4">Title</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="title" id="title" value="{{ $focus_devotee[0]->title }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Chinese Name</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="chinese_name" id="chinese_name"
                                value="{{ $focus_devotee[0]->chinese_name }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">English Name</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="english_name" id="english_name"
                                value="{{ $focus_devotee[0]->english_name }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Guiyi Name</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="guiyi_name" id="guiyi_name" value="{{ $focus_devotee[0]->guiyi_name }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Contact #</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="contact" id="contact" value="{{ $focus_devotee[0]->contact }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Addr - House No</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="address_houseno" id="focus_address_houseno" value="{{ $focus_devotee[0]->address_houseno }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Addr - Street</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="address_street" id="focus_address_street"
                                value="{{ $focus_devotee[0]->address_street }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Addr - Unit</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="adress_unit" id="focus_address_unit"
                                value="{{ $focus_devotee[0]->address_unit1 }} - {{ $focus_devotee[0]->address_unit2 }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Addr - Postal</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="address_postal" id="focus_address_postal"
                                value="{{ $focus_devotee[0]->address_postal }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4"></label>

                        <div class="col-md-8">
                            <input type="hidden" class="form-control" name="" id="focus_address_translate"
                                value="{{ $focus_devotee[0]->address_translated }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Other Addr - Chinese</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="oversea_addr_in_chinese" id="focus_oversea_addr_in_chinese"
                                value="{{ $focus_devotee[0]->oversea_addr_in_chinese }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Nationality</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="nationality" id="nationality"
                                value="{{ $focus_devotee[0]->country_name }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Deceased</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="deceased_year" id="deceased_year"
                                value="{{ $focus_devotee[0]->deceased_year }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Date of Birth</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="dob" id="dob"
                              value="{{ $focus_devotee[0]->dob }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Marital Status</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="marital_status" id="marital_status"
                                value="{{ $focus_devotee[0]->marital_status }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Dialect</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="dialect" id="dialect"
                                value="{{ $focus_devotee[0]->dialect_name }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Introduced By - 1</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="introduced_by1" id="introduced_by1"
                                value="{{ $focus_devotee[0]->introduced_by1 }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Introduced By - 2</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="introduced_by2" id="introduced_by2"
                                value="{{ $focus_devotee[0]->introduced_by2 }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Approval Date</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="approved_date" id="approved_date"
                                value="{{ $focus_devotee[0]->approved_date }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                    <div class="form-group">
                        <label class="col-md-4">Mailer (Y/N)</label>

                        <div class="col-md-8">
                            <input type="text" class="form-control" name="mailer" value="{{ $focus_devotee[0]->mailer }}">
                        </div><!-- end col-md-8 -->
                    </div><!-- end form-group -->

                </div><!-- end col-md-12 -->
            </div><!-- end row -->

            @endif

        @else

        <div class="row" id="no_session">

            <div class="col-md-12">
                <h4>Focus Devotee 焦点善信</h4>
            </div><!-- end col-md-12 -->

        </div><!-- end row -->

        <form class="form-horizontal form-bordered" id="focus_devotee_form" method="post"
            action="{{ URL::to('/operator/focus-devotee') }}">

            {!! csrf_field() !!}

            <div class="row">

                <div class="col-md-12">

                  <div class="col-md-3">

                      <div class="form-group">
                          <label>By Person</label>
                      </div><!-- end form-group -->

                  </div><!-- end col-md-3 -->

                  <div class="col-md-9">
                      <div class="form-group">
                          <label>Chinese Name</label>
                          <input type="text" class="form-control" name="chinese_name" value="{{ old('chinese_name') }}">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>English Name</label>
                          <input type="text" class="form-control" name="english_name" value="{{ old('english_name') }}">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Devotee ID</label>
                          <input type="number" class="form-control" name="devotee_id" value="{{ old('devotee_id') }}" min="1">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Member ID</label>
                          <input type="number" class="form-control" name="member_id" value="{{ old('member_id') }}" min="1">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Bridging ID</label>
                          <input type="number" class="form-control" name="bridging_id" value="{{ old('bridging_id') }}" min="1">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Family Code</label>
                          <input type="text" class="form-control" name="familycode" value="{{ old('familycode') }}">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>NRIC No</label>
                          <input type="text" class="form-control" name="nric" value="{{ old('nric') }}">
                      </div><!-- end form-group -->

                  </div><!-- end col-md-9 -->
                </div><!-- end col-md-12 -->

            </div><!-- end row -->

            <div class="row">

                <div class="col-md-12">
                  <div class="col-md-3">

                      <div class="form-group">
                          <label>By Address</label>
                      </div><!-- end form-group -->
                  </div><!-- end col-md-3 -->

                  <div class="col-md-9">

                      <div class="form-group">
                          <label>Street Name</label>
                          <input type="text" class="form-control" name="address_street" value="{{ old('address_street') }}" id="address_street">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>House/ Block No</label>
                          <input type="text" class="form-control" name="address_houseno" value="{{ old('address_houseno') }}">
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Unit</label>

                          <div class="clearfix">
                          </div>

                          <div style='width:30%;float:left;'>
                            <input type="text" class="form-control" name="address_unit1" value="{{ old('address_unit1') }}" maxlength="3">
                          </div>

                          <label style='width:7%;float:left;' class="separator">-</label>

                          <div style='width:40%;float:left;'>
                            <input type="text" class="form-control" name="address_unit2" value="{{ old('address_unit2') }}" maxlength="3">
                          </div>

                          <div class="clearfix">
                          </div>
                      </div><!-- end form-group -->

                      <div class="form-group">
                          <label>Postal Code</label>
                          <input type="text" class="form-control" name="address_postal" value="{{ old('address_postal') }}">
                      </div><!-- end form-group -->

                  </div><!-- end col-md-9 -->
                </div><!-- end col-md-12 -->

            </div><!-- end row -->

            <div class="row">

                <div class="col-md-12">
                  <div class="col-md-3">

                      <div class="form-group">
                          <label>By Contact</label>
                      </div><!-- end form-group -->

                  </div><!-- end col-md-3 -->

                  <div class="col-md-9">

                      <div class="form-group">
                          <label>Phone No</label>
                          <input type="text" class="form-control" name="contact" value="{{ old('contact') }}" id="phone_no">
                      </div><!-- end form-group -->

                  </div><!-- end col-md-9 -->
                </div><!-- end col-md-12 -->

            </div><!-- end row -->

        @endif

        <div class="col-md-12" style="margin-bottom: 10px;">

          <div class="form-group" style="margin:0 auto;">
           <button type="button" class="btn default" id="new_same_familycode"> New Devotee with same FC
           </button>
          </div><!-- end form-group -->

        </div><!-- end col-md-12 -->

        <div class="col-md-6">

          <div class="form-group pull-right">
           <button type="button" class="btn default" style="margin-right: 25px; width:100px;" id="new"> New
           </button>
          </div><!-- end form-group -->

        </div><!-- end col-md-6 -->

        <div class="col-md-6">

          <div class="form-group pull-right">
           <button type="submit" class="btn default" id="quick_search" style="width: 110px;"> Quick Search
           </button>
          </div><!-- end form-group -->

        </div><!-- end col-md-6 -->

        </form>

        <div class="clearfix"></div><!-- end clearfix -->

        <div class="col-md-6">
        </div><!-- end col-md-6 -->

        <div class="col-md-6">

          <form class="form-horizontal form-bordered" id="focus_devotee_form" method="post"
              action="{{ URL::to('/operator/devotee/new-search') }}">

              {!! csrf_field() !!}

          <div class="form-group pull-right">
              <button type="submit" class="btn default" id="new_search" style="width: 110px;">New Search</button>
          </div>

          </form>

        </div><!-- end col-md-6 -->

        <div class="clearfix">
        </div><!-- end clearfix -->

    </div><!-- end inbox-sidebar -->

</div><!-- end col-md-3 -->
