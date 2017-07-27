
$(function() {

    $("#edit-familycode-table tbody").empty();
    $('#edit-familycode-table tbody').append("<tr id='edit_no_familycode'>" +
                        "<td colspan='3'>No Family Code</td></tr>");

	// check family code
    $(".edit_check_family_code").click(function() {

        $("#edit_no_familycode").remove();
        $("#edit-familycode-table tbody").empty();

    	var address_houseno = $("#edit_address_houseno").val();
    	var address_unit1 = $("#edit_address_unit1").val();
    	var address_unit2 = $("#edit_address_unit2").val();
    	var address_street = $("#edit_address_street").val();
    	var address_building = $("#edit_address_building").val();
    	var address_postal = $("#edit_address_postal").val();

        $('#update_btn').removeAttr("disabled");
        $("#familycode-table tbody").empty();

        var formData = {
        	_token: $('meta[name="csrf-token"]').attr('content'),
        	address_houseno: address_houseno,
        	address_unit1: address_unit1,
        	address_unit2: address_unit2,
        	address_street: address_street,
        	address_building: address_building,
        	address_postal: address_postal
        };

        $.ajax({
            type: 'POST',
            url: "/operator/devotee/search-familycode",
            data: formData,
            dataType: 'json',
            success: function(response)
            {
                if(response.familycode.length != 0)
                {
                    $("#edit_no_familycode").remove();

                    var familycode_id = "";

                    $.each(response.familycode, function(index, data) {

                        if(familycode_id != data.familycode_id)
                        {
                          $('#edit-familycode-table tbody').append("<tr id='appendFamilyCode'><td><input type='radio' name='edit_familycode_id' " +
                              "value='" + data.familycode_id + "' /></td>" +
                              "<td>" + data.chinese_name + "</td>" +
                              "<td>" + data.familycode + "</td></tr>");
                        }

                        else {
                          $('#edit-familycode-table tbody').append("<tr><td colspan='3'>" + data.chinese_name + "</td>");
                        }

                        familycode = data.familycode_id;
                    });
                }

                else
                {
                    $('#edit-familycode-table tbody').append("<tr id='edit_no_familycode'>" +
                        "<td colspan='3'>No Family Code</td></tr>");
                }
            },

            error: function (response) {
            	console.log(response);
            }
       	});

    });
});
