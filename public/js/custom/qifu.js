$(function() {

  checkbox_multi_select('checkbox-multi-select-module-qifu-tab-qifu-section-sfc');
  checkbox_multi_select('checkbox-multi-select-module-qifu-tab-qifu-section-raf');

  $('body').on('change', '.qifu-amount-col', function(){
    // var sum = 0;
    // var len = $("input[name='qifu_amount[]']:checked").length;
    //
    // sum += len * 10;
    //
    // $(".total").html(len);
    // $(".total_amount").html(sum);
    // $("#total_amount").val(sum);
    // $(".total_payable").html(sum);

    var gr_sum = 0;
    var total = 0;

    $("input[name='qifu_amount[]']").each( function () {

      if( $(this).is(":checked") == true ) {
        $(this).closest('.qifu-amount-col').find('.gr').prop('checked',true);
      }

      else {
        $(this).closest('.qifu-amount-col').find('.gr').prop('checked',false);
      }
    });

    var gr_count = $("input[name='gr[]']:checked").length;
    $(".gr_total").html(gr_count);
    var qifu_price_gr= $("#qifu_price_gr").text();
    gr_sum += gr_count * qifu_price_gr;
    $(".gr_total_amount").html(gr_sum);
    total = gr_sum;
    $(".total_payable").html(total);
    $("#total_amount").val(total);
  });

  $("#qifu-form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='qifu_amount[]']").each( function () {
      var checkbox_this = $(this);
      var is_checked_list = checkbox_this.closest('.qifu-amount-col').find('.is_checked_list');

      if( checkbox_this.is(":checked") == true ) {
        is_checked_list.attr('value','1');
      }

      else {
        is_checked_list.prop('checked', true);
        //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
        is_checked_list.attr('value','0');
      }
    });
  });

  // do the validation for the form
  $("#confirm_qifu_btn").click(function(e) {

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

    if(total_amount == 0)
    {
      validationFailed = true;
      errors[count++] = "Total Payable Amount is empty.";
    }

    if(mode_payment == "cheque")
    {
      if ($.trim(cheque_no).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Cheque No is empty.";
      }
    }

    if(mode_payment == "nets")
    {
      if ($.trim(nets_no).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Nets No is empty.";
      }
    }

    if(mode_payment == "receipt")
    {
      if ($.trim(manualreceipt).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Manual Receipt is empty.";
      }

      if ($.trim(receipt_at).length <= 0)
      {
        validationFailed = true;
        errors[count++] = "Date Of Receipt is empty.";
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
        $("#qifu-form")[0].submit();
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
