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

                             <div class="col-md-6">
                               <p>Receipt Date : {{ \Carbon\Carbon::parse($receipt[0]->trans_date)->format("d/m/Y") }}</p>
                               <p>Paid By : {{ $receipt[0]->chinese_name }} (D - {{ $receipt[0]->devotee_id }})</p>
                               <p>Description : {{ $receipt[0]->description }}</p>
                               <p>Donation for next Event : </p>
                             </div><!-- end col-md-6 -->

                             <div class="col-md-6">
                               <p>Receipt No : {{ $receipt[0]->xy_receipt }}</p>
                               <p>Transaction No : {{ $receipt[0]->trans_no }}</p>
                               <p>Attended By : </p>
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
                     						<th>Block</th>
                     						<th>Address</th>
                     						<th>Unit</th>
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
                     						<td>{{ $donation_devotee->address_houseno }}</td>
                     						<td>{{ $donation_devotee->address_street }}</td>
                     						<td>{{ $donation_devotee->address_unit1 }} {{ $donation_devotee->address_unit2 }}</td>
                     						<td>{{ $donation_devotee->hjgr }}</td>
                     						<td>{{ $receipt[0]->xy_receipt }}</td>
                     						<td>S$ {{ $donation_devotee->amount }}</td>
                     					</tr>

                     					@php $count++;  $sum += $donation_devotee->amount; @endphp

                     					@endforeach
                     				</tbody>
                     			</table>

                         </div><!-- end form-group -->

                         <div class="form-group">
                           <p>Payment Mode : {{ $generaldonation->mode_payment }}</p>
                           <p>Total Amount : S$ {{ $sum }}</p>
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
