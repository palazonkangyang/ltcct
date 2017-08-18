@extends('layouts.backend.app')

@section('main-content')

  <div class="page-container-fluid">

    <div class="page-content-wrapper">

      <div class="page-head">

        <div class="container-fluid">

          <div class="page-title">
                <h1>Receipt Detail</h1>
            </div><!-- end page-title -->

        </div><!-- end container-fluid -->

      </div><!-- end page-head -->

      <div class="page-content">

        <div class="container-fluid">

          <ul class="page-breadcrumb breadcrumb">
            <li>
              <a href="/operator/index">Home</a>
              <i class="fa fa-circle"></i>
            </li>
            <li>
              <span>Receipt Detail</span>
            </li>
         </ul>

         <div class="page-content-inner">

           <div class="inbox">

             <div class="row">

               @include('layouts.partials.focus-devotee-sidebar')

               <div class="col-md-9">

                 <div class="form-row-seperated">

                   <div class="portlet light">

                     <div class="validation-error">
                     </div><!-- end validation-error -->

                     @if($errors->any())

                     <div class="alert alert-danger">

                         @foreach($errors->all() as $error)
                           <p>{{ $error }}</p>
                         @endforeach

                     </div><!-- end alert -->

                     @endif

                     @if(Session::has('success'))
                         <div class="alert alert-success"><em> {{ Session::get('success') }}</em></div>
                     @endif

                     @if(Session::has('error'))
                         <div class="alert alert-danger"><em> {{ Session::get('error') }}</em></div>
                     @endif

                     <div class="portlet-body">

                       <div class="form-body">

                         <div class="form-group">

                           <div class="col-md-12">
                             <h4>Receipt Viewer : {{ $receipt[0]->chinese_name }}</h4>
                             <br />
                           </div><!-- end col-md-12 -->

                           <div class="col-md-12">

                             <div class="col-md-6 info-detail">
                               <p>Receipt Date (日期) : {{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</p>
                               <p>Paid By (付款者) : {{ $receipt[0]->chinese_name }} (D - {{ $receipt[0]->devotee_id }})</p>
                               <p>Description (项目) : 香油</p>
                               <p>Donation for Next Event (法会香油) : {{ $festiveevent->event }}</p>
                             </div><!-- end col-md-6 -->

                             <div class="col-md-6 info-detail">
                               <p>Receipt No : {{ $receipt[0]->xy_receipt }}</p>
                               <p>Transaction No : {{ $receipt[0]->trans_no }}</p>
                               <p>Attended By : {{ $receipt[0]->first_name }} {{ $receipt[0]->last_name }}</p>
                             </div><!-- end col-md-6 -->

                           </div><!-- end col-md-12 -->

                         </div><!-- end form-group -->

                         <div class="form-group">

                           <table class="table table-bordered table-striped">
                     				<thead>
                     					<tr>
                     						<th>S/No</th>
                     						<th>Chinese Name</th>
                     						<th>Devotee</th>
                     						<th>Address</th>
                     						<th>HJ/ GR</th>
                     						<th>Receipt</th>
                     						<th>Amount</th>
                     					</tr>
                     				</thead>

                     				<tbody>

                     					@php $count = 1; $sum= 0; @endphp

                     					@foreach($donation_devotees as $donation_devotee)

                     					<tr>
                     						<td>{{ $count }}</td>
                     						<td>{{ $donation_devotee->chinese_name }}</td>
                     						<td>{{ $donation_devotee->devotee_id }}</td>
                     						<td>
                                  @if(isset($donation_devotee->oversea_addr_in_chinese))
                                    {{ $donation_devotee->oversea_addr_in_chinese }}
                                  @elseif(isset($donation_devotee->address_unit1) && isset($donation_devotee->address_unit2))
                                    {{ $donation_devotee->address_houseno }}, #{{ $donation_devotee->address_unit1 }}-{{ $donation_devotee->address_unit2 }}, {{ $donation_devotee->address_street }}, {{ $donation_devotee->address_postal }}
                                  @else
                                    {{ $donation_devotee->address_houseno }}, {{ $donation_devotee->address_street }}, {{ $donation_devotee->address_postal }}
                                  @endif
                                </td>
                     						<td>{{ $donation_devotee->hjgr }}</td>
                     						<td>{{ $receipt[0]->xy_receipt }}</td>
                     						<td>S$ {{ $donation_devotee->amount }}</td>
                     					</tr>

                     					@php $count++;  $sum += $donation_devotee->amount; @endphp

                     					@endforeach
                     				</tbody>
                     			</table>

                         </div><!-- end form-group -->

                         @if(Session::has('cancelled_date'))
                             <p class="text-center text-danger">
                               This Transaction has been cancelled by {{ Session::get('cancelled_date') }} by
                               {{ Session::get('first_name') }} {{ Session::get('last_name') }}. No Printing is allowed!!
                             </p>
                         @endif

                         <div class="form-group">
                           <div class="col-md-12">

                             <div class="col-md-4">
                               <p>Payment Mode : {{ $generaldonation->mode_payment }}</p>

                               @if(Session::has('cancelled_date'))
                                <p><span class="text-white">Payment Mode :</span><span class="text-danger">(Refuned/ Returned)</span></p>

                               @endif

                             </div><!-- end col-md-4 -->

                             <div class="col-md-4">
                               @if(Session::has('cancelled_date'))
                                <p>Total Amount : S$ <span class="text-danger">{{ $sum }}</span></p>
                               @else
                                <p>Total Amount : S$ {{ $sum }}</p>
                               @endif
                             </div><!-- end col-md-4 -->

                             <div class="col-md-4">
                             </div><!-- end col-md-4 -->

                           </div><!-- end col-md-12 -->
                         </div><!-- end form-group -->

                         <div class="form-group">

                           <label>Type of Receipt Printing :</label>

                 					<div class="radio">
                 						<label>
                 							<input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'hj'){ ?>checked="checked"<?php }?>>
                 							1 Receipt Printing for same address <br />
                 						</label>
                 				  </div><!-- end radio -->

                 					<div class="radio">
                 						<label>
                 							<input type="radio" name="" disabled <?php if ($generaldonation->hjgr == 'gr'){ ?>checked="checked"<?php }?>>
                 							Individual Receipt Printing
                 						</label>
                 				  </div><!-- end radio -->

                        </div><!-- end form-group -->

                        <hr>

                        <form class="" action="{{ URL::to('/staff/receipt-cancellation') }}" method="post">
                          {!! csrf_field() !!}

                          <input type="hidden" name="receipt_id" value="{{ $receipt[0]->receipt_id }}">


                          <div class="form-group">

                            <div class="col-md-6">
                              @if(!Session::has('cancelled_date'))
                              <label class="col-md-4 control-label">Authorized Password</label>
                              <div class="col-md-4">
                                  <input type="password" class="form-control"
                                      name="authorized_password" id="authorized_password">
                              </div><!-- end col-md-4 -->
                              <div class="col-md-4">
                              </div><!-- end col-md-4 -->
                              @endif
                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                                @if(Session::has('cancelled_date'))
                                <p class="text-danger">
                                  This Transaction has been cancelled. <br />
                                  No Cancellation is allowed.
                                </p>
                                @endif
                            </div><!-- end col-md-6 -->

                          </div><!-- end form-group -->

                          <div class="form-group">
                            <div class="col-md-12">

                              <div class="col-md-6">
                                <div class="form-actions">
                                  @if(!Session::has('cancelled_date'))
                                    <button type="submit" class="btn blue" id="receipt_cancel_btn">Cancel & Replace Transaction</button>
                                    <a href="/staff/cancel-transaction/{{ $receipt[0]->receipt_id }}" class="btn default">Cancel Transaction</a>
                                  @endif
                                  <a href="/staff/donation" class="btn default">Back</a>
                                </div><!-- end form-actions -->
                              </div><!-- end col-md-6 -->

                              <div class="col-md-6">
                              </div><!-- end col-md-6 -->

                            </div><!-- end col-md-12 -->
                          </div><!-- end form-group -->

                        </form>

                       </div><!-- end form-body -->

                     </div><!-- end portlet-body -->

                   </div><!-- end portlet -->

                 </div><!-- end form-horizontal -->

               </div><!-- end col-md-9 -->

             </div><!-- end row -->

           </div><!-- end inbox -->

         </div><!-- end page-content-inner -->

        </div><!-- end container-fluid -->

      </div><!-- end page-content -->

    </div><!-- end page-content-wrapper -->

  </div><!-- end page-container-fluid -->

@stop

@section('custom-js')

<script src="{{asset('js/custom/common.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

  $(function() {

    $("#receipt_cancel_btn").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;
      var authorized_password = $("#authorized_password").val();

      if ($.trim(authorized_password).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Unauthorised User Access !! Changes will NOT be Saved !! Please re-enter Authorised User Access to save Changes !!"
      }

      if (validationFailed)
      {
          var errorMsgs = '';

          for(var i = 0; i < count; i++)
          {
              errorMsgs = errorMsgs + errors[i] + "<br/>";
          }

          $('html,body').animate({ scrollTop: 0 }, 'slow');

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(errorMsgs);

          return false;
      }

      else
      {
          $(".validation-error").removeClass("bg-danger alert alert-error")
          $(".validation-error").empty();
      }

    });
  });
</script>

@stop
