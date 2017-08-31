$(function() {

  $("#search_detail_btn").click(function() {

    $("#search_devotee_lists tbody").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var devotee_id = $("#search_devotee_id").val();
    var member_id = $("#search_member_id").val();
    var chinese_name = $("#search_chinese_name").val();

    if(($.trim(devotee_id).length <= 0) && ($.trim(member_id).length <= 0) && ($.trim(chinese_name).length <= 0))
    {
      validationFailed = true;
      errors[count++] = "Fill one field for search devotee."
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

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      devotee_id: devotee_id,
      member_id: member_id,
      chinese_name: chinese_name
    };

    $.ajax({
      type: 'GET',
      url: "/staff/search-devotee",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        if(response.devotee.length != 0)
        {
          $("#search_devotee_lists tbody").empty();

          $.each(response.devotee, function(index, data) {
            $('#search_devotee_lists tbody').append("<tr><td><a class='search-member' id='" + data.devotee_id + "'>" + data.chinese_name + "</a></td></tr>");
          });
        }

        else
        {
          $('#search_devotee_lists tbody').append("<tr><td>No Result Found!</td></tr>")
        }
      },
      error: function (response) {
          console.log(response);
      }
    });

  });

  $("#search_devotee_lists").on("mouseover", ".search-member", function(e) {
    var devotee_id = $(this).attr("id");

    $("#search_devotee_lists").find('a').removeClass('highlight');
    $(this).addClass('highlight');

    $("#searchby_devotee_id").val('');
    $("#searchby_member_id").val('');
    $("#search_title").val('');
    $("#searchby_chinese_name").val('');
    $("#search_english_name").val('');
    $("#search_guiyi_name").val('');
    $("#search_contact").val('');
    $("#search_address_houseno").val('');
    $("#search_address_unit").val('');
    $("#search_address_street").val('');
    $("#search_address_postal").val('');
    $("#search_country").val('');

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      devotee_id: devotee_id,
    };

    $.ajax({
      type: 'GET',
      url: "/staff/search-devotee-id",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        $.each(response.devotee, function(index, data) {

          $("#searchby_devotee_id").val(data.devotee_id);
          $("#searchby_member_id").val(data.member_id);
          $("#search_title").val(data.title);
          $("#searchby_chinese_name").val(data.chinese_name);
          $("#search_english_name").val(data.english_name);
          $("#search_guiyi_name").val(data.guiyi_name);
          $("#search_contact").val(data.contact);
          $("#search_address_houseno").val(data.address_houseno);

          if(data.address_unit1 != null && data.address_unit2 != null)
          {
            $("#search_address_unit").val("#" + data.address_unit1 + "-" + data.address_unit2);
          }
          else
          {
            $("#search_address_unit").val('');
          }

          $("#search_address_street").val(data.address_street);
          $("#search_address_postal").val(data.address_postal);
          $("#search_country").val(data.country_name);
        });
      },
      error: function (response) {
          console.log(response);
      }

    });

  });

  $("#insert_devotee").click(function() {

    $(".alert-success").remove();
    $(".validation-error").empty();

    var devotee_id = $("#search_devotee_lists").find('.highlight').attr('id');

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    $('#different_familycode_table .append-devotee-id').each( function () {

      var id = $(this).val();

      if(devotee_id == id)
      {
        validationFailed = true;
        errors[count++] = "This devotee id is already selected."
      }
    });

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

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      devotee_id: devotee_id,
    };

    $.ajax({
      type: 'GET',
      url: "/staff/insert-devotee",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        var familycode = $("#family_code").text();

        if(familycode == response.devotee[0]['familycode'])
        {
          validationFailed = true;
          errors[count++] = "Same Family Code cannot be selected.";
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

        $.each(response.devotee, function(index, data) {

          if(data.address_unit1 != null && data.address_unit2 != null)
          {
            var full_address = data.address_houseno + ", #" + data.address_unit1 + "-" + data.address_unit2 + ", " + data.address_street + ", " + data.address_postal;
          }
          else
          {
            var full_address = data.address_houseno + ", " + data.address_street + ", " + data.address_postal;
          }

          $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
            "<td class='checkbox-col'><input type='checkbox' name='xiangyou_ciji_id[]' value='" + data.devotee_id + "' class='different xiangyou_ciji_id'>" +
            "<input type='hidden' class='form-control hidden_xiangyou_ciji_id' name='hidden_xiangyou_ciji_id[]'  value=''></td>" +
            "<td class='checkbox-col'><input type='checkbox' name='yuejuan_id[]' value='" + data.devotee_id + "' class='different yuejuan_id'>" +
            "<input type='hidden' class='form-control hidden_yuejuan_id' name='hidden_yuejuan_id[]' value=''></td>" +
            "<td>" + data.chinese_name +"</td>" +
            "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
            "<td>" + $.trim(data.member_id) + "</td>" +
            "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
            "<td>" + $.trim(data.guiyi_name) + "</td>" +
            "<td>" + $.trim(data.contact) + "</td>" +
            "<td>" + $.trim(data.paytill_date) + "</td>" +
            "<td>" + data.mailer + "</td>" +
            "<td>" + data.lasttransaction_at + "</td>" +
            "<td>" + data.familycode + "</td>");
          });
        },
        error: function (response) {
            console.log(response);
        }

      });
  });

  // remove row
  $("#different_familycode_table").on('click', '.removeDevotee', function() {
    if (!confirm("Are you sure you want to delete this devotee from Relative and Friends List? This process is irreversible.")){
      return false;
    }

    else{
      $(this).closest('tr').remove();
    }

  });

  $("#update_differentaddr_btn").click(function() {

    $(".alert-success").remove();
    $(".validation-error").empty();

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var focusdevotee_id = $("#focusdevotee_id").val();

    if ($.trim(focusdevotee_id).length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Please select focus devotee.";
    }

    if($("#different_familycode_table tbody").children().length == 0)
    {
      validationFailed = true;
      errors[count++] = "There is no devotee in the table.";
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

  $("#cancel_differentaddr_btn").click(function() {
    $('input:checkbox').attr('checked',false);
  });

});
