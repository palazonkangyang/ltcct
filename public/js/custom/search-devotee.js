$(function() {

    // remove row
    $("#generaldonation_table2").on('click', '.removeDevotee', function() {
        $(this).closest ('tr').remove ();
    });

    // do the validation for the form
    $("#confirm_donation_btn").click(function(e) {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;
        var submit = true;

        var mode_payment = $("input[name=mode_payment]:checked").val();
        var cheque_no = $("#cheque_no").val();
        var nets_no = $("#nets_no").val();
        var manualreceipt = $("#manualreceipt").val();
        var receipt_at = $("#receipt_at").val();
				var total_amount = $("#total_amount").val();

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

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(errorMsgs);

          return false;
      }

      else
      {

        if (confirm("Do you want to confirm this form?")){
          $("#donation-form")[0].submit();
        }

        else{
          return false;
        }

        $(".validation-error").removeClass("bg-danger alert alert-error")
        $(".validation-error").empty();
      }

      setTimeout(function(){ window.location.reload(true); }, 1000);
    });

});
