$(function() {

  $("#qifu_search_detail").click(function() {
    qifuSearchDetail();
  });

  $('#receipt_no,#trans_no').keypress(function (e) {
    if (e.which == 13) {
      qifuSearchDetail();
      return false;
    }
  });

  function qifuSearchDetail(){
    var user_id = $("#user_id").val();
    var focusdevotee_id = $("#focusdevotee_id").val();

    $("#amount").removeClass('text-danger');
    $("#cancel-qifu-replace-btn").attr('disabled', false);
    $("#cancel-qifu-transaction").attr('disabled', false);
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
      $("#qifu_trans_wrap1").hide();
      $("#qifu_trans_wrap2").hide();
      $("#qifu_trans_wrap3").hide();
      $("#qifu_trans_wrap4").hide();
      $("#qifu_trans_wrap5").hide();
      $("#qifu_trans_wrap6").hide();
      $("#qifu_trans_wrap7").hide();
      $("#qifu_trans_wrap8").hide();

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
        $("#qifu_trans_wrap1").show();
        $("#qifu_trans_wrap2").show();
        $("#qifu_trans_wrap3").show();
        $("#qifu_trans_wrap4").show();
        $("#qifu_trans_wrap5").show();
        $("#qifu_trans_wrap6").show();
        $("#qifu_trans_wrap7").show();
        $("#qifu_trans_wrap8").show();

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

          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
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
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled on ' + response.transaction.cancelled_date  + ' by ' + response.transaction.full_name + '. No Printing is allowed!!');
          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refunded/ Returned)');
        }

        else if (user_id == 5) {
          $("#transaction-text").text('');

          $("#amount").removeClass('text-danger');
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-qifu-replace-btn").attr('disabled', false);
          $("#cancel-qifu-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction[0].focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
        }

        if(($.trim(receipt_no).length > 0) && ($.trim($("#amount").val() > 0)))
        {
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
        }
      },
      error: function (response) {
        console.log(response);
      }
    });

  }

  $("#cancel-qifu-transaction").click(function() {

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

  $("#qifu_receipt_history_table").on('click', '.qifu-receipt-id', function() {

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
        $("#qifu_trans_wrap1").show();
        $("#qifu_trans_wrap2").show();
        $("#qifu_trans_wrap3").show();
        $("#qifu_trans_wrap4").show();
        $("#qifu_trans_wrap5").show();
        $("#qifu_trans_wrap6").show();
        $("#qifu_trans_wrap7").show();
        $("#qifu_trans_wrap8").show();

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
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").append('This Transaction has been cancelled on ' + response.transaction.cancelled_date  + ' by ' + response.transaction.full_name + '. No Printing is allowed!!');
          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', true);
          $("#refund").text('(Refunded/ Returned)');
        }

        else if (user_id == 5) {
          $("#transaction-text").text('');

          $("#amount").removeClass('text-danger');
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
          $("#authorized_password").attr('disabled', true);
          $("#transaction-text").text('');

          $(".mt-radio").attr('disabled', true);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        else
        {
          $("#amount").removeClass('text-danger');
          $("#cancel-qifu-replace-btn").attr('disabled', false);
          $("#cancel-qifu-transaction").attr('disabled', false);
          $("#authorized_password").attr('disabled', false);
          $("#transaction-text").text('');
          $(".mt-radio").attr('disabled', false);
          $("#reprint-btn").attr('disabled', false);
          $("#refund").text('');
        }

        if(response.transaction.focusdevotee_id != focusdevotee_id)
        {
          $("#cancel-qifu-replace-btn").attr('disabled', true);
          $("#cancel-qifu-transaction").attr('disabled', true);
        }
      },
      error: function (response) {
        console.log(response);
      }
    });


  });

  // cancel-qifu-replace-btn start
  $("#cancel-qifu-replace-btn").click(function() {
    $(".alert-success").remove();
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    //var receipt_no = $("#receipt_no").val();
    var trans_no = $("#trans_no").val();
    var amount = $("#amount").text();
    var authorized_password = $("#authorized_password").val();
    var description = $("#description").text();
    var total_devotee = 0;
    //
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
    //
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
      authorized_password: authorized_password,
      mod_id: 9,
      trans_no: trans_no
    };

    $('#qifu-form')[0].reset();

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
          $('.nav-tabs li:eq(0) a').tab('show');
          $("#transaction_wrap").show();
          $('input[name="trans_no_to_cancel"]').val(response.transaction.trans_no);
          $("#trans_info").html("<span style='font-weight: bold'>" + response.transaction.trans_no + "</span>" + " <span class='text-danger'>is about to Cancel & Replace.</span>");
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
        // total_devotee = response.total_devotee;
        //
        // $("#qifu_trans_wrap1").hide();
        // $("#qifu_trans_wrap2").hide();
        // $("#qifu_trans_wrap3").hide();
        // $("#qifu_trans_wrap4").hide();
        // $("#qifu_trans_wrap5").hide();
        // $("#qifu_trans_wrap6").hide();
        // $("#qifu_trans_wrap7").hide();
        // $("#qifu_trans_wrap8").hide();
        //
        // $('#transaction-table tbody').empty();
        // $('#transaction-table tbody').append("<tr><td colspan='7'>No Result Found</td></tr>");
        //
        // $("#receipt_date").text('');
        // $("#paid_by").text('');
        // $("#donation_event").text('');
        // $("#receipt").text('');
        // $("#transaction_no").text('');
        // $("#description").text('');
        // $("#attended_by").text('');
        // $("#payment_mode").text('');
        // $("#amount").text('');
        //
        // $('.nav-tabs li:eq(0) a').tab('show');
        //
        // $.each(response.receipt, function(index, data) {
        //
        //   $("#qifu_table tbody tr").each(function() {
        //     var devotee = $(this).find("#devotee").text();
        //
        //     if(devotee == data.devotee_id)
        //     {
        //       $(this).find('.amount').attr('checked', true);
        //     }
        //   });
        //
        //   $("#qifu_table2 tbody tr").each(function() {
        //     var devotee = $(this).find("#devotee").text();
        //
        //     if(devotee == data.devotee_id)
        //     {
        //       $(this).find('.amount').attr('checked', true);
        //     }
        //   });
        // });
        //
        // $(".total").html(total_devotee);
        // $(".total_amount").text(amount);
        // $("#total_amount").val(amount);
        //
        // $("#authorized_password").val('');
        // $("#trans_no").val('');
        //
        // $("#transaction_wrap").show();
        //
        // if(trans_no != "")
        // {
        //   $("#trans_info").html("<span style='font-weight: bold'>" + trans_no + "</span>" + " <span class='text-danger'>is about to Cancel & Replace.</span>");
        // }
        //
        // if(response.error == "not match")
        // {
        //   $('html,body').animate({ scrollTop: 0 }, 'slow');
        //
        //   $(".validation-error").addClass("bg-danger alert alert-error")
        //   $(".validation-error").html("Unauthorised user access! Change will not be saved! Please re-enter authorised user access to save changes.");
        // }
        //
        // else
        // {
        //   $(".validation-error").removeClass("bg-danger alert alert-error")
        //   $(".validation-error").empty();
        // }
      },
      error: function (response) {
        console.log(response);
      }
    });

  });
  // cancel-qifu-replace-btn end

});
