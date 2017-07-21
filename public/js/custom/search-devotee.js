$(function() {

    // search devotee id for relatives and friends
	$("#search_devotee_btn").click(function() {

		var devotee_id = $("#search_devotee").val();

		var formData = {
           	_token: $('meta[name="csrf-token"]').attr('content'),
            devotee_id: devotee_id
        };

		$.ajax({
            type: 'GET',
            url: "/staff/search-devotee",
            data: formData,
            dataType: 'json',
            success: function(response)
            {
                if(response.devotee == null)
                {
                    alert("Devotee ID is not found. Please search again!");
                }

                else
                {
                    $("#no_data").remove();


                    $('#appendDevoteeLists').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i>" +
                        "<input type='hidden' name='other_devotee_id[]' value='" + response.devotee['devotee_id'] + "'></td>" +
                        "<td>" + response.devotee['chinese_name'] +"</td>" +
                        "<td>" + response.devotee['devotee_id'] + "</td>" +
                        "<td>" + response.devotee['address_building'] + "</td>" +
                        "<td>" + response.devotee['address_street'] + "</td>" +
                        "<td>" + response.devotee['address_unit1'] + "</td>" +
                        "<td>" + response.devotee['guiyi_name'] + "</td>" +
                        "<td width='100px' class='amount-col'><input type='text' class='form-control amount' name='other_amount[]'></td>" +
                        "<td width='120px'><input type='text' class='form-control paid_till' name='other_paid_till[]' data-provide='datepicker' data-date-format='dd/mm/yyyy'></td>" +
                        "<td width='150px'><select class='form-control' name='other_hjgr_arr[]'><option value='hj'>hj</option><option value='gr'>gr</option>" +
                        "</select></td>" +
                        "<td width='80px'><select class='form-control' name='other_display[]'><option value='Y'>Y</option><option value='N'>N</option>" +
                        "</select></td>" +
                        "<td></td>" +
                        "<td></td>");
                }
            },

            error: function (response) {
                console.log(response);
            }
        });
	});


    // remove row
    $("#generaldonation_table2").on('click', '.removeDevotee', function() {

        $(this).closest ('tr').remove ();
    });


    // do the validation for the form
    $("#confirm_donation_btn").click(function() {

        var count = 0;
        var errors = new Array();
        var validationFailed = false;

        var mode_payment = $("input[name=mode_payment]:checked").val();
        var cheque_no = $("#cheque_no").val();
        var manualreceipt = $("#manualreceipt").val();
        var receipt_at = $("#receipt_at").val();

        if(mode_payment == "cheque")
        {
            if ($.trim(cheque_no).length <= 0)
            {
                validationFailed = true;
                errors[count++] = "Cheque No is empty."
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

    });
});
