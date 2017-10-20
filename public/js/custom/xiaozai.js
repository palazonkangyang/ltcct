$(function() {

  $('body').on('change', '.xiaozai-amount-col', function(){

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
  });

  $("#xiaozai-form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='xiaozai_amount[]']").each( function () {
      var checkbox_this = $(this);
      var hidden_xiaozai_amount = checkbox_this.closest('.xiaozai-amount-col').find('.hidden_xiaozai_amount');

      if( checkbox_this.is(":checked") == true ) {
        hidden_xiaozai_amount.attr('value','1');
      }

      else {
        hidden_xiaozai_amount.prop('checked', true);
        //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
        hidden_xiaozai_amount.attr('value','0');
      }
    });
  });

  // do the validation for the form
  $("#confirm_xiaozai_btn").click(function(e) {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var mode_payment = $("input[name=mode_payment]:checked").val();
    var cheque_no = $("#cheque_no").val();
    var nets_no = $("#nets_no").val();
    var manualreceipt = $("#manualreceipt").val();
    var receipt_at = $("#receipt_at").val();
    var total_amount = $("#total_amount").val();

    if(total_amount == 0)
    {
      validationFailed = true;
      errors[count++] = "Total Payable Amount is empty."
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
        $("#xiaozai-form")[0].submit();
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
