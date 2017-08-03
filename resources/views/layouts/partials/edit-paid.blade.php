
<div class="form-body">
  <div class="col-md-6">

    <form method="post" action="{{ URL::to('/paid/update-paid') }}"
      class="form-horizontal form-bordered">

      {!! csrf_field() !!}

      <div class="form-group">
        <input type="hidden" name="edit_paid_id" id="edit_paid_id" value="">
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Reference No *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_reference_no" value="{{ old('edit_reference_no') }}" id="edit_reference_no">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Date *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_date" value="{{ old('edit_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_date">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Expenditure No *</label>
        <div class="col-md-9">
            <select class="form-control" name="edit_expenditure_id" id="edit_expenditure_id">
              @foreach($expenditure as $exp)
              <option value="{{ $exp->expenditure_id }}">{{ $exp->reference_no }}</option>
              @endforeach
            </select>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Supplier *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_supplier" value="{{ old('edit_supplier') }}" id="edit_supplier">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Description *</label>
        <div class="col-md-9">
            <textarea name="edit_description" class="form-control" rows="3" id="edit_description">{{ old('edit_description') }}</textarea>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Expenditure Total *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_expenditure_total" value="{{ old('edit_expenditure_total') }}" id="edit_expenditure_total">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Outstanding Total *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_outstanding_total" value="{{ old('edit_outstanding_total') }}" id="edit_outstanding_total">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Amount *</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_amount" value="{{ old('edit_amount') }}" id="edit_amount">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Status *</label>
        <div class="col-md-9">
          <select class="form-control" name="edit_status" id="edit_status">
            <option value="draft">Draft</option>
            <option value="posted">Posted</option>
          </select>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Type *</label>
        <div class="col-md-9">
          <select class="form-control" name="edit_type" id="edit_type">
            <option value="cash">Cash</option>
            <option value="cheque">Cheque</option>
          </select>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div id="edit_cash">

        <div class="form-group">
          <label class="col-md-3 control-label">Voucher No</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cash_voucher_no" value="{{ old('edit_cash_voucher_no') }}" id="edit_cash_voucher_no">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Transaction Date</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_transaction_date" value="{{ old('edit_transaction_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_transaction_date">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Cash Account</label>
          <div class="col-md-9">
              <select class="form-control" name="edit_cash_account" id="edit_cash_account">
                <option value="cash">Cash</option>
              </select>
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Cash Amount</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cash_amount" value="{{ old('edit_cash_amount') }}" id="edit_cash_amount">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Payee</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cash_payee" value="{{ old('edit_cash_payee') }}" id="edit_cash_payee">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

      </div><!-- end edit_cash -->

      <div id="edit_cheque">

        <div class="form-group">
          <label class="col-md-3 control-label">Cheque No</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cheque_no" value="{{ old('edit_cheque_no') }}" id="edit_cheque_no">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Cheque Account</label>
          <div class="col-md-9">
              <select class="form-control" name="edit_cheque_account" id="edit_cheque_account">
                @foreach($glcode as $gl)
                <option value="{{ $gl->glcode_id }}">{{ $gl->type_name }}</option>
                @endforeach
              </select>
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Voucher No</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cheque_voucher_no" value="{{ old('edit_cheque_voucher_no') }}" id="edit_cheque_voucher_no">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Payee</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cheque_payee" value="{{ old('edit_cheque_payee') }}" id="edit_cheque_payee">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Cheque Date</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cheque_date" value="{{ old('edit_cheque_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_cheque_date">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-3 control-label">Cash Date</label>
          <div class="col-md-9">
              <input type="text" class="form-control" name="edit_cash_date" value="{{ old('edit_cash_date') }}" data-provide="datepicker" data-date-format="dd/mm/yyyy" id="edit_cash_date">
          </div><!-- end col-md-9 -->
        </div><!-- end form-group -->

      </div><!-- end edit_cheque -->

      <div class="form-group">
        <label class="col-md-3 control-label">Job</label>
        <div class="col-md-9">
            <select class="form-control" name="edit_job_id" id="edit_job_id">
              @foreach($job as $j)
              <option value="{{ $j->job_id }}">{{ $j->job_name }}</option>
              @endforeach
            </select>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Gl Description</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="edit_gl_description" value="{{ old('edit_gl_description') }}" id="edit_gl_description">
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3 control-label">Remark</label>
        <div class="col-md-9">
            <textarea name="edit_remark" class="form-control" rows="3" id="edit_remark">{{ old('edit_remark') }}</textarea>
        </div><!-- end col-md-9 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <p>&nbsp;</p>
      </div><!-- end form-group -->

      <div class="form-group">

        <div class="col-md-6">
          <p>
            If you have made Changes to the above. You need to CONFIRM to save the Changes.
            To Confirm, please enter authorized password to proceed.
          </p>
        </div><!-- end col-md-6 -->

        <div class="col-md-6">
          <label class="col-md-6">Authorized Password</label>
          <div class="col-md-6">
            <input type="password" class="form-control" name="edit_authorized_password" id="edit_authorized_password">
          </div><!-- end col-md-6 -->
        </div><!-- end col-md-6 -->

      </div><!-- end form-group -->

      <div class="form-group">

        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <div class="form-actions pull-right">
            <button type="submit" class="btn blue" id="update_paid_btn">Update
            </button>
            <button type="button" class="btn default">Cancel</button>
          </div><!-- end form-actions -->
        </div><!-- end col-md-9 -->

      </div><!-- end form-group -->

    </form>
  </div><!-- end col-md-6 -->

  <div class="col-md-6">
  </div><!-- end col-md-6 -->

</div><!-- end form-body -->

<div class="clearfix"></div><!-- end clearfix -->
