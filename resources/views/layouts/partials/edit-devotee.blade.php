		<form method="post" action="{{ URL::to('/operator/edit-devotee') }}"
        class="form-horizontal form-bordered">

        {!! csrf_field() !!}

        <div class="form-body">

					@if(Session::has('focus_devotee'))

			        @php $focus_devotee = Session::get('focus_devotee'); @endphp

			       @if(count($focus_devotee) > 1)

						 <div class="form-body" style="margin-bottom: 25px;">

				 			<div class="col-md-3">
				 				<label>Devotee ID (to be assigned)</label>
				 			</div><!-- end col-md-3 -->

				 			<div class="col-md-3">
				 				<label>Member ID (to be assigned)</label>
				 			</div><!-- end col-md-3 -->

				 			<div class="col-md-3">
				 				<label>Bridging ID (to be assigned)</label>
				 			</div><!-- end col-md-3 -->

				 			<div class="col-md-3">
				 				<label>Family Code (to be assigned)</label>
				 			</div><!-- end col-md-3 -->

				 			<div class="clearfix">
				 			</div><!-- end clearfix -->

				 		</div><!-- end form-body -->

						 <div class="col-md-6">

							 <div class="form-group">
									 <input type="hidden" name="devotee_id" value="{{ old('devotee_id') }}" id="edit_devotee_id">
									 <input type="hidden" name="familycode_id" value="{{ old('familycode_id') }}" id="edit_familycode_id">
									 <input type="hidden" name="member_id" value="{{ old('member_id') }}" id="edit_member_id">
								 </div><!-- end form-group -->

								 <div class="form-group">

										 <label class="col-md-3 control-label">Title</label>
										 <div class="col-md-9">
												 <select class="form-control" name="title" id="edit_title">
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
														 id="edit_address_unit1" maxlength="3">
										 </div><!-- end col-md-2 -->

										 <label class="col-md-1">-</label>

										 <div class="col-md-2">
												 <input type="text" class="form-control" name="address_unit2" value="{{ old('address_unit2') }}"
														 id="edit_address_unit2" maxlength="5">
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

										 <div class="col-md-12">
											 <button type="button" class="btn default edit_check_family_code" style="margin-right: 30px;">
													 Check Family Code
											 </button>

											 @if(Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 5)
											 <a href="/admin/add-address" class="btn default">Add New Address</a>
											 @endif
										 </div><!-- end col-md-12 -->

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
															 <option value="widowed">Widowed</option>
															 <option value="separated">Separated</option>
															 <option value="divorced">Divorced</option>
													 </select>
											 </div><!-- end col-md-9 -->

									 </div><!-- end form-group -->

									 <div class="form-group">

											 <label class="col-md-3 control-label">Dialect</label>
											 <div class="col-md-9">
													 <select class="form-control" name="dialect" id="edit_dialect">
															 <option value="">Please select</option>
															 @foreach($dialects as $dialect)
															 <option value="{{ $dialect->dialect_id }}">{{ $dialect->dialect_name }}</option>
															 @endforeach
													 </select>
											 </div><!-- end col-md-9 -->

									 </div><!-- end form-group -->

									 <div class="form-group" id="edit_other_dialect_field" style="display:none;">

	 										<label class="col-md-3 control-label"></label>
	 										<div class="col-md-9">
	 												<input type="text" name="other_dialect" class="form-control" value=""
	 												placeholder="Other Dialect" id="edit_other_dialect">
	 										</div><!-- end col-md-9 -->

	 								</div><!-- end form-group -->

									 <div class="form-group">

											 <label class="col-md-3 control-label">Race</label>
											 <div class="col-md-9">
													 <select class="form-control" name="race" id="edit_race">
															 <option value="">Please select</option>
															 @foreach($races as $race)
															 <option value="{{ $race->race_id }}">{{ $race->race_name }}</option>
															 @endforeach
													 </select>
											 </div><!-- end col-md-9 -->

									 </div><!-- end form-group -->

									 <div class="form-group" id="edit_other_race_field" style="display:none;">

	 										<label class="col-md-3 control-label"></label>
	 										<div class="col-md-9">
	 												<input type="text" name="other_race" class="form-control" value=""
	 												placeholder="Other Race" id="edit_other_race">
	 										</div><!-- end col-md-9 -->

	 								</div><!-- end form-group -->

									 <div class="form-group">

											 <label class="col-md-3 control-label">Nationality</label>
											 <div class="col-md-9">
													 <select class="form-control" name="nationality" id="edit_nationality">
															 <option value="">Please select</option>
															 @foreach($countries as $country)
															 <option value="{{ $country->country_id }}">{{ $country->country_name }}</option>
															 @endforeach
													 </select>
											 </div><!-- end col-md-9 -->

									 </div><!-- end form-group -->

									 <div class="form-group">

											 <div class="col-md-12">
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

						 <div class="form-body" style="margin-bottom: 25px;">

						   <div class="col-md-3">
						     <label>Devotee ID : {{ $focus_devotee[0]->devotee_id }}</label>
						   </div><!-- end col-md-3 -->

						   <div class="col-md-3">
						     <label>Member ID : {{ $focus_devotee[0]->member }}</label>
						   </div><!-- end col-md-3 -->

						   <div class="col-md-3">
						     <label>Bridging ID : </label>
						   </div><!-- end col-md-3 -->

						   <div class="col-md-3">
						     <label>Family Code : {{ $focus_devotee[0]->familycode }}</label>
						   </div><!-- end col-md-3 -->

						   <div class="clearfix">
						   </div><!-- end clearfix -->

						 </div><!-- end form-body -->

						 <div class="col-md-6">

	 						<div class="form-group">
	 								<input type="hidden" name="devotee_id" value="{{ $focus_devotee[0]->devotee_id }}" id="edit_devotee_id">
	 								<input type="hidden" name="familycode_id" value="{{ $focus_devotee[0]->familycode_id }}" id="edit_familycode_id">
	 								<input type="hidden" name="member_id" value="{{ $focus_devotee[0]->member_id }}" id="edit_member_id">
	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Title *</label>
	 									<div class="col-md-8">
	 											<select class="form-control" name="title" id="edit_title">
	 													<option value="mr" <?php if ($focus_devotee[0]->title == "mr") echo "selected"; ?>>Mr</option>
	 													<option value="miss" <?php if ($focus_devotee[0]->title == "miss") echo "selected"; ?>>Miss</option>
	 													<option value="madam" <?php if ($focus_devotee[0]->title == "madam") echo "selected"; ?>>Madam</option>
	 											</select>
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Chinese Name *</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="chinese_name" value="{{ $focus_devotee[0]->chinese_name }}"
	 													id="edit_chinese_name">
	 											</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">English Name</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="english_name" value="{{ $focus_devotee[0]->english_name }}" id="edit_english_name">
	 									</div><!-- end col-md-9 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Contact # *</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="contact" value="{{ $focus_devotee[0]->contact }}" id="edit_contact">
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

								<div class="form-group">

									<label class="col-md-4">Email Address</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="email"
										value="{{ $focus_devotee[0]->email }}" id="content_email_address">
									</div><!-- end col-md-8 -->

								</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Guiyi Name</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="guiyi_name" value="{{ $focus_devotee[0]->date }}" id="edit_guiyi_name">
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

								<div class="form-group">

									<label class="col-md-4">Guiyi Date</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="guiyi_date" id="content_guiyi_date"
										data-provide="datepicker" data-date-format="dd/mm/yyyy" value="">
									</div><!-- end col-md-9 -->

								</div><!-- end form-group -->
	 							<div class="form-group">

	 									<label class="col-md-4">Address - House No *</label>
	 									<div style='width:16.66667%;float:left; padding-left: 15px;'>
	 											<input type="text" class="form-control" name="address_houseno" value="{{ $focus_devotee[0]->address_houseno }}"
	 													id="edit_address_houseno">
	 									</div><!-- end col-md-3 -->

	 									<label style='width:9.3%;float:left;'>Unit</label>

	 									<div style='width:14.5%;float:left;'>
	 											<input type="text" class="form-control" name="address_unit1" value="{{ $focus_devotee[0]->address_unit1 }}"
	 													id="edit_address_unit1" maxlength="3">
	 									</div><!-- end col-md-2 -->

	 									<label style='width:6.2%;float:left;'>-</label>

	 									<div style='width:16.66667%;float:left;'>
	 											<input type="text" class="form-control" name="address_unit2" value="{{ $focus_devotee[0]->address_unit2 }}"
	 													id="edit_address_unit2" maxlength="5">
	 									</div><!-- end col-md-2 -->
	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Address - Street *</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="address_street"
	 													value="{{ $focus_devotee[0]->address_street }}" id="edit_address_street">
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Address - Postal *</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="address_postal" value="{{ $focus_devotee[0]->address_postal }}"
	 													id="edit_address_postal">
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Address - Translate</label>
	 									<div class="col-md-8">
	 													<input type="text" class="form-control" name="address_translated" id="edit_address_translated"
														value="{{ $focus_devotee[0]->address_translated }}" readonly>
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<label class="col-md-4">Oversea Addr in Chinese</label>
	 									<div class="col-md-8">
	 											<input type="text" class="form-control" name="oversea_addr_in_chinese" id="edit_oversea_addr_in_chinese"
												value="{{ $focus_devotee[0]->oversea_addr_in_chinese }}">
	 									</div><!-- end col-md-8 -->

	 							</div><!-- end form-group -->

	 							<div class="form-group">

	 									<div class="col-md-12">
	 										<button type="button" class="btn default edit_check_family_code" style="margin-right: 30px;">
	 												Check Family Code
	 										</button>

											@if(Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 5)
											<a href="/admin/add-address" class="btn default">Add New Address</a>
											@endif
	 									</div><!-- end col-md-12 -->

	 							</div><!-- end form-group -->

	 						</div><!-- end col-md-6 -->

							<div class="col-md-6">

									<div class="form-group">

										<label class="col-md-4">NRIC</label>
										<div class="col-md-8">
											 <input type="text" class="form-control" name="nric" value="{{ $focus_devotee[0]->nric }}" id="edit_nric">
										 </div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Deceased Year</label>
											<div class="col-md-8">
													<input type="text" class="form-control" name="deceased_year"
															value="{{ $focus_devotee[0]->deceased_year }}" id="edit_deceased_year">
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Date of Birth</label>
											<div class="col-md-8">
													<input type="text" class="form-control" name="dob" data-provide="datepicker" data-date-format="dd/mm/yyyy"
													value="{{ $focus_devotee[0]->dob }}" id="edit_dob">
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Marital Status</label>
											<div class="col-md-8">
													<select class="form-control" name="marital_status" id="edit_marital_status">
															<option value="">Please select</option>
															<option value="single" <?php if ($focus_devotee[0]->marital_status == "single") echo "selected"; ?>>Single</option>
															<option value="married" <?php if ($focus_devotee[0]->marital_status == "married") echo "selected"; ?>>Married</option>
															<option value="widowed" <?php if ($focus_devotee[0]->marital_status == "widowed") echo "selected"; ?>>Widowed</option>
															<option value="separated" <?php if ($focus_devotee[0]->marital_status == "separated") echo "selected"; ?>>Separated</option>
															<option value="divorced" <?php if ($focus_devotee[0]->marital_status == "divorced") echo "selected"; ?>>Divorced</option>
													</select>
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Dialect</label>
											<div class="col-md-8">
													<select class="form-control" name="dialect" id="edit_dialect">
															<option value="">Please select</option>
															@foreach($dialects as $dialect)
															<option value="{{ $dialect->dialect_id }}" <?php if ($focus_devotee[0]->dialect == $dialect->dialect_id) echo "selected"; ?>>{{ $dialect->dialect_name }}</option>
															@endforeach
															<option value="other_dialect">Others</option>
													</select>
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group" id="edit_other_dialect_field" style="display:none;">

											<label class="col-md-4"></label>
											<div class="col-md-8">
													<input type="text" name="other_dialect" class="form-control" value="{{ old('other_dialect') }}"
													placeholder="Other Dialect" id="edit_other_dialect">
											</div><!-- end col-md-9 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Race</label>
											<div class="col-md-8">
													<select class="form-control" name="race" id="edit_race">
															<option value="">Please select</option>
															@foreach($races as $race)
															<option value="{{ $race->race_id }}" <?php if ($focus_devotee[0]->race == $race->race_id) echo "selected"; ?>>{{ $race->race_name }}</option>
															@endforeach
															<option value="other_race">Others</option>
													</select>
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group" id="edit_other_race_field" style="display:none;">

										 <label class="col-md-4"></label>
										 <div class="col-md-8">
												 <input type="text" name="other_race" class="form-control" value="{{ old('other_race') }}"
												 placeholder="Other Race" id="edit_other_race">
										 </div><!-- end col-md-8 -->

								 </div><!-- end form-group -->

									<div class="form-group">

											<label class="col-md-4">Nationality</label>
											<div class="col-md-8">
													<select class="form-control" name="nationality" id="edit_nationality">
															<option value="">Please select</option>
															@foreach($countries as $country)
															<option value="{{ $country->id }}" <?php if ($focus_devotee[0]->nationality == $country->id) echo "selected"; ?>>
																{{ $country->country_name }}
															</option>
															@endforeach
													</select>
											</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

									<div class="form-group">

											<div class="col-md-12">
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

											<label class="col-md-4">Mailer *</label>
											<div class="col-md-3">
													<select class="form-control" name="mailer">
														<option value="No" <?php if ($focus_devotee[0]->mailer == "No") echo "selected"; ?>>No</option>
														<option value="Yes" <?php if ($focus_devotee[0]->mailer == "Yes") echo "selected"; ?>>Yes</option>
													</select>
											</div><!-- end col-md-3 -->

											<div class="col-md-6">
											</div><!-- end col-md-6 -->

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
											<select class="form-control" name="title" id="edit_title">
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
													<option value="widowed">Widowed</option>
													<option value="separated">Separated</option>
													<option value="divorced">Divorced</option>
												</select>
										</div><!-- end col-md-9 -->

								</div><!-- end form-group -->

								<div class="form-group">

										<label class="col-md-3 control-label">Dialect</label>
										<div class="col-md-9">
												<select class="form-control" name="dialect" id="edit_dialect">
														<option value="">Please select</option>
														<option value="chinese">Chinese</option>
														<option value="other_dialect">Others</option>
												</select>
										</div><!-- end col-md-9 -->

								</div><!-- end form-group -->

								<div class="form-group" id="edit_other_dialect_field" style="display:none;">

										<label class="col-md-3 control-label"></label>
										<div class="col-md-9">
												<input type="text" name="other_dialect" class="form-control" value="{{ old('other_dialect') }}"
												placeholder="Other Dialect" id="edit_other_dialect">
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

								<div class="form-group" id="edit_other_race_field" style="display:none;">

										<label class="col-md-3 control-label"></label>
										<div class="col-md-9">
												<input type="text" name="other_race" class="form-control" value="{{ old('other_race') }}"
												placeholder="Other Race" id="edit_other_race">
										</div><!-- end col-md-9 -->

								</div><!-- end form-group -->

								<div class="form-group">

										<label class="col-md-3 control-label">Nationality</label>
										<div class="col-md-9">
												<select class="form-control" name="nationality" id="edit_nationality">
														<option value="">Please select</option>
														@foreach($countries as $country)
														<option value="{{ $country->country_id }}">{{ $country->country_name }}</option>
														@endforeach
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
																				<th>Family Code</th>
																				<th>Name</th>
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

          <div class="col-md-6">

						<h5>Optional Address</h5>

						@if(Session::has('optionaladdresses'))

						@php $optionaladdresses = Session::get('optionaladdresses');

						@endphp

								@if(count($optionaladdresses) > 0)

								<div id="edit_opt_address">

								@foreach($optionaladdresses as $optAddress)

								<div class="edit_inner_opt_addr">
									<div class="form-group">

										<div class='col-md-1'>
											<i class='fa fa-minus-circle removeAddressBtn1' aria-hidden='true'></i>
										</div>

										<div class='col-md-3 optional-wrapper'>

											<select class='form-control edit-address-type' name='address_type[]'>
												<option value="home" <?php if ($optAddress->type == "home") echo "selected"; ?>>宅址</option>
												<option value="company" <?php if ($optAddress->type == "company") echo "selected"; ?>>公司</option>
												<option value="stall" <?php if ($optAddress->type == "stall") echo "selected"; ?>>小贩</option>
												<option value="office" <?php if ($optAddress->type == "office") echo "selected"; ?>>办公址</option>
											</select>

										</div><!-- end col-md-3 -->

										@if($optAddress->type == "home" || $optAddress->type == "office")

										<div class='col-md-6' style='padding-right: 0;'>
											<input type="text" class="form-control edit-address-data" name="address_data[]" placeholder="Please fill address on the right"
												title="Please fill address on the right" readonly>
										</div><!-- end col-md-6 -->

										@else

										<div class='col-md-6' style='padding-right: 0;'>
											<input type="text" class="form-control edit-address-data" name="address_data[]" value="{{ $optAddress->data }}"
												title="{{ $optAddress->data }}">
										</div><!-- end col-md-4 -->

										@endif

										<div class='col-md-2'>
											<button type='button' class='fa fa-angle-double-right edit-populate-data form-control' aria-hidden='true'></button>
										</div>

										<div class="col-md-12">
											<input type="hidden" class="form-control edit-address-houseno-hidden">
											<input type="hidden" class="form-control edit-address-unit1-hidden">
											<input type="hidden" class="form-control edit-address-unit2-hidden">
											<input type="hidden" class="form-control edit-address-street-hidden">
											<input type="hidden" class="form-control edit-address-postal-hidden">
											<input type="hidden" class="form-control edit-address-oversea-hidden" name="address_oversea_hidden[]"
												value="{{ $optAddress->oversea_address }}">
											<input type="hidden" class="form-control edit-address-translate-hidden" name="address_translated_hidden[]"
												value="{{ $optAddress->address_translated }}">
											<input type="hidden" class="form-control edit-address-data-hidden" name="address_data_hidden[]"
												value="{{ $optAddress->address }}">
										</div>

									</div><!-- end form-group -->
								</div><!-- end edit_inner_opt_addr -->

								@endforeach

								</div><!-- end edit_opt_address -->

								@else

								<div id="edit_opt_address">
									<div class="edit_inner_opt_addr">
										<div class="form-group">

											<div class='col-md-1'>
											</div><!-- end col-md-1 -->

											<div class='col-md-3 optional-wrapper'>
												<select class='form-control edit-address-type' name='address_type[]'>
													<option value="home">宅址</option>
													<option value="company">公司</option>
													<option value="stall">小贩</option>
													<option value="office">办公址</option>
												</select>
											</div><!-- end col-md-3 -->

											<div class='col-md-6' style='padding-right: 0;'>
												<input type="text" class="form-control edit-address-data" name="address_data[]" value="Please fill address on the right"
													title="Please fill address on the right" readonly>
											</div><!-- end col-md-6 -->

											<div class='col-md-2'>
												<button type='button' class='fa fa-angle-double-right edit-populate-data form-control' aria-hidden='true'></button>
											</div>

											<div class="col-md-12">
												<input type="hidden" class="form-control edit-address-houseno-hidden">
												<input type="hidden" class="form-control edit-address-unit1-hidden">
												<input type="hidden" class="form-control edit-address-unit2-hidden">
												<input type="hidden" class="form-control edit-address-street-hidden">
												<input type="hidden" class="form-control edit-address-postal-hidden">
												<input type="hidden" class="form-control edit-address-oversea-hidden" name="address_oversea_hidden[]">
												<input type="hidden" class="form-control edit-address-translate-hidden" name="address_translated_hidden[]">
												<input type="hidden" class="form-control edit-address-data-hidden" name="address_data_hidden[]">
											</div>
										</div><!-- end form-group -->
									</div><!-- end edit_inner_opt_addr -->

								</div><!-- end edit_opt_address -->

								@endif
								@endif

								<div class="form-group">
                    <div class="col-md-1"></div><!-- end col-md-1 -->

                    <div class="col-md-5" style="margin-bottom: 15px;">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="AddressBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6"></div><!-- end col-md-6 -->
                </div><!-- end form-group -->

								<h5>Optional Vehicle</h5>

								@if(Session::has('optionalvehicles'))

								@php $optionalvehicles = Session::get('optionalvehicles'); @endphp

								@if(count($optionalvehicles) > 0)

								<div id="opt_vehicle">

								@foreach($optionalvehicles as $optVehicle)

								<div class='form-group'>
									<div class='col-md-1'>
										<i class='fa fa-minus-circle removeVehicleBtn1' aria-hidden='true'></i>
									</div><!-- end col-md-1 -->

									<div class='col-md-3 optional-wrapper'>
										<select class='form-control' name='vehicle_type[]'>
											<option value="car" <?php if ($optVehicle->type == "car") echo "selected"; ?>>车辆</option>
											<option value="ship" <?php if ($optVehicle->type == "ship") echo "selected"; ?>>船只</option>
										</select>
									</div><!-- end col-md-3 -->

									<div class='col-md-8 vehicle-data'>
										<input type="text" class="form-control" name="vehicle_data[]" value="{{ $optVehicle->data }}">
									</div><!-- end col-md-8 -->

								</div><!-- end form-group -->

								@endforeach

								</div><!-- end opt_vehicle -->

								@else

								<div id="opt_vehicle">

									<div class='form-group'>
										<div class='col-md-1'>
										</div><!-- end col-md-1 -->

										<div class='col-md-3 optional-wrapper'>
											<select class='form-control' name='vehicle_type[]'>
												<option value="car">车辆</option>
												<option value="ship">船只</option>
											</select>
										</div><!-- end col-md-3 -->

										<div class='col-md-8 vehicle-data'>
											<input type="text" class="form-control" name="vehicle_data[]" value="">
										</div><!-- end col-md-8 -->

									</div><!-- end form-group -->

								</div><!-- end opt_vehicle -->

								@endif
								@endif

                <div class="form-group">
                    <div class="col-md-1">
                    </div><!-- end col-md-1 -->

                    <div class="col-md-5" style="margin-bottom: 15px;">
                        <i class="fa fa-plus-circle" aria-hidden="true" id="VehicleBtn"></i>
                    </div><!-- end col-md-5 -->

                    <div class="col-md-6">
                    </div><!-- end col-md-6 -->
                </div><!-- end form-group -->

								<h5>Special Remark</h5>

								@if(Session::has('specialRemarks'))

								@php $specialRemarks = Session::get('specialRemarks'); @endphp

								@if(count($specialRemarks) > 0)

								<div id="special_remark">

								@foreach($specialRemarks as $specialRemark)

								<div class='form-group'>
									<div class='col-md-1'>
										<i class='fa fa-minus-circle removeSpecRemarkBtn1' aria-hidden='true'></i>
									</div><!-- end col-md-1 -->

									<div class='col-md-11 special-remark'>
										<input type="text" class="form-control" name="special_remark[]" value="{{ $specialRemark->data }}">
									</div><!-- end col-md-11 -->

								</div><!-- end form-group -->

								@endforeach

								</div><!-- end special_remark -->

								@else

								<div id="special_remark">

									<div class='form-group'>
										<div class='col-md-1'>
										</div><!-- end col-md-1 -->

										<div class='col-md-11 special-remark'>
											<input type="text" class="form-control" name="special_remark[]" value="">
										</div><!-- end col-md-9 -->

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
                    <label class="col-md-12">
                        If you have made Changes to the above. You need to CONFIRM to save the Changes.<br />
                        To Confirm, please enter authorized password to proceed.
                    </label>
                </div><!-- end form-group -->
            </div><!-- end col-md-6 -->

						<div class="col-md-6">

							<div style="border: 1px solid #D5D4D4; padding: 5px; margin-bottom: 10px;">
								<h5>Local Address</h5>

								<div class="form-group">
									<label class="col-md-4 local-address">House No *</label>
									<div style='width:16.66667%;float:left; padding-left: 15px;'>
											<input type="text" class="form-control" name="populate_houseno"
													value="{{ old('populate_houseno') }}" id="edit_populate_houseno">
									</div><!-- end col-md-3 -->

									<label style='width:9.3%;float:left;'>Unit</label>

									<div style='width:14.5%;float:left;'>
											<input type="text" class="form-control" name="populate_unit_1"
													value="{{ old('populate_unit_1') }}" id="edit_populate_unit_1" maxlength="3">
									</div><!-- end col-md-2 -->

									<label style='width:6.2%;float:left;'>-</label>

									<div style='width:16.66667%;float:left;'>
											<input type="text" class="form-control" name="populate_unit_2"
													value="{{ old('populate_unit_2') }}" id="edit_populate_unit_2" maxlength="5">
									</div><!-- end col-md-2 -->
								</div><!-- end form-group -->

								<div class="form-group">
									<label class="col-md-4 local-address">Street *</label>
									<div class="col-md-8">
											<input type="text" class="form-control" name="populate_street"
													value="{{ old('populate_address_street') }}" id="edit_populate_street">
									</div><!-- end col-md-8 -->
								</div><!-- end form-group -->

								<div class="form-group">
									<label class="col-md-4 local-address">Postal *</label>
									<div class="col-md-8">
											<input type="text" class="form-control" name="populate_postal"
													value="{{ old('populate_postal') }}" id="edit_populate_postal">
									</div><!-- end col-md-8 -->
								</div><!-- end form-group -->

								<div class="form-group">
									<label class="col-md-4 local-address">Address Translate</label>
									<div class="col-md-8">
											<input type="text" class="form-control" name="populate_address_translate" readonly
													value="{{ old('populate_address_translate') }}" id="edit_populate_address_translate">
									</div><!-- end col-md-8 -->
								</div><!-- end form-group -->

								<div class="form-group">
									<label class="col-md-4 local-address">Oversea Addr in Chinese</label>
									<div class="col-md-8">
											<input type="text" class="form-control" name="populate_oversea_addr_in_china" autocomplete="nope"
													value="{{ old('populate_oversea_addr_in_china') }}" id="edit_populate_oversea_addr_in_china">
									</div><!-- end col-md-8 -->
								</div><!-- end form-group -->
							</div>

						@if(Auth::user()->role == 3)

						@if(Session::has('focus_devotee'))

				        @php $focus_devotee = Session::get('focus_devotee'); @endphp

				       @if(count($focus_devotee) > 1)

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

								@else

								<div class="form-group">
                    <label class="col-md-3">Introduced By-1</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="introduced_by1" value="{{ $focus_devotee[0]->introduced_by1 }}"
													id="edit_introduced_by1">
                   	</div><!-- end col-md-9 -->
                </div><!-- end form-group -->

                <div class="form-group">
                    <label class="col-md-3">Introduced By-2</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="introduced_by2" value="{{ $focus_devotee[0]->introduced_by2 }}" id="edit_introduced_by2">
                    </div><!-- end col-md-9 -->
                </div><!-- end form-group -->

                <div class="form-group">
                    <label class="col-md-3">Member Approved Date</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-inline date-picker" name="approved_date" data-provide="datepicker"
                            id="edit_approved_date" data-date-format="dd/mm/yyyy" value="{{ $focus_devotee[0]->approved_date }}">
                    </div><!-- end col-md-9 -->
                </div><!-- end form-group -->

								@endif

						@else

							<div class="form-group">
									<label class="col-md-3">Introduced By-1</label>
									<div class="col-md-9">
											<input type="text" class="form-control" name="introduced_by1" value="{{ old('introduced_by1') }}" id="edit_introduced_by1">
									</div><!-- end col-md-9 -->
							</div><!-- end form-group -->

							<div class="form-group">
									<label class="col-md-3">Introduced By-2</label>
									<div class="col-md-9">
											<input type="text" class="form-control" name="introduced_by2" value="{{ old('introduced_by2') }}" id="edit_introduced_by2">
									</div><!-- end col-md-9 -->
							</div><!-- end form-group -->

							<div class="form-group">
									<label class="col-md-3">Member Approved Date</label>
									<div class="col-md-9">
											<input type="text" class="form-control form-control-inline date-picker" name="approved_date" data-provide="datepicker"
													id="edit_approved_date" data-date-format="dd/mm/yyyy" value="{{ old('approved_date') }}">
									</div><!-- end col-md-9 -->
							</div><!-- end form-group -->

						@endif

						@if(isset($focus_devotee[0]->approved_date))

                <div class="form-group">
                    <label class="col-md-3">Member Cancelled Date</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-inline date-picker" name="cancelled_date" data-provide="datepicker"
                            id="edit_cancelled_date" data-date-format="dd/mm/yyyy" value="{{ old('cancelled_date') }}">
                    </div><!-- end col-md-9 -->
                </div><!-- end form-group -->

                <div class="form-group">
                    <label class="col-md-3">Reason for Cancel</label>
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

								@endif

								@endif

               	<div class="form-group">
                  <label class="col-md-3"></label>
                  <label class="col-md-5 control-label">Authorized Password</label>
                  <div class="col-md-4">
                    <input type="password" class="form-control" name="authorized_password" id="authorized_password" autocomplete="new-password">
                  </div><!-- end col-md-4 -->
                </div><!-- end form-group -->

                <div class="form-actions pull-right">
                	<button type="submit" class="btn blue" id="update_btn">Confirm</button>
                  <button type="button" class="btn default" id="edit_cancel_btn">Cancel</button>
                </div><!-- end form-actions -->
            </div><!-- end col-md-6 -->

						<div id="edit-dialog-box" title="System Alert">
								You have NOT Saved this New Devotee Record 1
								Do you want to Cancel this record?
						</div>

        </div><!-- end form-body -->

        <div class="clearfix"></div><!-- end clearfix -->

    </form>
