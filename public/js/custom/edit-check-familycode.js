
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
      var oversea_addr_in_chinese = $('#edit_oversea_addr_in_chinese').val();

        $('#update_btn').removeAttr("disabled");
        $("#familycode-table tbody").empty();

        var formData = {
        	_token: $('meta[name="csrf-token"]').attr('content'),
        	address_houseno: address_houseno,
        	address_unit1: address_unit1,
        	address_unit2: address_unit2,
        	address_street: address_street,
        	address_building: address_building,
        	address_postal: address_postal,
          oversea_addr_in_chinese: oversea_addr_in_chinese
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
                          $('#edit-familycode-table tbody').append("<tr id='appendRelocationFamilyCode'><td><input type='radio' name='edit_familycode_id' " +
                              "value='" + data.familycode_id + "' /></td>" +
                              "<td>" + data.familycode + "</td>" +
                              "<td><a href='#' class='toggler' data-prod-cat='" + data.familycode_id  + "'>+ " + data.chinese_name + "</a></td></tr>");
                        }

                        else {
                          $('#edit-familycode-table tbody').append("<tr class='cat" + data.familycode_id + "' style='display:none'><td></td><td></td>" +
                          "<td>" + data.chinese_name + "</td></tr>");
                        }

                        familycode_id = data.familycode_id;
                    });
                }

                else
                {
                    $('#edit-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
                        "<td colspan='3'>No Family Code</td></tr>");
                }
            },

            error: function (response) {
            	console.log(response);
            }
       	});

    });

    $("#edit-familycode-table").on('click','.toggler',function(e) {
        e.preventDefault();
        $('.cat'+$(this).attr('data-prod-cat')).toggle();
    });

});
