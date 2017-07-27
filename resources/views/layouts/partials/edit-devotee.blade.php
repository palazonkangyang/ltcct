
	<form method="post" action="{{ URL::to('/operator/edit-devotee') }}"
        class="form-horizontal form-bordered">

        {!! csrf_field() !!}

        <div class="form-body">

					@if(Session::has('focus_devotee'))

			        @php $focus_devotee = Session::get('focus_devotee'); @endphp

			       @if(count($focus_devotee) > 1)

						 <div class="col-md-6">

							 <div class="form-group">
									 <input type="hidden" name="devotee_id" value="{{ old('devotee_id') }}" id="edit_devotee_id">
									 <input type="hidden" name="familycode_id" value="{{ old('familycode_id') }}" id="edit_familycode_id">
									 <input type="hidden" name="member_id" value="{{ old('member_id') }}" id="edit_member_id">
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

										 <label class="col-md-3 control-label">English Name</label>
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

										 <label class="col-md-3 control-label">Guiyi Name</label>
										 <div class="col-md-9">
												 <input type="text" class="form-control" name="guiyi_name" value="{{ old('guiyi_name') }}" id="edit_guiyi_name">
										 </div><!-- end col-md-9 -->

								 </div><!-- end form-group -->

								 <div class="form-group">

										 <label class="col-md-3 control-label">Address - House No *</label>
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

										 <label class="col-md-3 control-label">Address - Street *</label>
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
														 <input type="text" class="form-control" name="address_translated" id="edit_address_translated" readonly>
										 </div><!-- end col-md-9 -->

								 </div><!-- end form-group -->

								 <div class="form-group">

										 <label class="col-md-3 control-label">Oversea Addr in Chinese</label>
										 <div class="col-md-9">
												 <input type="text" class="form-control" name="oversea_addr_in_chinese" id="edit_oversea_addr_in_chinese">
										 </div><!-- end col-md-9 -->

								 </div><!-- end form-group -->

								 <div class="form-group">

										 <div class="col-md-8">
											 <button type="button" class="btn default edit_check_family_code" style="margin-right: 30px;">
													 Check Family Code
											 </button>

											 <button type="button" class="btn default edit_address_translated_btn">
													 Translate Address
											 </button>
										 </div><!-- end col-md-8 -->

										 <div class="col-md-4">
										 </div><!-- end col-md-4 -->

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
													 <input type="text" class="form-control" name="deceased_year" data-provide="datepicker"
															 value="{{ old('deceased_year') }}" id="edit_deceased_year">
											 </div><!-- end col-md-9 -->

									 </div><!-- end form-group -->

									 <div class="form-group">

											 <label class="col-md-3 control-label">Date of Birth</label>
											 <div class="col-md-9">
													 <input type="text" class="form-control" name="dob" data-provide="datepicker" data-date-format="dd/mm/yyyy" value="{{ old('dob') }}" id="edit_dob">
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
														 <option value="No">No</option>
														 <option value="Yes">Yes</option>
													 </select>
											 </div><!-- end col-md-2 -->

											 <div class="col-md-4">
											 </div><!-- end col-md-4 -->

									 </div><!-- end form-group -->

								 </div><!-- end col-md-6 -->

						 @else

						 <div class="col-md-6">

	 						<div class="form-group">
	 								<input type="hidden" name="devotee_id" value="{{ old('devotee_id') }}" id="edit_devotee_id">
	 								<input type="hidden" name="familycode_id" value="{{ old('familycode_id') }}" id="edit_familycode_id">
	 								<input type="hidden" name="member_id" value="{{ old('member_id') }}" id="edit_member_id">
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
	 											<input type="text" class="form-control" name="chinese_name" value="{{ $focus_devotee[0]->chinese_name }}"
	 													id="edit_chinese_name">
	 											</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">English Name</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="english_name" value="{{ $focus_devotee[0]->english_name }}" id="edit_english_name">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Contact # *</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="contact" value="{{ $focus_devotee[0]->contact }}" id="edit_contact">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Guiyi Name</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="guiyi_name" value="{{ $focus_devotee[0]->guiyi_name }}" id="edit_guiyi_name">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Address - House No *</label>
	 									<div class="col-md-3">
	 											<input type="text" class="form-control" name="address_houseno" value="{{ $focus_devotee[0]->address_houseno }}"
	 													id="edit_address_houseno">
	 									</div><!-- end col-md-3 -->

	 									<label class="col-md-1 control-label">Unit</label>

	 									<div class="col-md-2">
	 											<input type="text" class="form-control" name="address_unit1" value="{{ $focus_devotee[0]->address_unit1 }}"
	 													id="edit_address_unit1">
	 									</div><!-- end col-md-2 -->

	 									<label class="col-md-1">-</label>

	 									<div class="col-md-2">
	 											<input type="text" class="form-control" name="address_unit2" value="{{ $focus_devotee[0]->address_unit2 }}"
	 													id="edit_address_unit2">
	 									</div><!-- end col-md-2 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Address - Street *</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="address_street"
	 													value="{{ $focus_devotee[0]->address_street }}" id="edit_address_street">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Address - Building</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="address_building" value="{{ $focus_devotee[0]->address_building }}"
	 													id="edit_address_building">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Address - Postal *</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="address_postal" value="{{ $focus_devotee[0]->address_postal }}"
	 													id="edit_address_postal">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Address - Translate</label>
	 									<div class="col-md-9">
	 													<input type="text" class="form-control" name="address_translated" id="edit_address_translated"
														value="{{ $focus_devotee[0]->address_translated }}" readonly>
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-3 control-label">Oversea Addr in Chinese</label>
	 									<div class="col-md-9">
	 											<input type="text" class="form-control" name="oversea_addr_in_chinese" id="edit_oversea_addr_in_chinese"
												value="{{ $focus_devotee[0]->oversea_addr_in_chinese }}">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<div class="col-md-8">
	 										<button type="button" class="btn default edit_check_family_code" style="margin-right: 30px;">
	 												Check Family Code
	 										</button>

	 										<button type="button" class="btn default edit_address_translated_btn">
	 												Translate Address
	 										</button>
	 									</div><!-- end col-md-8 -->

	 									<div class="col-md-4">
	 									</div><!-- end col-md-4 -->

	 							</div><!-- end form-group -->

	 						</div><!-- end col-md-6 -->

							<div class="col-md-6">

									<div class="form-group">

										<label class="col-md-3 control-label">NRIC</label>
										<div class="col-md-9">
											 <input type="text" class="form-control" name="nric" value="{{ $focus_devotee[0]->nric }}" id="edit_nric">
										 </div><!-- end col-md-9 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-3 control-label">Deceased Year</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="deceased_year"
															value="{{ $focus_devotee[0]->deceased_year }}" id="edit_deceased_year">
											</div><!-- end col-md-9 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-3 control-label">Date of Birth</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="dob" data-provide="datepicker" data-date-format="dd/mm/yyyy"
													value="{{ $focus_devotee[0]->dob }}" id="edit_dob">
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
														<option value="No">No</option>
														<option value="Yes">Yes</option>
													</select>
											</div><!-- end col-md-2 -->

											<div class="col-md-4">
											</div><!-- end col-md-4 -->

									</div><!-- end form-group -->

								</div><!-- end col-md-6 -->

						@endif

					@else

					<div class="col-md-6">

						<div class="form-group">
								<input type="hidden" name="devotee_id" value="{{ old('devotee_id') }}" id="edit_devotee_id">
								<input type="hidden" name="familycode_id" value="{{ old('familycode_id') }}" id="edit_familycode_id">
								<input type="hidden" name="member_id" value="{{ old('member_id') }}" id="edit_member_id">
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

									<label class="col-md-3 control-label">English Name</label>
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

									<label class="col-md-3 control-label">Guiyi Name</label>
									<div class="col-md-9">
											<input type="text" class="form-control" name="guiyi_name" value="{{ old('guiyi_name') }}" id="edit_guiyi_name">
									</div><!-- end col-md-9 -->

							</div><!-- end form-group -->

							<div class="form-group">

									<label class="col-md-3 control-label">Address - House No *</label>
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

									<label class="col-md-3 control-label">Address - Street *</label>
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
													<input type="text" class="form-control" name="address_translated" id="edit_address_translated" readonly>
									</div><!-- end col-md-9 -->

							</div><!-- end form-group -->

							<div class="form-group">

									<label class="col-md-3 control-label">Oversea Addr in Chinese</label>
									<div class="col-md-9">
											<input type="text" class="form-control" name="oversea_addr_in_chinese" id="edit_oversea_addr_in_chinese">
									</div><!-- end col-md-9 -->

							</div><!-- end form-group -->

							<div class="form-group">

									<div class="col-md-8">
										<button type="button" class="btn default edit_check_family_code" style="margin-right: 30px;">
												Check Family Code
										</button>

										<button type="button" class="btn default edit_address_translated_btn">
												Translate Address
										</button>
									</div><!-- end col-md-8 -->

									<div class="col-md-4">
									</div><!-- end col-md-4 -->

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
												<input type="text" class="form-control" name="deceased_year" data-provide="datepicker"
														value="{{ old('deceased_year') }}" id="edit_deceased_year">
										</div><!-- end col-md-9 -->

								</div><!-- end form-group -->

								<div class="form-group">

										<label class="col-md-3 control-label">Date of Birth</label>
										<div class="col-md-9">
												<input type="text" class="form-control" name="dob" data-provide="datepicker" data-date-format="dd/mm/yyyy" value="{{ old('dob') }}" id="edit_dob">
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
													<option value="No">No</option>
													<option value="Yes">Yes</option>
												</select>
										</div><!-- end col-md-2 -->

										<div class="col-md-4">
										</div><!-- end col-md-4 -->

								</div><!-- end form-group -->

							</div><!-- end col-md-6 -->

					@endif

					<div class="clearfix"></div>

					<hr>

					<h4>Optional</h4>

          <div class="col-md-6">

						@if(Session::has('optionaladdresses'))

						@php $optionaladdresses = Session::get('optionaladdresses'); @endphp

								@if(count($optionaladdresses) > 0)

								<div id="opt_address">

								@foreach($optionaladdresses as $optAddress)

								<div class="form-group">

									<label class='col-md-3 control-label'>Opt.Address</label><!-- end col-md-3 -->

									<div class='col-md-3'>

										<select class='form-control' name='address_type[]'>
											<option value="home" <?php if ($optAddress->type == "home") echo "selected"; ?>>Home</option>
											<option value="company" <?php if ($optAddress->type == "company") echo "selected"; ?>>Company</option>
											<option value="stall" <?php if ($optAddress->type == "stall") echo "selected"; ?>>Stall</option>
											<option value="office" <?php if ($optAddress->type == "office") echo "selected"; ?>>Office</option>
										</select>

									</div><!-- end col-md-3 -->

									<div class='col-md-5'>
										<input type="text" class="form-control" name="address_data[]" value="{{ $optAddress->data }}">
									</div><!-- end col-md-5 -->

									<div class='col-md-1'>
										<i class='fa fa-minus-circle removeAddressBtn1' aria-hidden='true'></i>
									</div>

								</div><!-- end form-group -->

								@endforeach

								</div><!-- end opt_address -->

								@else

								<div id="opt_address">

									<div class="form-group">
										<label class='col-md-3 control-label'>Opt.Address</label><!-- end col-md-3 -->

										<div class='col-md-3'>
											<select class='form-control' name='address_type[]'>
												<option value="home">Home</option>
												<option value="company">Company</option>
												<option value="stall">Stall</option>
												<option value="office">Office</option>
											</select>
										</div><!-- end col-md-3 -->

										<div class='col-md-5'>
											<input type="text" class="form-control" name="address_data[]" value="">
										</div><!-- end col-md-5 -->

										<div class='col-md-1'>
										</div><!-- end col-md-1 -->
									</div><!-- end form-group -->

								</div><!-- end opt_address -->

								@endif
								@endif

								<div class="form-group">
                    <div class="col-md-1"></div><!-- end col-md-1 -->

                    <div class="col-md-5">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="AddressBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6"></div><!-- end col-md-6 -->
                </div><!-- end form-group -->


								@if(Session::has('optionalvehicles'))

								@php $optionalvehicles = Session::get('optionalvehicles'); @endphp

								@if(count($optionalvehicles) > 0)

								<div id="opt_vehicle">

								@foreach($optionalvehicles as $optVehicle)

								<div class='form-group'>
									<label class='col-md-3 control-label'>Opt.Vehicle</label><!-- end col-md-3 -->

									<div class='col-md-3'>
										<select class='form-control' name='vehicle_type[]'>
											<option value="car" <?php if ($optVehicle->type == "car") echo "selected"; ?>>Car</option>
											<option value="ship" <?php if ($optVehicle->type == "ship") echo "selected"; ?>>Ship</option>
										</select>
									</div><!-- end col-md-3 -->

									<div class='col-md-5'>
										<input type="text" class="form-control" name="vehicle_data[]" value="{{ $optVehicle->data }}">
									</div><!-- end col-md-5 -->

									<div class='col-md-1'>
										<i class='fa fa-minus-circle removeVehicleBtn1' aria-hidden='true'></i>
									</div><!-- end col-md-1 -->
								</div><!-- end form-group -->

								@endforeach

								</div><!-- end opt_vehicle -->

								@else

								<div id="opt_vehicle">

									<div class='form-group'>
										<label class='col-md-3 control-label'>Opt.Vehicle</label><!-- end col-md-3 -->

										<div class='col-md-3'>
											<select class='form-control' name='vehicle_type[]'>
												<option value="car">Car</option>
												<option value="ship">Ship</option>
											</select>
										</div><!-- end col-md-3 -->

										<div class='col-md-5'>
											<input type="text" class="form-control" name="vehicle_data[]" value="">
										</div><!-- end col-md-5 -->

										<div class='col-md-1'>
										</div><!-- end col-md-1 -->
									</div><!-- end form-group -->

								</div><!-- end opt_vehicle -->

								@endif
								@endif

                <div class="form-group">
                    <div class="col-md-1">
                    </div><!-- end col-md-1 -->

                    <div class="col-md-5">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="VehicleBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6">
                    </div><!-- end col-md-6 -->
                </div><!-- end form-group -->

								@if(Session::has('specialRemarks'))

								@php $specialRemarks = Session::get('specialRemarks'); @endphp

								@if(count($specialRemarks) > 0)

								<div id="special_remark">

								@foreach($specialRemarks as $specialRemark)

								<div class='form-group'>
									<label class='col-md-3 control-label'>Special Remarks</label><!-- end col-md-3 -->

									<div class='col-md-8'>
										<input type="text" class="form-control" name="special_remark[]" value="{{ $specialRemark->data }}">
									</div><!-- end col-md-8 -->

									<div class='col-md-1'>
										<i class='fa fa-minus-circle removeSpecRemarkBtn1' aria-hidden='true'></i>
									</div><!-- end col-md-1 -->
								</div><!-- end form-group -->

								@endforeach

								</div><!-- end special_remark -->

								@else

								<div id="special_remark">
									<div class='form-group'>
										<label class='col-md-3 control-label'>Special Remarks</label><!-- end col-md-3 -->

										<div class='col-md-8'>
											<input type="text" class="form-control" name="special_remark[]" value="">
										</div><!-- end col-md-8 -->

										<div class='col-md-1'>
										</div><!-- end col-md-1 -->
									</div><!-- end form-group -->
								</div><!-- end special_remark -->

								@endif
								@endif

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
                            id="edit_approved_date" data-date-format="dd/mm/yyyy" value="{{ old('approved_date') }}">
                    </div><!-- end col-md-9 -->
                </div><!-- end form-group -->

                <div class="form-group">
                    <label class="col-md-3 control-label">Member Cancelled Date</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-inline date-picker" name="cancelled_date" data-provide="datepicker"
                            id="edit_cancelled_date" data-date-format="dd/mm/yyyy" value="{{ old('cancelled_date') }}">
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
