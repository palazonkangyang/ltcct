$(function() {

  $("#xiaozai_search_detail").click(function() {

    var user_id = $("#user_id").val();
    var focusdevotee_id = $("#focusdevotee_id").val();

    $("#amount").removeClass('text-danger');
    $("#cancel-kongdan-replace-btn").attr('disabled', false);
    $("#cancel-kongdan-transaction").attr('disabled', false);
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
      url: "/fahui/xiaozai-transaction-detail",
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

          if(count > 1)
          {
            $("#receipt").text(response.transaction[0].receipt_no + " - " + response.transaction[count - 1].receipt_no);
          }
          else
          {
            $("#receipt").text(response.transaction[0].receipt_no);
          }

          $("#description").text(response.transaction[0].description);
          $('#receipt_date').text(response.transaction[0].trans_date);
          $("#paid_by").text(response.focusdevotee + " (D - " + response.transaction[0].focusdevotee_id + ")");
          $("#donation_event").text((response.transaction[0].event !=null ? response.transaction[0].event : ''));
          $("#transaction_no").text(response.transaction[0].trans_no);
          $("#attended_by").text(response.transaction[0].first_name + " " + response.transaction[0].last_name);
          $("#payment_mode").text(response.transaction[0].mode_payment);

          $.each(response.transaction, function(index, data) {

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + data.chinese_type + "</td>" +
            "<td>" + (data.item_description != null ? data.item_description : '') + "</td>" +
            "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
            "<td>" + data.receipt_no + "</td>" +
            "<td>" + data.amount + "</td>");

            rowno++;
            total_amount += data.amount;
          });

          $("#amount").text(total_amount);
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

        if(response.cancellation[0]['cancelled_date'] != null)
        {
          $("#transaction-text").text('');

          $("#amount").addClass('text-danger');
          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled by ' +
            response.cancellation[0]['cancelled_date']  + ' by ' + response.cancellation[0]['first_name'] + ' ' + response.cancellation[0]['last_name']
            + '. No Printing is allowed!!');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refuned/ Returned)');
        }

        else if (user_id == 5) {
          $("#transaction-text").text('');

          $("#amount").removeClass('text-danger');
          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
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

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
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

  });

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
      url: "/fahui/xiaozai-transaction-detail",
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

          if(count > 1)
          {
            $("#receipt").text(response.transaction[0].receipt_no + " - " + response.transaction[count - 1].receipt_no);
          }
          else
          {
            $("#receipt").text(response.transaction[0].receipt_no);
          }

          $("#trans_no").val(response.transaction[0].trans_no);
          $('#receipt_date').text(response.transaction[0].trans_date);
          $("#description").text(response.transaction[0].description);
          $("#paid_by").text(response.focusdevotee + " (D - " + response.transaction[0].focusdevotee_id + ")");
          $("#donation_event").text((response.transaction[0].event !=null ? response.transaction[0].event : ''));
          $("#transaction_no").text(response.transaction[0].trans_no);
          $("#attended_by").text(response.transaction[0].first_name + " " + response.transaction[0].last_name);
          $("#payment_mode").text(response.transaction[0].mode_payment);

          $.each(response.transaction, function(index, data) {

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + data.chinese_type + "</td>" +
            "<td>" + (data.item_description != null ? data.item_description : '') + "</td>" +
            "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
            "<td>" + data.receipt_no + "</td>" +
            "<td>" + data.amount + "</td>");

            rowno++;
            total_amount += data.amount;
          });

          $("#amount").text(total_amount);
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

        if(response.cancellation[0]['cancelled_date'] != null)
        {
          $("#transaction-text").text('');

          $("#amount").addClass('text-danger');
          $("#cancel-xiaozai-replace-btn").attr('disabled', true);
          $("#cancel-xiaozai-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled by ' +
            response.cancellation[0]['cancelled_date']  + ' by ' + response.cancellation[0]['first_name'] + ' ' + response.cancellation[0]['last_name']
            + '. No Printing is allowed!!');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refuned/ Returned)');
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

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
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

    var receipt_no = $("#receipt_no").val();
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
      receipt_no: receipt_no,
      trans_no: trans_no,
      authorized_password: authorized_password
    };

    $('#xiaozai-form')[0].reset();

    $.ajax({
      type: 'POST',
      url: "/fahui/xiaozai-cancel-replace-transaction",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        total_devotee = response.total_devotee;

        alert(JSON.stringify(response.receipt));

        $("#xiaozai_trans_wrap1").hide();
    		$("#xiaozai_trans_wrap2").hide();
    		$("#xiaozai_trans_wrap3").hide();
    		$("#xiaozai_trans_wrap4").hide();
    		$("#xiaozai_trans_wrap5").hide();
    		$("#xiaozai_trans_wrap6").hide();
    		$("#xiaozai_trans_wrap7").hide();
    		$("#xiaozai_trans_wrap8").hide();

        $('#transaction-table tbody').empty();
        $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

        $("#receipt_date").text('');
        $("#paid_by").text('');
        $("#donation_event").text('');
        $("#receipt").text('');
        $("#transaction_no").text('');
        $("#description").text('');
        $("#attended_by").text('');
        $("#payment_mode").text('');
        $("#amount").text('');

        $('.nav-tabs li:eq(0) a').tab('show');

        $.each(response.receipt, function(index, data) {

          $("#xiaozai_table tbody tr").each(function() {
            var devotee = $(this).find("#devotee").text();
            var type = $(this).find("input[name='type[]']").val();

            if(devotee == data.devotee_id && type == data.type)
            {
              $(this).find('.amount').attr('checked', true);
            }
          });

          $("#xiaozai_table2 tbody tr").each(function() {
            var devotee = $(this).find("#devotee").text();
            var type = $(this).find("input[name='type[]']").val();

            if(devotee == data.devotee_id && type == data.type)
            {
              $(this).find('.amount').attr('checked', true);
            }
          });
        });

        var address_sum = 0;
        var individual_office_sum = 0;
        var company_sum = 0;
        var vehicle_sum = 0;
        var total = 0;

        $("input[name='xiaozai_amount[]']").each( function () {

          if( $(this).is(":checked") == true ) {
            $(this).closest('.xiaozai-amount-col').find('.address_total_type').prop('checked',true);
            $(this).closest('.xiaozai-amount-col').find('.individual_office_total_type').prop('checked',true);
            $(this).closest('.xiaozai-amount-col').find('.company_total_type').prop('checked',true);
            $(this).closest('.xiaozai-amount-col').find('.vehicle_total_type').prop('checked',true);
          }

          else {
            $(this).closest('.xiaozai-amount-col').find('.address_total_type').prop('checked',false);
            $(this).closest('.xiaozai-amount-col').find('.individual_office_total_type').prop('checked',false);
            $(this).closest('.xiaozai-amount-col').find('.company_total_type').prop('checked',false);
            $(this).closest('.xiaozai-amount-col').find('.vehicle_total_type').prop('checked',false);
          }
        });

        var address_length = $("input[name='address_total_type[]']:checked").length;
        var individual_office_length = $("input[name='individual_office_total_type[]']:checked").length;
        var company_length = $("input[name='company_total_type[]']:checked").length;
        var vehicle_length = $("input[name='vehicle_total_type[]']:checked").length;

        $(".address_total").html(address_length);
        $(".individual_office_total").html(individual_office_length);
        $(".company_total").html(company_length);
        $(".vehicle_total").html(vehicle_length);

        address_sum += address_length * 30;
        individual_office_sum += individual_office_length * 20;
        company_sum += company_length * 100;
        vehicle_sum += vehicle_length * 30;

        $(".address_total_amount").html(address_sum);
        $(".individual_office_total_amount").html(individual_office_sum);
        $(".company_total_amount").html(company_sum);
        $(".vehicle_total_amount").html(vehicle_sum);

        total = address_sum + individual_office_sum + company_sum + vehicle_sum;

        $(".total_payable").html(total);
        $("#total_amount").val(total);

        $("#authorized_password").val('');
        $("#trans_no").val('');

        $("#transaction_wrap").show();

        if(trans_no != "")
        {
          $("#trans_info").html("<span style='font-weight: bold'>" + trans_no + "</span>" + " <span class='text-danger'>is about to Cancel & Replace.</span>");
        }

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
      },
      error: function (response) {
        console.log(response);
      }
    });

  });

});
