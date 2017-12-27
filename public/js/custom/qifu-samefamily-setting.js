$(function() {
  checkbox_multi_select('checkbox-multi-select-module-qifu-tab-sfc-section-sfc');
  
  $('#update_sameaddr_btn').click(function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var qifu_focusdevotee_id = $("#qifu_focusdevotee_id").val();

    if ($.trim(qifu_focusdevotee_id).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Please select focus devotee.";
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
      $(".validation-error").removeClass("bg-danger alert alert-error")
      $(".validation-error").empty();
    }
  });

  $("#qifu_samefamily_form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='qifu_id[]']").each( function () {
      var checkbox_this = $(this);
      var hidden_qifu_id = checkbox_this.closest('.checkbox-col').find('.hidden_qifu_id');

      if( checkbox_this.is(":checked") == true ) {
        hidden_qifu_id.attr('value','1');
      }

      else {
        hidden_qifu_id.prop('checked', true);
        //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
        hidden_qifu_id.attr('value','0');
      }
    });
  });
});
