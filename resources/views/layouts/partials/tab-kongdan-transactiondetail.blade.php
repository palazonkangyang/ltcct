<div class="form-body">

  <form method="post" target="_blank" action="{{ URL::to('/fahui/reprint-receipt') }}"
    class="form-horizontal form-bordered" id="">
    {!! csrf_field() !!}

    <div class="col-md-6">

      <div class="form-group">
        <label class="col-md-3">Receipt No</label>
        <div class="col-md-5">
            <input type="text" class="form-control" id="receipt_no" name="receipt_no">
        </div><!-- end col-md-5 -->
        <div class="col-md-4">
        </div><!-- end col-md-4 -->
      </div><!-- end form-group -->

      <div class="form-group">
        <label class="col-md-3">Transaction No</label>
        <div class="col-md-5">
            <input type="text" class="form-control" id="trans_no" name="trans_no">
        </div><!-- end col-md-5 -->
        <div class="col-md-4">
          <button type="button" class="btn default" id="kongdan_search_detail">Detail</button>
        </div><!-- end col-md-4 -->
      </div><!-- end form-group -->

    </div><!-- end col-md-6 -->

    <div class="col-md-6">
    </div><!-- end col-md-6 -->

    <div class="col-md-12" id="kongdan_trans_wrap1">
      <div class="form-group">
        <br />
        <h5 style="font-weight: bold">TRANSACTION & RECEIPT VIEWER - 交易详情</h5>
      </div><!-- end form-group -->
    </div><!-- end col-md-12 -->

    <div class="col-md-12" id="kongdan_trans_wrap2">

      <div class="col-md-6">
        <div class="form-group">
          <label class="col-md-4">Receipt Date (日期)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="receipt_date"></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-4">Paid By (付款者)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="paid_by"></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-4">Description (项目)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="description"></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-4">Donation for Next Event ( 法会香油 )</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="donation_event"></label>
        </div><!-- end form-group -->
      </div><!-- end col-md-6 -->

      <div class="col-md-6">

        <div class="form-group">
          <label class="col-md-4">Receipt No (收据)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="receipt"></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-4">Transaction No (交易)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="transaction_no"></label>
        </div><!-- end form-group -->

        <div class="form-group">
          <label class="col-md-4">Attended By (接待者)</label>
          <label class="col-md-1">:</label>
          <label class="col-md-4" id="attended_by"></label>
        </div><!-- end form-group -->
      </div><!-- end col-md-6 -->

    </div><!-- end col-md-12 -->

    <hr>

    <div class="col-md-12" id="kongdan_trans_wrap3">

      <table class="table table-bordered table-striped" id="transaction-table">
       <thead>
         <tr>
           <th width="5%">S/No</th>
           <th width="12%">Chinese Name</th>
           <th width="10%">Devotee</th>
           <th width="25%">Item Description</th>
           <th width="10%">Receipt</th>
           <th width="10%">Amount</th>
         </tr>
       </thead>

       <tbody>
         <tr>
           <td colspan="6">No Result Found</td>
         </tr>
       </tbody>

     </table>

   </div><!-- end col-md-12 -->

   <div class="col-md-12" id="kongdan_trans_wrap4">
     <p class="text-center text-danger" id="transaction-text">

     </p>
   </div><!-- end col-md-12 -->

   <div class="col-md-12" id="kongdan_trans_wrap5">
     <div class="form-group">

       <div class="col-md-6">
         <p style="font-weight: bold; font-size: 13px;">Payment Mode (付款方式) : <span id="payment_mode"></span>
           <br />
           <span style="width: 170px; display: inline-block;"></span>
           <span class="text-danger" id="refund" style="font-weight: normal;"></span>
         </p>
       </div><!-- end col-md-6 -->

       <div class="col-md-6">
          <p style="font-weight: bold; font-size: 13px;">Total Amount (总额) : S$ <span id="amount">0</span></p>
       </div><!-- end col-md-6 -->

     </div><!-- end form-group -->
   </div><!-- end col-md-12 -->

   <div class="col-md-12" id="kongdan_trans_wrap6">
     <div class="form-group">

       <label>Type of Receipt Printing :</label>

       <div class="mt-radio-list">
         <!--
         <label class="mt-radio mt-radio-outline"> 1 Receipt Printing for Same Address
             <input type="radio" name="receipt_printing_type" value="one_receipt_printing_for_same_address" checked>
             <span></span>
         </label>
         -->
         <label class="mt-radio mt-radio-outline"> Individual Receipt Printing
             <input type="radio" name="receipt_printing_type" value="individual_receipt_printing" checked>
             <span></span>
         </label>
     </div><!-- end mt-radio-list -->

     <button type="submit" class="btn blue" id="reprint-btn">Re-print Receipt</button>

    </div><!-- end form-group -->
   </div><!-- end col-md-12 -->

   </form>

   <form method="post" action="{{ URL::to('/fahui/cancel-transaction') }}"
     class="form-horizontal form-bordered" id="">
     {!! csrf_field() !!}
     <input type="hidden" name="mod_id" value=10>

   <div class="col-md-12" id="kongdan_trans_wrap7">
     <br />
     <div class="form-group">
       <label class="col-md-2">Authorized Password</label>
       <div class="col-md-2">
         @if(Auth::user()->role != 5)
           <input type="password" class="form-control" name="authorized_password" id="authorized_password">
         @else
            <input type="password" class="form-control" name="authorized_password" id="authorized_password" disabled>
         @endif
       </div><!-- end col-md-4 -->
       <div class="col-md-4">
       </div><!-- end col-md-4 -->
     </div><!-- end form-group -->
    <br />
   </div><!-- end col-md-12 -->

   <div class="col-md-12" id="kongdan_trans_wrap8">

    <div class="form-group">
     <input type="hidden" name="transaction_no" value="" id="hidden_transaction_no">
     <input type="hidden" value="{{ Auth::user()->role }}" id="user_id">
    </div><!-- end form-group -->

    @if(Auth::user()->role != 5)

     <div class="form-group">
       <div class="form-actions">
        <button type="button" class="btn blue" id="cancel-kongdan-replace-btn">Cancel & Replace Transaction</button>
        <button type="submit" class="btn default" id="cancel-kongdan-transaction">Cancel Transaction</button>
      </div><!-- end form-actions -->
     </div><!-- end form-group -->

     @else

     <div class="form-group">
       <div class="form-actions">
        <button type="button" class="btn blue" id="cancel-kongdan-replace-btn" disabled>Cancel & Replace Transaction</button>
        <button type="submit" class="btn default" id="cancel-kongdan-transaction" disabled>Cancel Transaction</button>
      </div><!-- end form-actions -->
     </div><!-- end form-group -->

     @endif

   </div><!-- end col-md-12 -->

    <div class="clearfix">
    </div><!-- end clearfix -->

  </form>

</div><!-- end form-body -->
