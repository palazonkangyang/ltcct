$(function() {

  $("#xiaozai_search_detail").click(function() {
    xiaozaiSearchDetail();
  });

  $('#receipt_no,#trans_no').keypress(function (e) {
    if (e.which == 13) {
      xiaozaiSearchDetail();
      return false;
    }
  });

  function xiaozaiSearchDetail(){
    var user_id = $("#user_id").val();
    var focusdevotee_id = $("#focusdevotee_id").val();

    $("#amount").removeClass('text-danger');
    $("#cancel-xiaozai-replace-btn").attr('disabled', false);
    $("#cancel-xiaozai-transaction").attr('disabled', false);
    $("#authorized_password").attr('disabled', false);
    $("#transaction-text").text('');
    $(".mt-radio").attr('disabled', false);
    $("#reprint-btn").attr('disabled', false);
    $("#refund").text('');

    $("#receipt").text('');
    $('#receipt_date').text('');
    $("#paid_by").text('');
    $("#donation_event").text('');
    $("#transaction_no").text('');
    $("#description").text('');
    $("#attended_by").text('');
    $("#payment_mode").text('');
    $("#amount").text(0);

    $('#transaction-table tbody').empty();
    $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

    $(".validation-error").removeClass("bg-danger alert alert-error")
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var receipt_no = $("#receipt_no").val();
    var trans_no = $("#trans_no").val();

    if(($.trim(receipt_no).length > 0) && ($.trim(trans_no).length > 0))
    {
      validationFailed = true;
      errors[count++] = "Receipt No or Transaction No can only search one time."
    }

    if (($.trim(receipt_no).length <= 0) && ($.trim(trans_no).length <= 0))
    {
      validationFailed = true;
      errors[count++] = "At least one field should not be empty."
    }

    if (validationFailed)
    {
      $("#xiaozai_trans_wrap1").hide();
      $("#xiaozai_trans_wrap2").hide();
      $("#xiaozai_trans_wrap3").hide();
      $("#xiaozai_trans_wrap4").hide();
      $("#xiaozai_trans_wrap5").hide();
      $("#xiaozai_trans_wrap6").hide();
      $("#xiaozai_trans_wrap7").hide();
      $("#xiaozai_trans_wrap8").hide();

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

    else {
      $(".validation-error").removeClass("bg-danger alert alert-error")
      $(".validation-error").empty();
    }

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      receipt_no: receipt_no,
      trans_no: trans_no
    };

    $.ajax({
      type: 'GET',
      url: "/fahui/transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#xiaozai_trans_wrap1").show();
        $("#xiaozai_trans_wrap2").show();
        $("#xiaozai_trans_wrap3").show();
        $("#xiaozai_trans_wrap4").show();
        $("#xiaozai_trans_wrap5").show();
        $("#xiaozai_trans_wrap6").show();
        $("#xiaozai_trans_wrap7").show();
        $("#xiaozai_trans_wrap8").show();

        $('#transaction-table tbody').empty();

        if(response.msg != null)
        {
          $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

          $('html,body').animate({ scrollTop: 0 }, 'slow');

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(response.msg);

          $("#receipt").text('');
          $("#receipt_date").text('');
          $("#paid_by").text('');
          $("#donation_event").text('');
          $("#transaction_no").text('');
          $("#description").text('');
          $("#attended_by").text('');
          $("#payment_mode").text('');
          $("#amount").text(0);

          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }

        if(response.transaction.length != 0)
        {
          var rowno = 1;
          var total_amount = 0;
          var count = response.transaction.length;

          $("#receipt").text(response.transaction.receipt);
          $('#receipt_date').text(response.transaction.trans_at);
          $("#description").text(response.transaction.description);
          $("#paid_by").text(response.transaction.paid_by + " (" + response.transaction.focusdevotee_id + ")");
          $("#donation_event").text(response.next_event.event);
          $("#transaction_no").text(response.transaction.trans_no);
          $("#attended_by").text(response.transaction.attended_by);
          $("#payment_mode").text(response.transaction.mode_payment);

          $.each(response.receipts, function(index, data) {

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.devotee_chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + data.type_chinese_name + "</td>" +
            "<td>" + (data.item_description != null ? data.item_description : '') + "</td>" +
            "<td>" + data.receipt_no + "</td>" +
            "<td>" + data.amount + "</td>");

            rowno++;
          });

          $("#amount").text(response.transaction.total_amount);
          $('.nav-tabs li:eq(1) a').tab('show');
        }

        else
        {
          $("#receipt").text('');
          $('#receipt_date').text('');
          $("#paid_by").text('');
          $("#donation_event").text('');
          $("#transaction_no").text('');
          $("#attended_by").text('');
          $("#amount").text(0);

          $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");
        }

        if(response.transaction.cancelled_date != null)
        {
          $("#transaction-text").text('');

          $("#amount").addClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled on ' + response.transaction.cancelled_date  + ' by ' + response.transaction.full_name + '. No Printing is allowed!!');
          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refunded/ Returned)');
        }

        else if (user_id == 5) {
          $("#transaction-text").text('');

          $("#amount").removeClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', false);
          $("#cancel-xiaozai-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction.focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
        }

        if(($.trim(receipt_no).length > 0) && ($.trim($("#amount").val() > 0)))
        {
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }
      },
      error: function (response) {
          console.log(response);
      }
    });
  }

  $("#xiaozai_receipt_history_table").on('click', '.xiaozai-receipt-id', function() {

    $(".alert-success").remove();

    var trans_no = $(this).attr("id");
    var focusdevotee_id = $("#focusdevotee_id").val();

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      receipt_no: "",
      trans_no: trans_no
    };

    $.ajax({
      type: 'GET',
      url: "/fahui/transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#xiaozai_trans_wrap1").show();
    		$("#xiaozai_trans_wrap2").show();
    		$("#xiaozai_trans_wrap3").show();
    		$("#xiaozai_trans_wrap4").show();
    		$("#xiaozai_trans_wrap5").show();
    		$("#xiaozai_trans_wrap6").show();
    		$("#xiaozai_trans_wrap7").show();
    		$("#xiaozai_trans_wrap8").show();

        $('#transaction-table tbody').empty();

        if(response.transaction.length != 0)
        {
          var rowno = 1;
          var total_amount = 0;
          var count = response.transaction.length;

          $("#receipt").text(response.transaction.receipt);
          $("#trans_no").val(response.transaction.trans_no);
          $('#receipt_date').text(response.transaction.trans_at);
          $("#description").text(response.transaction.description);
          $("#paid_by").text(response.transaction.paid_by + " (" + response.transaction.focusdevotee_id + ")");
          $("#donation_event").text(response.next_event.event);
          $("#transaction_no").text(response.transaction.trans_no);
          $("#attended_by").text(response.transaction.attended_by);
          $("#payment_mode").text(response.transaction.mode_payment);

          $.each(response.receipts, function(index, data) {

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.devotee_chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + data.type_chinese_name + "</td>" +
            "<td>" + (data.item_description != null ? data.item_description : '') + "</td>" +
            "<td>" + data.receipt_no + "</td>" +
            "<td>" + data.amount + "</td>");

            rowno++;
          });

          $("#amount").text(response.transaction.total_amount);
          $('.nav-tabs li:eq(1) a').tab('show');
        }

        else
        {
          $("#receipt").text('');
          $('#receipt_date').text('');
          $("#description").text('');
          $("#paid_by").text('');
          $("#donation_event").text('');
          $("#transaction_no").text('');
          $("#attended_by").text('');
          $("#amount").text(0);

          $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");
        }

        var user_id = $("#user_id").val();

        if(response.transaction.cancelled_date != null)
        {
          $("#transaction-text").text('');

          $("#amount").addClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled on ' + response.transaction.cancelled_date  + ' by ' + response.transaction.full_name + '. No Printing is allowed!!');
          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refunded/ Returned)');
        }

        else if (user_id == 5) {
          $("#transaction-text").text('');

          $("#amount").removeClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', false);
          $("#cancel-xiaozai-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction.focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
        }
      },
      error: function (response) {
        console.log(response);
      }
    });
  });

  $("#cancel-xiaozai-transaction").click(function() {

    $(".alert-success").remove();
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var trans_no = $("#trans_no").val();
    var authorized_password = $("#authorized_password").val();

    if($.trim(trans_no).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Transaction No field is empty.";
    }

    else
    {
      $("#hidden_transaction_no").val(trans_no);
    }

    if ($.trim(authorized_password).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.";
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

    else {
      $(".validation-error").removeClass("bg-danger alert alert-error")
      $(".validation-error").empty();
    }
  });

  $("#cancel-xiaozai-replace-btn").click(function() {

    $(".alert-success").remove();
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    // var receipt_no = $("#receipt_no").val();
    var trans_no = $("#trans_no").val();
    var amount = $("#amount").text();
    var authorized_password = $("#authorized_password").val();
    var description = $("#description").text();
    var total_devotee = 0;

    if ($.trim(authorized_password).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.";
    }

    if(amount == 0)
    {
      validationFailed = true;
      errors[count++] = "There is no record in the table.";
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

    else {
      $(".validation-error").removeClass("bg-danger alert alert-error")
      $(".validation-error").empty();
    }

    localStorage.setItem('cancel', 1);

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      //receipt_no: receipt_no,
      authorized_password: authorized_password,
      mod_id: 5,
      trans_no: trans_no
    };

    $('#xiaozai-form')[0].reset();

    $.ajax({
      type: 'POST',
      url: "/fahui/cancel-and-replace-transaction",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        if(response.error != "")
        {
          $('html,body').animate({ scrollTop: 0 }, 'slow');

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(response.error);
        }

        else{
          // total_devotee = response.total_devotee;

          //alert(JSON.stringify(response.receipt));

          // $("#xiaozai_trans_wrap1").hide();
      		// $("#xiaozai_trans_wrap2").hide();
      		// $("#xiaozai_trans_wrap3").hide();
      		// $("#xiaozai_trans_wrap4").hide();
      		// $("#xiaozai_trans_wrap5").hide();
      		// $("#xiaozai_trans_wrap6").hide();
      		// $("#xiaozai_trans_wrap7").hide();
      		// $("#xiaozai_trans_wrap8").hide();
          //
          // $('#transaction-table tbody').empty();
          // $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

          // $("#receipt_date").text('');
          // $("#paid_by").text('');
          // $("#donation_event").text('');
          // $("#receipt").text('');
          // $("#transaction_no").text('');
          // $("#description").text('');
          // $("#attended_by").text('');
          // $("#payment_mode").text('');
          // $("#amount").text('');

          $('.nav-tabs li:eq(0) a').tab('show');

          // $("#xiaozai_table tbody tr").remove();
          // $("#xiaozai_table2 tbody tr").remove();

          // $.each(response.receipt, function(index, data) {
            // $td_is_checked       = '<td>' +
            //                         '<input type="checkbox" class="amount checkbox-multi-select-module-xiaozai-tab-xiaozai-section-sfc" name="xiaozai_amount[]" value="1" '+
            //                         // if($data.is_checked == 1){
            //                         //    'checked' +
            //                         //  }
            //
            //                          '>' +
            //                         '<input type="hidden" class="form-control is_checked_list" name="is_checked_list[]" value="">' +
            //                        '</td>';
            // $td_chinese_name     = "<td>"+ data.devotee_chinese_name +"</td>";
            // $td_devotee_id       = "<td>"+ data.devotee_id +"</td>";
            // $td_register_by      = "<td></td>";
            // $td_gy               = "<td></td>";
            // $td_ops              = "<td></td>";
            // $td_item_description = "<td>"+ data.item_description +"</td>";
            // $td_xz_receipt       = "<td>"+ data.receipt_no +"</td>";
            // $td_paid_by          = "<td>"+ response.transaction.paid_by +"</td>";
            // $td_trans_date       = "<td>"+ response.transaction.trans_at +"</td>";

            // if(data.type == "base_home"){
            //   $td_type = "<td><select class='type' name='hjgr[]'><option value='hj' selected>合家</option><option value='gr'>个人</option></select></td>";
            // }
            // else if(data.type == "home"){
            //   $td_type = "<td><select class='type' name='hjgr[]'><option value='hj' selected>合家</option><option value='gr'>个人</option></select></td>";
            // }
            // else if(data.type == "company"){
            //   $td_type = "<td>公司<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(data.type == "stall"){
            //   $td_type = "<td>小贩<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(data.type == "office"){
            //   $td_type = "<td>个人<input type='hidden' name='hjgr[]'  value='gr' /></td>";
            // }
            // else if(data.type == "car"){
            //   $td_type = "<td>车辆<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(data.type == "ship"){
            //   $td_type = "<td>船只<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else{
            //   $td_type = "<td><input type='hidden' name='hjgr[]'  value='' /></td>";
            // }

            // $('#xiaozai_table tbody').append("" +
            // "<tr>" +
            //   $td_is_checked +
            //   $td_chinese_name +
            //   $td_devotee_id +
            //   $td_register_by +
            //   $td_gy +
            //   $td_ops +
            //   $td_type +
            //   $td_item_description +
            //   $td_xz_receipt +
            //   $td_paid_by +
            //   $td_trans_date +
            // "</tr>");
            // $("#xiaozai_table tbody tr").each(function() {
            //   var devotee = $(this).find("#devotee").text();
            //   var type = $(this).find("input[name='type[]']").val();
            //
            //   if(devotee == data.devotee_id && type == data.type)
            //   {
            //     $(this).find('.amount').attr('checked', true);
            //   }
            // });

            // $("#xiaozai_table2 tbody tr").each(function() {
            //   var devotee = $(this).find("#devotee").text();
            //   var type = $(this).find("input[name='type[]']").val();
            //
            //   if(devotee == data.devotee_id && type == data.type)
            //   {
            //     $(this).find('.amount').attr('checked', true);
            //   }
            // });
          // });


          // var hj_sum = 0;
          // var gr_sum = 0;
          // var company_sum = 0;
          // var stall_sum = 0;
          // var car_sum = 0;
          // var ship_sum = 0;
          // var total = 0;

          // $("input[name='xiaozai_amount[]']").each( function () {
          //   if( $(this).is(":checked") == true ) {
          //     $(this).closest('.xiaozai-amount-col').find('.address_total_type').prop('checked',true);
          //     $(this).closest('.xiaozai-amount-col').find('.individual_office_total_type').prop('checked',true);
          //     $(this).closest('.xiaozai-amount-col').find('.company_total_type').prop('checked',true);
          //     $(this).closest('.xiaozai-amount-col').find('.vehicle_total_type').prop('checked',true);
          //   }
          //
          //   else {
          //     $(this).closest('.xiaozai-amount-col').find('.address_total_type').prop('checked',false);
          //     $(this).closest('.xiaozai-amount-col').find('.individual_office_total_type').prop('checked',false);
          //     $(this).closest('.xiaozai-amount-col').find('.company_total_type').prop('checked',false);
          //     $(this).closest('.xiaozai-amount-col').find('.vehicle_total_type').prop('checked',false);
          //   }
          // });

          // var address_length = $("input[name='address_total_type[]']:checked").length;
          // var individual_office_length = $("input[name='individual_office_total_type[]']:checked").length;
          // var company_length = $("input[name='company_total_type[]']:checked").length;
          // var vehicle_length = $("input[name='vehicle_total_type[]']:checked").length;
          //
          // $(".address_total").html(address_length);
          // $(".individual_office_total").html(individual_office_length);
          // $(".company_total").html(company_length);
          // $(".vehicle_total").html(vehicle_length);

          // address_sum += address_length * 30;
          // individual_office_sum += individual_office_length * 20;
          // company_sum += company_length * 100;
          // vehicle_sum += vehicle_length * 30;

          // $(".address_total_amount").html(address_sum);
          // $(".individual_office_total_amount").html(individual_office_sum);
          // $(".company_total_amount").html(company_sum);
          // $(".vehicle_total_amount").html(vehicle_sum);

          // total = address_sum + individual_office_sum + company_sum + vehicle_sum;

          // $(".total_payable").html(1);
          // $("#total_amount").val(1);

          // $("#authorized_password").val('');
          // $("#trans_no").val('');

          $("#transaction_wrap").show();
          $('input[name="trans_no_to_cancel"]').val(response.transaction.trans_no);
          // if(response.transaction.trans_no != "")
          // {
            $("#trans_info").html("<span style='font-weight: bold'>" + response.transaction.trans_no + "</span>" + " <span class='text-danger'>is about to Cancel & Replace.</span>");
          // }

          if(response.error == "not match")
          {
            $('html,body').animate({ scrollTop: 0 }, 'slow');

            $(".validation-error").addClass("bg-danger alert alert-error")
            $(".validation-error").html("Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.");
          }

          else
          {
            $(".validation-error").removeClass("bg-danger alert alert-error")
            $(".validation-error").empty();
          }


        }

      },
      error: function (response) {
        console.log(response);
      }
    });

  });

});
