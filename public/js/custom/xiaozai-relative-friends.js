$(function() {

  $("#search_devotee_id").focusout(function() {

    if($(this).val() == "" && $("#search_member_id").val() == "" && $("#search_chinese_name").val() == "")
    {
      clearData();
    }
  });

  $("#search_member_id").focusout(function() {

    if($(this).val() == "" && $("#search_devotee_id").val() == "" && $("#search_chinese_name").val() == "")
    {
      clearData();
    }
  });

  $("#search_chinese_name").focusout(function() {

    if($(this).val() == "" && $("#search_devotee_id").val() == "" && $("#search_member_id").val() == "")
    {
      clearData();
    }
  });

  function clearData(){
    $("#search_devotee_lists tbody").empty();
    $('#search_devotee_lists tbody').append("<tr><td>No Result Found!</td></tr>");

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
  }

  $("#xiaozai_differentfamily_form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='xiaozai_id[]']").each( function () {
      var checkbox_this = $(this);
      var hidden_xiaozai_id = checkbox_this.closest('.checkbox-col').find('.hidden_xiaozai_id');

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
          $('#search_devotee_lists tbody').append("<tr><td>No Result Found!</td></tr>");
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

    var notsamevalue = 0;
    var type_value = 0;
    var a = 0;
    var devotee_len = 0;

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    $(".alert-success").remove();
    $(".validation-error").empty();

    var devotee_id = $("#search_devotee_lists").find('.highlight').attr('id');

    $('#different_xiaozai_familycode_table .append-devotee-id').each( function () {
      var type_value = $(this).closest('tr').find('.type').val();

      var id = $(this).val();

      if(devotee_id == id)
      {
        if(type_value == "individual")
        {
          a = 1;
        }
      }
    });

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      devotee_id: devotee_id,
      address: a
    };

    $.ajax({
      type: 'GET',
      url: "/fahui/insert-devotee",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        notsamevalue = 0;

        $.each(response.devotee, function(index, data) {

          notsamevalue = 0;

          $('#different_xiaozai_familycode_table .append-devotee-id').each( function () {

            var id = $(this).val();
            var type = $(this).closest('tr').find('.type').val();

            if(id == data.devotee_id && type == data.type)
            {
              notsamevalue = 1;
              validationFailed = true;
              errors[count++] = "Some Addresses are already exits. Some Addresses are inserted.";
            }
          });

          if(notsamevalue != 1)
          {
            if(data.type == "sameaddress")
            {
              $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
                "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
                "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
                "<td>" + data.chinese_name +"</td>" +
                "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
                "<td></td>" +
                "<td>" + $.trim(data.guiyi_name) + "</td>" +
                "<td></td>" +
                "<td>" + data.ops + "</td>" +
                "<td><select class='form-control type' name='type[]'><option value='sameaddress' selected>合家</option>" +
                "<option value='individual'>个人</option></select></td>" +
                "<td>" + data.item_description + "</td>" +
                "<td>" + $.trim(data.paytill_date) + "</td>" +
                "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
            }

            else
            {
              $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
                "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
                "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
                "<td>" + data.chinese_name +"</td>" +
                "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
                "<td></td>" +
                "<td>" + $.trim(data.guiyi_name) + "</td>" +
                "<td></td>" +
                "<td>" + data.ops + "</td>" +
                "<td><select class='form-control type' name='type[]' style='display: none;'><option value='" + data.type  + "' selected>" + data.type + "</option>" +
                "</select>" + data.chinese_type + "</td>" +
                "<td>" + data.item_description + "</td>" +
                "<td>" + $.trim(data.paytill_date) + "</td>" +
                "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
            }
          }
        });

        if (validationFailed)
        {
          var errorMsgs = '';

          errorMsgs = errorMsgs + errors[0] + "<br/>";

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
      },
      error: function (response) {
        console.log(response);
      }

    });
  });

  $("#update_xiaozai_differentaddr_btn").click(function() {

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

  // remove row
  $("#different_xiaozai_familycode_table").on('click', '.removeDevotee', function() {

    if (!confirm("Are you sure you want to delete this devotee from Relative and Friends List? This process is irreversible.")) {
      return false;
    }

    else {
      $(this).closest('tr').remove();
    }
  });

  $("#add_trick_list").click(function() {

    var array_id = [];
    var array_type = [];
    var notsamevalue = 0;

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    $("input:checkbox:checked", ".xiaozai_history_table").map(function() {
      array_id.push($(this).val());
      array_type.push($(this).closest('tr').find('.type').val());
    });

    if(array_id.length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Please select the devotee in the history table";
    }

    $.each(array_id, function( i, val ) {

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        devotee_id: array_id[i],
        type: array_type[i],
      };

      $.ajax({
        type: 'GET',
        url: "/fahui/insert-devotee-by-type",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          if (validationFailed)
          {
            var errorMsgs = '';

            errorMsgs = errorMsgs + errors[0] + "<br/>";

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

            notsamevalue = 0;

            $('#different_xiaozai_familycode_table .append-devotee-id').each( function () {

              var id = $(this).val();
              var type = $(this).closest('tr').find('.type').val();

              if(id == data.devotee_id && type == data.type)
              {
                notsamevalue = 1;
                validationFailed = true;
                errors[count++] = "Some Addresses are already exits. Some Addresses are inserted.";
              }
            });

            if(notsamevalue != 1)
            {
              $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
                "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
                "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
                "<td>" + data.chinese_name +"</td>" +
                "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
                "<td></td>" +
                "<td>" + $.trim(data.guiyi_name) + "</td>" +
                "<td></td>" +
                "<td>" + data.ops + "</td>" +
                "<td><select class='form-control type' name='type[]' style='display: none;'><option value='" + data.type  + "' selected>" + data.type + "</option>" +
                "</select>" + data.chinese_type + "</td>" +
                "<td>" + data.item_description + "</td>" +
                "<td>" + $.trim(data.paytill_date) + "</td>" +
                "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");

              $('html,body').animate({ scrollTop: 0 }, 'slow');
            }
          });
        },
        error: function (response) {
          console.log(response);
        }

      });
    });

  });

});
