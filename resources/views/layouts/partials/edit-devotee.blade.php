
	<form method="post" action="{{ URL::to('/operator/edit-devotee') }}"
        class="form-horizontal form-bordered">

        {!! csrf_field() !!}

        <div class="form-body">

            <div class="col-md-6">

                <div class="form-group">
                    <input type="hidden" name="devotee_id" value="" id="edit_devotee_id">
                    <input type="hidden" name="familycode_id" value="" id="edit_familycode_id">
                    <input type="hidden" name="member_id" value="" id="edit_member_id">
                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Title</label>
                    <div class="col-md-9">
                        <select class="form-control" name="title">
                            <option value="mr">Mr</option>
                            <option value="miss">Miss</option>
                            <option value="madam">Madam</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Chinese Name *</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="chinese_name" value="{{ old('chinese_name') }}"
                            id="edit_chinese_name">
                        </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">English Name *</label>
                    <div class="col-md-9">
                       	<input type="text" class="form-control" name="english_name" value="{{ old('english_name') }}" id="edit_english_name">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Contact # *</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="contact" value="{{ old('contact') }}" id="edit_contact">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Guiyi Name *</label>
                    <div class="col-md-9">
                       	<input type="text" class="form-control" name="guiyi_name" value="{{ old('guiyi_name') }}" id="edit_guiyi_name">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Address - House No</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="address_houseno" value="{{ old('address_houseno') }}"
                            id="edit_address_houseno">
                    </div><!-- end col-md-3 -->

                    <label class="col-md-1 control-label">Unit</label>

                    <div class="col-md-2">
                        <input type="text" class="form-control" name="address_unit1" value="{{ old('address_unit1') }}"
                            id="edit_address_unit1">
                    </div><!-- end col-md-2 -->

                    <label class="col-md-1">-</label>

                    <div class="col-md-2">
                        <input type="text" class="form-control" name="address_unit2" value="{{ old('address_unit2') }}"
                            id="edit_address_unit2">
                    </div><!-- end col-md-2 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Address - Street</label>
                    <div class="col-md-9">
												<input type="text" class="form-control" name="address_street"
														value="{{ old('address_street') }}" id="edit_address_street">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Address - Building</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="address_building" value="{{ old('address_building') }}"
                            id="edit_address_building">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Address - Postal *</label>
                    <div class="col-md-9">
                       	<input type="text" class="form-control" name="address_postal" value="{{ old('address_postal') }}"
                            id="edit_address_postal">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Address - Translate</label>
                    <div class="col-md-9">
                            <input type="text" class="form-control" name="address_translated" id="edit_address_translated">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Oversea Addr in Chinese</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="oversea_addr_in_chinese" id="edit_oversea_addr_in_chinese">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3">
                        <button type="button" class="btn default edit_check_family_code">Check Family Code</button>
                    </label>
                    <div class="col-md-9">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

            </div><!-- end col-md-6 -->

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-md-3 control-label">NRIC</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="nric" value="{{ old('nric') }}" id="edit_nric">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Deceased Year</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control deceased_year" name="deceased_year" data-provide="datepicker"
                            value="{{ old('deceased_year') }}" id="edit_deceased_year">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Date of Birth</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="dob" data-provide="datepicker" value="{{ old('dob') }}" id="edit_dob">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Marital Status</label>
                    <div class="col-md-9">
                        <select class="form-control" name="marital_status" id="edit_marital_status">
                            <option value="">Please select</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Dialect</label>
                    <div class="col-md-9">
                        <select class="form-control" name="dialect" id="edit_dialect">
                            <option value="">Please select</option>
                            <option value="chinese">Chinese</option>
                            <option value="others">Others</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Race</label>
                    <div class="col-md-9">
                        <select class="form-control" name="race" id="edit_race">
                            <option value="">Please select</option>
                            <option value="chinese">Chinese</option>
                            <option value="others">Others</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Nationality</label>
                    <div class="col-md-9">
                        <select class="form-control" name="nationality" id="edit_nationality">
                            <option value="">Please select</option>
                            <option value="singapore">Singapore</option>
                            <option value="others">Others</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                        <div class="table-scrollable" id="edit-familycode-table">
                            <table class="table table-bordered table-hover">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Family Code</th>
                                    </tr>
                                </thead>

                            	<tbody>
                                    <tr id="edit_no_familycode">
                                        <td colspan="3">No Family Code</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Mailer</label>
                    <div class="col-md-2">
                        <select class="form-control" name="mailer">
                            <option value="">Yes</option>
                            <option>No</option>
                        </select>
                    </div><!-- end col-md-2 -->

                    <div class="col-md-4">
                    </div><!-- end col-md-4 -->

                </div><!-- end form-group -->

            </div><!-- end col-md-6 -->

            <div class="clearfix"></div>

            <hr>

            <h4>Optional</h4>

            <div class="col-md-6">

                <div id="opt_address">
                </div><!-- end opt_address -->

                <div class="form-group">

                    <div class="col-md-1"></div><!-- end col-md-1 -->

                    <div class="col-md-5">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="AddressBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6"></div><!-- end col-md-6 -->

                </div><!-- end form-group -->

                <div id="opt_vehicle">
                </div><!-- end opt_vehicle -->

                <div class="form-group">

                    <div class="col-md-1">
                    </div><!-- end col-md-1 -->

                    <div class="col-md-5">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="VehicleBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6">
                    </div><!-- end col-md-6 -->

                </div><!-- end form-group -->

                <div id="special_remark">
                </div><!-- end special_remark -->

                <div class="form-group">

                    <div class="col-md-1">
                    </div><!-- end col-md-1 -->

                    <div class="col-md-5">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="SpecRemarkBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6">
                    </div><!-- end col-md-6 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-12 control-label">
                        If you have made Changes to the above. You need to CONFIRM to save the Changes.<br />
                        To Confirm, please enter authorized password to proceed.
                    </label>

                </div><!-- end form-group -->

            </div><!-- end col-md-6 -->

            <div class="col-md-6">

                <div class="form-group">

                    <label class="col-md-3 control-label">Introduced By-1</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="introduced_by1" value="{{ old('introduced_by1') }}" id="edit_introduced_by1">
                   	</div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Introduced By-2</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="introduced_by2" value="{{ old('introduced_by2') }}" id="edit_introduced_by2">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Member Approved Date</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-inline date-picker" name="approved_date" data-provide="datepicker"
                            id="edit_approved_date">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Member Cancelled Date</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-inline date-picker" name="cancelled_date" data-provide="datepicker"
                            id="edit_cancelled_date">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-group">

                    <label class="col-md-3 control-label">Reason for Cancel</label>
                    <div class="col-md-9">
                        <select class="form-control" name="reason_for_cancel" id="edit_reason_for_cancel">
                            <option value="">Please select</option>
                            <option value="1">Deceased</option>
                            <option value="2">Self withdrawal</option>
                            <option value="3">Had been inactive for years</option>
                            <option value="4">Others</option>
                        </select>
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

               	<div class="form-group">
                    <label class="col-md-6"></label>
                    <label class="col-md-3 control-label">Authorized Password</label>
                    <div class="col-md-3">
                        <input type="password" class="form-control" name="authorized_password" id="authorized_password">
                    </div><!-- end col-md-9 -->

                </div><!-- end form-group -->

                <div class="form-actions pull-right">
                    <button type="submit" class="btn blue" id="update_btn" disabled>Update</button>
                    <button type="button" class="btn default">Cancel</button>
                </div><!-- end form-actions -->
            </div><!-- end col-md-6 -->

        </div><!-- end form-body -->

        <div class="clearfix"></div><!-- end clearfix -->

    </form>
