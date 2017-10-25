$(function() {

  $("#kongdan_search_detail").click(function() {

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
      $("#kongdan_trans_wrap1").hide();
  		$("#kongdan_trans_wrap2").hide();
  		$("#kongdan_trans_wrap3").hide();
  		$("#kongdan_trans_wrap4").hide();
  		$("#kongdan_trans_wrap5").hide();
  		$("#kongdan_trans_wrap6").hide();
  		$("#kongdan_trans_wrap7").hide();
  		$("#kongdan_trans_wrap8").hide();

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
      url: "/fahui/kongdan-transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#kongdan_trans_wrap1").show();
    		$("#kongdan_trans_wrap2").show();
    		$("#kongdan_trans_wrap3").show();
    		$("#kongdan_trans_wrap4").show();
    		$("#kongdan_trans_wrap5").show();
    		$("#kongdan_trans_wrap6").show();
    		$("#kongdan_trans_wrap7").show();
    		$("#kongdan_trans_wrap8").show();

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

          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
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

            if(data.address_unit1 != null && data.address_unit2 != null)
            {
              var full_address = data.address_houseno + ", #" + data.address_unit1 + "-" + data.address_unit2 + ", " + data.address_street + ", " + data.address_postal;
            }
            else
            {
              var full_address = data.address_houseno + ", " + data.address_street + ", " + data.address_postal;
            }

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
            "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
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
          $("#cancel-kongdan-replace-btn").attr('disabled', false);
          $("#cancel-kongdan-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
        }

        if(($.trim(receipt_no).length > 0) && ($.trim($("#amount").val() > 0)))
        {
          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }
      },
      error: function (response) {
          console.log(response);
      }
    });

  });

  $("#cancel-kongdan-transaction").click(function() {

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

  $("#kongdan_receipt_history_table").on('click', '.kongdan-receipt-id', function() {

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
      url: "/fahui/kongdan-transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#kongdan_trans_wrap1").show();
    		$("#kongdan_trans_wrap2").show();
    		$("#kongdan_trans_wrap3").show();
    		$("#kongdan_trans_wrap4").show();
    		$("#kongdan_trans_wrap5").show();
    		$("#kongdan_trans_wrap6").show();
    		$("#kongdan_trans_wrap7").show();
    		$("#kongdan_trans_wrap8").show();

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

            if(data.address_unit1 != null && data.address_unit2 != null)
            {
              var full_address = data.address_houseno + ", #" + data.address_unit1 + "-" + data.address_unit2 + ", " + data.address_street + ", " + data.address_postal;
            }
            else
            {
              var full_address = data.address_houseno + ", " + data.address_street + ", " + data.address_postal;
            }

            $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
            "<td>" + data.chinese_name + "</td>" +
            "<td>" + data.devotee_id + "</td>" +
            "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
            "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
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
          $("#cancel-kongdan-replace-btn").attr('disabled', false);
          $("#cancel-kongdan-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-kongdan-replace-btn").attr('disabled', true);
          $("#cancel-kongdan-transaction").attr('disabled', true);
        }
      },
      error: function (response) {
        console.log(response);
      }
    });
  });

  $("#cancel-kongdan-replace-btn").click(function() {

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

    $('#kongdan-form')[0].reset();

    $.ajax({
      type: 'POST',
      url: "/fahui/kongdan-cancel-replace-transaction",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        total_devotee = response.total_devotee;

        $("#kongdan_trans_wrap1").hide();
    		$("#kongdan_trans_wrap2").hide();
    		$("#kongdan_trans_wrap3").hide();
    		$("#kongdan_trans_wrap4").hide();
    		$("#kongdan_trans_wrap5").hide();
    		$("#kongdan_trans_wrap6").hide();
    		$("#kongdan_trans_wrap7").hide();
    		$("#kongdan_trans_wrap8").hide();

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

          $("#kongdan_table tbody tr").each(function() {
            var devotee = $(this).find("#devotee").text();

            if(devotee == data.devotee_id)
            {
              $(this).find('.amount').attr('checked', true);
            }
          });

          $("#kongdan_table2 tbody tr").each(function() {
            var devotee = $(this).find("#devotee").text();

            if(devotee == data.devotee_id)
            {
              $(this).find('.amount').attr('checked', true);
            }
          });
        });

        $(".total").html(total_devotee);
        $(".total_amount").text(amount);
        $("#total_amount").val(amount);

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
