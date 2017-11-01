$(function() {

  $("#insert_devotee").attr("disabled", "disabled");

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

    $("#insert_devotee").attr("disabled", "disabled");
  }

  if ($('.kongdan_history_table').length > 0) {
    $("#add_trick_list").attr('disabled', false);
  }

  else {
    $("#add_trick_list").attr('disabled', true);
  }

  $("#kongdan_differentfamily_form").submit(function() {

    var this_master = $(this);

    this_master.find("input[name='kongdan_id[]']").each( function () {
      var checkbox_this = $(this);
      var hidden_kongdan_id = checkbox_this.closest('.checkbox-col').find('.hidden_kongdan_id');

      if( checkbox_this.is(":checked") == true ) {
        hidden_kongdan_id.attr('value','1');
      }

      else {
        hidden_kongdan_id.prop('checked', true);
        //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
        hidden_kongdan_id.attr('value','0');
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
      errors[count++] = "Fill one field for search devotee.";
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

    $("#insert_devotee").removeAttr("disabled");

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
    $("#search_oversea_addr_in_chinese").val('');

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
          $("#searchby_member_id").val(data.member);
          $("#search_title").val(data.title);
          $("#searchby_chinese_name").val(data.chinese_name);
          $("#search_english_name").val(data.english_name);
          $("#search_guiyi_name").val(data.guiyi_name);
          $("#search_contact").val(data.contact);
          $("#search_address_houseno").val(data.address_houseno);

          if(data.oversea_addr_in_chinese != null)
          {
            $("#search_oversea_addr_in_chinese").val(data.oversea_addr_in_chinese);
          }
          else if(data.address_unit1 != null && data.address_unit2 != null)
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

    $('#different_kongdan_familycode_table .append-devotee-id').each( function () {

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
          errors[count++] = "Same Family Code cannot be inserted.";
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
            "<td class='checkbox-col'><input type='checkbox' name='kongdan_id[]' value='" + data.devotee_id + "' class='different kongdan_id'>" +
            "<input type='hidden' class='form-control hidden_kongdan_id' name='hidden_kongdan_id[]'  value=''></td>" +
            "<td>" + data.chinese_name +"</td>" +
            "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
            "<td></td>" +
            "<td>" + $.trim(data.guiyi_name) + "</td>" +
            "<td></td>" +
            "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
            "<td>" + $.trim(data.paytill_date) + "</td>" +
            "<td></td>" +
            "<td>" + data.lasttransaction_at + "</td>");
          });
        },
        error: function (response) {
          console.log(response);
        }

      });
  });

  $("#update_kongdan_differentaddr_btn").click(function() {

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
  $("#different_kongdan_familycode_table").on('click', '.removeDevotee', function() {

    if (!confirm("Are you sure you want to delete this devotee from Relative and Friends List? This process is irreversible.")){
      return false;
    }

    else{
      $(this).closest('tr').remove();
    }
  });

  $("#add_trick_list").click(function() {

    var array = [];

    $("input:checkbox:checked", ".kongdan_history_table").map(function() {
        array.push($(this).val());
    });

    array = $.unique(array);

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    if(array.length <= 0)
    {
      validationFailed = true;
      errors[count++] = "Please select the devotee in the history table";
    }

    $('#different_kongdan_familycode_table .append-devotee-id').each( function () {

      var id = $(this).val();

      $.each(array, function( i, val ) {

        if(val == id)
        {
          validationFailed = true;
          errors[count++] = "Selected devotee(s) are already exits.";
        }
      });
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

    $.each(array, function( i, val ) {

      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        devotee_id: val,
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
            errors[count++] = "Same Family Code cannot be inserted.";
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
              "<td class='checkbox-col'><input type='checkbox' name='kongdan_id[]' value='" + data.devotee_id + "' class='different kongdan_id'>" +
              "<input type='hidden' class='form-control hidden_kongdan_id' name='hidden_kongdan_id[]'  value=''></td>" +
              "<td>" + data.chinese_name +"</td>" +
              "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
              "<td></td>" +
              "<td>" + $.trim(data.guiyi_name) + "</td>" +
              "<td></td>" +
              "<td>" + (data.address_houseno !=null ? full_address : data.oversea_addr_in_chinese) + "</td>" +
              "<td>" + $.trim(data.paytill_date) + "</td>" +
              "<td></td>" +
              "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
            });
          },
          error: function (response) {
            console.log(response);
          }

        });
    });

  });
});
