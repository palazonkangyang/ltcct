
$(function() {

  var des = $("#description").text();

  if(des == "月捐")
  {
    $("#amount").removeClass('text-danger');
    $("#cancel-replace-btn").attr('disabled', false);
    $("#cancel-transaction").attr('disabled', false);
    $("#authorized_password").attr('disabled', false);
    $("#transaction-text").text('');
    $(".mt-radio").attr('disabled', false);
    $("#reprint-btn").attr('disabled', false);
    $("#refund").text('');
  }

  $("#receipt_history_table").on('click', '.receipt-id', function() {

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
      url: "/staff/transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#trans_wrap1").show();
  			$("#trans_wrap2").show();
  			$("#trans_wrap3").show();
  			$("#trans_wrap4").show();
  			$("#trans_wrap5").show();
  			$("#trans_wrap6").show();
  			$("#trans_wrap7").show();
  			$("#trans_wrap8").show();

        $('#transaction-table tbody').empty();

        if(response.transaction.length != 0)
        {
          var rowno = 1;
          var total_amount = 0;
          var count = response.transaction.length;

          if(count > 1)
          {
            $("#receipt").text(response.transaction[0].xy_receipt + " - " + response.transaction[count - 1].xy_receipt);
          }
          else
          {
            $("#receipt").text(response.transaction[0].xy_receipt);
          }

          if(response.transaction[0].description == "General Donation - 慈济")
          {
            description = '慈济';
          }
          else if (response.transaction[0].description == "General Donation - 香油") {
            description = "香油";
          }
          else
          {
            description = "月捐";
          }

          $("#col-header").text("HJ/ GR");
          $("#col-header").css("width", "10%");
          $("#col-member").hide();

          $("#trans_no").val(response.transaction[0].trans_no);
          $('#receipt_date').text(response.transaction[0].trans_date);
          $("#description").text(description);
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
            "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
            "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
            "<td>" + data.xy_receipt + "</td>" +
            "<td>" + data.amount + "</td>");

            rowno++;
            total_amount += data.amount;
          });

          $("#amount").text(total_amount);
          $('.nav-tabs li:eq(4) a').tab('show');
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
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
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
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-replace-btn").attr('disabled', false);
          $("#cancel-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
        }
      },
      error: function (response) {
        console.log(response);
      }
    });
  });

  $("#search_detail").click(function() {

    var user_id = $("#user_id").val();
    var focusdevotee_id = $("#focusdevotee_id").val();

    $("#amount").removeClass('text-danger');
    $("#cancel-replace-btn").attr('disabled', false);
    $("#cancel-transaction").attr('disabled', false);
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
      $("#trans_wrap1").hide();
			$("#trans_wrap2").hide();
			$("#trans_wrap3").hide();
			$("#trans_wrap4").hide();
			$("#trans_wrap5").hide();
			$("#trans_wrap6").hide();
			$("#trans_wrap7").hide();
			$("#trans_wrap8").hide();

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
      url: "/staff/transaction-detail",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $("#trans_wrap1").show();
  			$("#trans_wrap2").show();
  			$("#trans_wrap3").show();
  			$("#trans_wrap4").show();
  			$("#trans_wrap5").show();
  			$("#trans_wrap6").show();
  			$("#trans_wrap7").show();
  			$("#trans_wrap8").show();

        $('#transaction-table tbody').empty();

        if(response.msg != null)
        {
          $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

          $('html,body').animate({ scrollTop: 0 }, 'slow');

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(response.msg);

          $("#receipt").text('');
          $('#receipt_date').text('');
          $("#paid_by").text('');
          $("#donation_event").text('');
          $("#transaction_no").text('');
          $("#description").text('');
          $("#attended_by").text('');
          $("#payment_mode").text('');
          $("#amount").text(0);

          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }

        if(response.transaction.length != 0)
        {
          var rowno = 1;
          var total_amount = 0;
          var count = response.transaction.length;

          if(count > 1)
          {
            $("#receipt").text(response.transaction[0].xy_receipt + " - " + response.transaction[count - 1].xy_receipt);
          }
          else
          {
            $("#receipt").text(response.transaction[0].xy_receipt);
          }

          if(response.transaction[0].description == "General Donation - 慈济")
          {
            description = '慈济';
          }
          else if (response.transaction[0].description == "General Donation - 香油") {
            description = "香油";
          }
          else
          {
            description = "月捐";
          }

          if(response.transaction[0].description == "General Donation - 月捐")
          {
            $("#col-header").text("Paid For");
            $("#col-header").css("width", "15%");
            $("#col-member").show();
          }

          else
          {
            $("#col-header").text("HJ/ GR");
            $("#col-header").css("width", "10%");
            $("#col-member").hide();
          }

          $("#description").text(description);
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

            if(response.transaction[0].description == "General Donation - 月捐")
            {
              $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
              "<td>" + data.chinese_name + "</td>" +
              "<td>" + data.devotee_id + "</td>" +
              "<td>" + data.member_id + "</td>" +
              "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
              "<td>" + data.paid_for + "</td>" +
              "<td>" + data.xy_receipt + "</td>" +
              "<td>" + data.amount + "</td>");
            }

            else
            {
              $('#transaction-table tbody').append("<tr><td>" + rowno + "</td>" +
              "<td>" + data.chinese_name + "</td>" +
              "<td>" + data.devotee_id + "</td>" +
              "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
              "<td>" + (data.hjgr == 'hj' ? '合家' : '个人') + "</td>" +
              "<td>" + data.xy_receipt + "</td>" +
              "<td>" + data.amount + "</td>");
            }

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
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
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
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-replace-btn").attr('disabled', false);
          $("#cancel-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
        }

        if(($.trim(receipt_no).length > 0) && ($.trim($("#amount").val() > 0)))
        {
          $("#cancel-replace-btn").attr('disabled', true);
          $("#cancel-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }
      },
      error: function (response) {
          console.log(response);
      }
    });

  });

  $("#cancel-replace-btn").click(function() {

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

    $('#donation-form')[0].reset();

    $.ajax({
      type: 'POST',
      url: "/staff/cancel-replace-transaction",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $('#transaction-table tbody').empty();
        $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");

        $("#receipt_date").text('');
        $("#paid_by").text('');
        $("#donation_event").text('');
        $("#receipt").text('');
        $("#transaction_no").text('');
        $("#attended_by").text('');
        $("#payment_mode").text('');
        $("#amount").text('');

        if(description == "香油")
        {
          $('.nav-tabs li:eq(0) a').tab('show');

          $.each(response.receipt, function(index, data) {

            $("#generaldonation_table tbody tr").each(function() {
              var devotee = $(this).find("#devotee").text();

              if(devotee == data.devotee_id)
              {
                $(this).find('.amount').val(data.amount);
                $(this).find('.hjgr').val(data.hjgr);
                $(this).find('.display').val(data.display);
              }
            });

            $("#generaldonation_table2 tbody tr").each(function() {
              var devotee = $(this).find("#devotee").text();

              if(devotee == data.devotee_id)
              {
                $(this).find('.amount').val(data.amount);
                $(this).find('.hjgr').val(data.hjgr);
                $(this).find('.display').val(data.display);
              }
            });
          });

          $(".total").html(amount);
          $("#total_amount").val(amount);
        }

        else if (description == "慈济") {
          $('.nav-tabs li:eq(1) a').tab('show');

          $.each(response.receipt, function(index, data) {

            $("#ciji_generaldonation_table tbody tr").each(function() {
              var devotee = $(this).find("#devotee").text();

              if(devotee == data.devotee_id)
              {
                $(this).find('.ciji-amount').val(data.amount);
                $(this).find('.ciji-hjgr').val(data.hjgr);
                $(this).find('.ciji-display').val(data.display);
              }
            });

            $("#ciji_generaldonation_table2 tbody tr").each(function() {
              var devotee = $(this).find("#devotee").text();

              if(devotee == data.devotee_id)
              {
                $(this).find('.ciji-amount').val(data.amount);
                $(this).find('.ciji-hjgr').val(data.hjgr);
                $(this).find('.ciji-display').val(data.display);
              }
            });
          });

          $(".ciji_total").html(amount);
          $("#ciji_total_amount").val(amount);
        }

        else
        {

        }

        $("#authorized_password").val('');
        $("#trans_no").val('');


        if(trans_no != "")
        {
          $("#trans_info").html(trans_no + " <span class='text-danger'>is about to cancel and replace.</span>");
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

  $("#cancel-transaction").click(function() {

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

    // var formData = {
    //   _token: $('meta[name="csrf-token"]').attr('content'),
    //   trans_no: trans_no,
    //   authorized_password
    // };
    //
    // $.ajax({
    //   type: 'POST',
    //   url: "/staff/cancel-transaction",
    //   data: formData,
    //   dataType: 'json',
    //   success: function(response)
    //   {
    //     $("#search_detail").click();
    //   },
    //   error: function (response) {
    //       console.log(response);
    //   }
    // });
  });

  $('#reprint-btn').click(function() {

    $(".alert-success").remove();
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var amount = $("#amount").text();

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
  });

});
