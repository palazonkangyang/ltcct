
    <form method="post" action="{{ URL::to('/staff/donation') }}" class="form-horizontal form-bordered">

            {!! csrf_field() !!}

        <div class="form-body">

            <div class="form-group">

                <h4>Same address and same family code</h4>

                <table class="table table-bordered" id="generaldonation_table">
                    <thead>
                        <tr>
                            <th>Chinese Name</th>
                            <th>Devotee#</th>
                            <th>Block</th>
                            <th>Address</th>
                            <th>Unit</th>
                            <th>Guiyi Name</th>
                            <th>Amount</th>
                            <th>Pay Till</th>
                            <th>HJ/ GR</th>
                            <th>Display</th>
                            <th>XYReceipt</th>
                            <th>Trans Date</th>
                        </tr>
                    </thead>

                    @if(Session::has('devotee_lists'))

                        @php

                            $devotee_lists = Session::get('devotee_lists');
                            $focus_devotee = Session::get('focus_devotee');

                        @endphp

                    <tbody id="has_session">

                        <tr>
                            <td>{{ $focus_devotee[0]->chinese_name }}</td>
                            <td>
                                {{ $focus_devotee[0]->devotee_id }}
                                <input type="hidden" name="devotee_id[]" value="{{ $focus_devotee[0]->devotee_id }}">
                            </td>
                            <td>{{ $focus_devotee[0]->address_building }}</td>
                            <td>{{ $focus_devotee[0]->address_street }}</td>
                            <td>{{ $focus_devotee[0]->address_unit1 }} {{ $focus_devotee[0]->address_unit2 }} </td>
                            <td>{{ $focus_devotee[0]->guiyi_name }}</td>
                            <td width="100px">
                                <input type="text" class="form-control amount" name="amount[]">
                            </td>
                            <td width="120px">
                                <input type="text" class="form-control paid_till" name="paid_till[]" data-provide="datepicker">
                            </td>
                            <td>
                                <select class="form-control" name="hjgr_arr[]">
	                                <option value="hj">hj</option>
	                                <option value="gr">gr</option>
	                            </select>
                            </td>
                            <td>
                                <select class="form-control" name="display[]">
	                                <option value="Y">Y</option>
	                                <option value="N">N</option>
	                            </select>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach($devotee_lists as $devotee)

                        <tr>
                            <td>{{ $devotee->chinese_name }}</td>
                            <td>{{ $devotee->devotee_id }}
                                <input type="hidden" name="devotee_id[]" value="{{ $devotee->devotee_id }}">
                            </td>
                            <td>{{ $devotee->address_building }}</td>
                            <td>{{ $devotee->address_street }}</td>
                            <td>{{ $devotee->address_unit1 }} {{ $devotee->address_unit2 }}</td>
                            <td>{{ $devotee->guiyi_name }}</td>
                            <td width="100px" class="amount-col">
                                <input type="text" class="form-control amount" name="amount[]">
                            </td>
                            <td width="120px">
                                <input type="text" class="form-control paid_till" name="paid_till[]" data-provide="datepicker">
                            </td>
                            <td>
                                <select class="form-control" name="hjgr_arr[]">
	                                <option value="hj">hj</option>
	                                <option value="gr">gr</option>
	                            </select>
                            </td>
                            <td>
                                <select class="form-control" name="display[]">
	                                <option value="Y">Y</option>
	                                <option value="N">N</option>
	                            </select>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        @endforeach

                    </tbody>

                    @else

                    <tbody id="no_session">
                        <tr>
	                        <td colspan="12">No Data</td>
	                    </tr>
                    </tbody>

                    @endif

                </table>

            </div><!-- end form-group -->

            <div class="form-group">

                <h4>Relatives and friends</h4>

                <table class="table table-bordered" id="generaldonation_table">
                    <thead>
                        <tr>
                            <th>Chinese Name</th>
                            <th>Devotee#</th>
                            <th>Block</th>
                            <th>Address</th>
                            <th>Unit</th>
                            <th>Guiyi Name</th>
                            <th>Amount</th>
                            <th>Pay Till</th>
                            <th>HJ/ GR</th>
                            <th>Display</th>
                            <th>XYReceipt</th>
                            <th>Trans Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="12">No Data</td>
                        </tr>
                    </tbody>

                </table>

            </div><!-- end form-group -->

        </div><!-- end form-body -->

        <hr>
