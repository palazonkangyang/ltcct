
$(function() {

  $("#receipt_history_table").on('click', '.receipt-id', function() {

    var trans_no = $(this).attr("id");

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
      },
      error: function (response) {
          console.log(response);
      }
    });
  });

  $("#search_detail").click(function() {

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
        $('#transaction-table tbody').empty();

        if(response.msg != null)
        {
          $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");
          alert(response.msg);
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

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      receipt_no: receipt_no,
      trans_no: trans_no,
      authorized_password
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
