$(function() {

  $('body').on('change', '.xiaozai-amount-col', function(){

    var hj_sum = 0;
    var gr_sum = 0;
    var company_sum = 0;
    var stall_sum = 0;
    var car_sum = 0;
    var ship_sum = 0;
    var total = 0;

    $("input[name='xiaozai_amount[]']").each( function () {

      if( $(this).is(":checked") == true ) {
        $(this).closest('.xiaozai-amount-col').find('.hj').prop('checked',true);
        $(this).closest('.xiaozai-amount-col').find('.gr').prop('checked',true);
        $(this).closest('.xiaozai-amount-col').find('.company').prop('checked',true);
        $(this).closest('.xiaozai-amount-col').find('.stall').prop('checked',true);
        $(this).closest('.xiaozai-amount-col').find('.car').prop('checked',true);
        $(this).closest('.xiaozai-amount-col').find('.ship').prop('checked',true);
      }

      else {
        $(this).closest('.xiaozai-amount-col').find('.hj').prop('checked',false);
        $(this).closest('.xiaozai-amount-col').find('.gr').prop('checked',false);
        $(this).closest('.xiaozai-amount-col').find('.company').prop('checked',false);
        $(this).closest('.xiaozai-amount-col').find('.stall').prop('checked',false);
        $(this).closest('.xiaozai-amount-col').find('.car').prop('checked',false);
        $(this).closest('.xiaozai-amount-col').find('.ship').prop('checked',false);
      }
    });

    var hj_count = $("input[name='hj[]']:checked").length;
    var gr_count = $("input[name='gr[]']:checked").length;
    var company_count = $("input[name='company[]']:checked").length;
    var stall_count = $("input[name='stall[]']:checked").length;
    var car_count = $("input[name='car[]']:checked").length;
    var ship_count = $("input[name='ship[]']:checked").length;

    $(".hj_total").html(hj_count);
    $(".gr_total").html(gr_count);
    $(".company_total").html(company_count+stall_count);
    $(".vehicle_total").html(car_count+ship_count);

    hj_sum += hj_count * 30;
    gr_sum += gr_count * 20;
    company_sum += company_count * 100;
    stall_sum += stall_count * 100;
    car_sum += car_count * 30;
    ship_sum += ship_count * 30;

    $(".hj_total_amount").html(hj_sum);
    $(".gr_total_amount").html(gr_sum);
    $(".company_total_amount").html(company_sum + stall_sum);
    $(".vehicle_total_amount").html(car_sum + ship_sum);

    total = hj_sum + gr_sum + company_sum + stall_sum + car_sum + ship_sum;

    $(".total_payable").html(total);
    $("#total_amount").val(total);
  });

  $("#xiaozai-form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='xiaozai_amount[]']").each( function () {
      var checkbox_this = $(this);
      var is_checked_list = checkbox_this.closest('.xiaozai-amount-col').find('.is_checked_list');

      if( checkbox_this.is(":checked") == true ) {
        hidden_xiaozai_id.attr('value','1');
      }

      else {
        hidden_xiaozai_id.prop('checked', true);
        //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
        hidden_xiaozai_id.attr('value','0');
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
