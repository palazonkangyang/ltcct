
$(function() {

  var edit_type = $("#edit_type").val();

  if(edit_type == 'cash')
  {
    $("#edit_cash").show();
    $("#edit_cheque").hide();
  }

  else
  {
    $("#edit_cheque").show();
    $("#edit_cash").hide();
  }

  $("#edit_type").on('change', function() {
     var edit_type = $(this).val();

     if(edit_type == 'cash')
     {
       $("#edit_cash").show();
       $("#edit_cheque").hide();
     }

     else
     {
       $("#edit_cheque").show();
       $("#edit_cash").hide();
     }
  });

  $("#paid-table").on('click','.edit-item',function(e) {

    $(".nav-tabs > li:first-child").removeClass("active");
    $("#edit-paid").addClass("active");

    var paid_id = $(this).attr("id");

    var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        paid_id: paid_id
    };

    $.ajax({
        type: 'GET',
        url: "/paid/paid-detail",
        data: formData,
        dataType: 'json',
        success: function(response)
        {

          localStorage.setItem('paid_id', response.paid['paid_id']);
          localStorage.setItem('expenditure_id', response.paid['expenditure_id']);
          localStorage.setItem('job_id', response.paid['job_id']);
          localStorage.setItem('status', response.paid['status']);
          localStorage.setItem('type', response.paid['type']);
          localStorage.setItem('cheque_account', response.paid['cheque_account']);
          localStorage.setItem('description', response.paid['description']);
          localStorage.setItem('remark', response.paid['remark']);

          if(response.paid['type'] == 'cash')
          {
            $("#edit_cash").show();
            $("#edit_cheque").hide();
          }

          else
          {
            $("#edit_cheque").show();
            $("#edit_cash").hide();
          }

          if(localStorage.getItem('paid_id'))
          {
              var paid_id = localStorage.getItem('paid_id');
              var expenditure_id = localStorage.getItem('expenditure_id');
              var job_id = localStorage.getItem('job_id');
              var status = localStorage.getItem('status');
              var type = localStorage.getItem('type');
              var cheque_account = localStorage.getItem('cheque_account');
          }

          $("#edit_paid_id").val(paid_id);
          $("#edit_reference_no").val(response.paid['reference_no']);
          $("#edit_date").val(response.paid['date']);
          $("#edit_expenditure_id").val(expenditure_id);
          $("#edit_supplier").val(response.paid['supplier']);
          $("#edit_description").val(response.paid['description']);
          $("#edit_expenditure_total").val(response.paid['expenditure_total']);
          $("#edit_outstanding_total").val(response.paid['outstanding_total']);
          $("#edit_amount").val(response.paid['amount']);
          $("#edit_status").val(status);
          $("#edit_type").val(type);

          $("#edit_cash_voucher_no").val(response.paid['cash_voucher_no']);
          $("#edit_transaction_date").val(response.paid['transaction_date']);
          $("#edit_cash_account").val(response.paid['cash_account']);
          $("#edit_cash_amount").val(response.paid['cash_amount']);
          $("#edit_cash_payee").val(response.paid['cash_payee']);

          $("#edit_cheque_no").val(response.paid['cheque_no']);
          $("#edit_cheque_account").val(cheque_account);
          $("#edit_cheque_date").val(response.paid['cheque_date']);
          $("#edit_cash_date").val(response.paid['cash_date']);


          $("#edit_job_id").val(job_id);
          $("#edit_gl_description").val(response.paid['gl_description']);
          $("#edit_remark").val(response.paid['remark']);
        },

        error: function (response) {
            console.log(response);
        }
    });

  });

  $('#update_paid_btn').click(function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var edit_reference_no = $("#edit_reference_no").val();
    var edit_date = $("#edit_date").val();
    var edit_supplier = $("#edit_supplier").val();
    var edit_description = $("#edit_description").val();
    var edit_expenditure_total = $("#edit_expenditure_total").val();
    var edit_outstanding_total = $("#edit_outstanding_total").val();
    var edit_amount = $("#edit_amount").val();
    var edit_type = $("#edit_type").val();

    var edit_cheque_no = $("#edit_cheque_no").val();
    var edit_cheque_voucher_no = $("#edit_cheque_voucher_no").val();
    var edit_cheque_payee = $("#edit_cheque_payee").val();
    var edit_cheque_date = $("#edit_cheque_date").val();
    var edit_cash_date = $("#edit_cash_date").val();

    if ($.trim(edit_reference_no).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Reference No field is empty."
    }

    if ($.trim(edit_date).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Date field is empty."
    }

    if ($.trim(edit_supplier).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Supplier field is empty."
    }

    if ($.trim(edit_description).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Description field is empty."
    }

    if ($.trim(edit_expenditure_total).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Expenditure Total field is empty."
    }

    if ($.trim(edit_outstanding_total).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Outstanding Total field is empty."
    }

    if ($.trim(edit_amount).length <= 0)
    {
        validationFailed = true;
        errors[count++] = "Amount field is empty."
    }

    if(edit_type == 'cheque')
    {
      if ($.trim(edit_cheque_no).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Cheque No field is empty."
      }

      if ($.trim(edit_cheque_voucher_no).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Voucher No field is empty."
      }

      if ($.trim(edit_cheque_payee).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Payee field is empty."
      }

      if ($.trim(edit_cheque_date).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Cheque Date field is empty."
      }

      if ($.trim(edit_cash_date).length <= 0)
      {
          validationFailed = true;
          errors[count++] = "Cash Date field is empty."
      }
    }

  });

});
