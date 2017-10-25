$(function() {

  $(".ciji-display").attr('disabled', true);

  $('body').on('input', '.ciji-amount-col', function(){
      var sum = 0;

    $(".ciji-amount").each(function(){

      sum += +$(this).val();

      $(".ciji_total").html(sum);
      $("#ciji_total_amount").val(sum);
    });
  });

  $("body").delegate('.ciji-amount', 'focus', function() {

    var minimum_amount = parseInt($("#minimum_amount").val());

    $(this).on("change",function (){

      var amount = parseInt($(this).val());

      if(amount > minimum_amount)
      {
        $(this).closest('tr').find(".ciji-display").removeAttr('disabled');
        $(this).closest('tr').find(".ciji-display").val('Y');
        $(this).closest('tr').find(".ciji-display-hidden").val('Y');
      }
      else if(amount == minimum_amount)
      {
        $(this).closest('tr').find(".ciji-display").removeAttr('disabled');
        $(this).closest('tr').find(".display").val('Y');
        $(this).closest('tr').find(".display-hidden").val('Y');
      }
      else
      {
        $(this).closest('tr').find(".ciji-display").attr("disabled", "disabled");
        $(this).closest('tr').find(".ciji-display").val('N');
        $(this).closest('tr').find(".ciji-display-hidden").val('N');
      }
    });
  });

  $("#confirm_ciji_btn").click(function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var mode_payment = $("input[name=ciji_mode_payment]:checked").val();
    var cheque_no = $("#ciji_cheque_no").val();
    var nets_no = $("#ciji_nets_no").val();
    var manualreceipt = $("#ciji_manualreceipt").val();
    var receipt_at = $("#ciji_receipt_at").val();
    var total_amount = $("#ciji_total_amount").val();

    if($.trim(total_amount).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Amount Field(s) is empty."
    }

    if(mode_payment == "cheque")
    {
      if ($.trim(cheque_no).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Cheque No is empty."
      }
    }

    if(mode_payment == "nets")
    {
      if ($.trim(nets_no).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Nets No is empty."
      }
    }

    if(mode_payment == "receipt")
    {
      if ($.trim(manualreceipt).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Manual Receipt is empty."
      }

      if ($.trim(receipt_at).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Date Of Receipt is empty."
      }
    }

    if (validationFailed)
    {
      var errorMsgs = '';

      for(var i = 0; i < count; i++)
      {
        errorMsgs = errorMsgs + errors[i] + "<br/>";
      }

      $('html,body').animate({ scrollTop: 0 }, 'slow');

      $(".validation-error").addClass("bg-danger alert alert-error");
      $(".validation-error").html(errorMsgs);

      return false;
    }

    else
    {
      if (confirm("Do you want to confirm this form?")){
        $("#ciji-donation-form")[0].submit();
      }

      else{
        return false;
      }

      $(".validation-error").removeClass("bg-danger alert alert-error");
      $(".validation-error").empty();
    }

    setTimeout(function(){ window.location.reload(true); }, 1000);
  });

  $("#ciji_receipt_history_table").on('click', '.receipt-id', function() {

    $(".alert-success").remove();
    var focusdevotee_id = $("#focusdevotee_id").val();

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
          var description = "";

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

});
