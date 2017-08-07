
$(function() {

    $("#relocation-familycode-table tbody").empty();
    $('#relocation-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
                        "<td colspan='3'>No Family Code</td></tr>");

	// check family code
    $(".relocation_check_family_code").click(function() {

      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      $("#relocation_no_familycode").remove();
      $("#relocation-familycode-table tbody").empty();

    	var address_houseno = $("#new_address_houseno").val();
    	var address_unit1 = $("#new_address_unit1").val();
    	var address_unit2 = $("#new_address_unit2").val();
    	var address_street = $("#new_address_street").val();
    	var address_building = $("#new_address_building").val();
    	var address_postal = $("#new_address_postal").val();
      var oversea_addr_in_chinese = $("#new_oversea_addr_in_chinese").val();

      if($.trim(oversea_addr_in_chinese).length <= 0)
      {
        if ($.trim(address_houseno).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Address Houseno is empty."
        }

        if ($.trim(address_street).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Address Street is empty."
        }

        if ($.trim(address_building).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Address Building is empty."
        }

        if ($.trim(address_postal).length <= 0)
        {
            validationFailed = true;
            errors[count++] = "Address Postal is empty."
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

      else {
          $(".validation-error").removeClass("bg-danger alert alert-error")
          $(".validation-error").empty();
      }

      $("#familycode-table tbody").empty();
      $("#edit-familycode-table tbody").empty();

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
            $("#relocation_no_familycode").remove();

            var familycode_id = "";

            $.each(response.familycode, function(index, data) {

              if(familycode_id != data.familycode_id)
              {
                $('#relocation-familycode-table tbody').append("<tr id='appendFamilyCode'><td><input type='radio' name='relocation_familycode_id' " +
                  "value='" + data.familycode_id + "' /></td>" +
                  "<td>" + data.familycode + "</td>" +
                  "<td><a href='#' class='toggler' data-prod-cat='" + data.familycode_id  + "'>+ " + data.chinese_name + "</a></td></tr>");
              }

              else {
                $('#relocation-familycode-table tbody').append("<tr class='cat" + data.familycode_id + "' style='display:none'><td></td><td></td>" +
                  "<td>" + data.chinese_name + "</td></tr>");
              }

                familycode_id = data.familycode_id;
            });
          }

          else
          {
            $('#relocation-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
              "<td colspan='3'>No Family Code</td></tr>");
          }
        },

        error: function (response) {
          console.log(response);
        }
      });

    });

    $("#relocation-familycode-table").on('click','.toggler',function(e) {
        e.preventDefault();
        $('.cat'+$(this).attr('data-prod-cat')).toggle();
    });

});
