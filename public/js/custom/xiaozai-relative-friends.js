$(function() {
  checkbox_multi_select('checkbox-multi-select-module-xiaozai-tab-raf-section-raf');
  checkbox_multi_select('checkbox-multi-select-module-xiaozai-tab-raf-section-raf-history');

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
      // var type_value = $(this).closest('tr').find('.type').val();
      //
      // var id = $(this).val();
      //
      // if(devotee_id == id)
      // {
      //   if(type_value == "individual")
      //   {
      //     a = 1;
      //   }
      // }
    });

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      devotee_id: devotee_id,
      mod_id: 5
      // address: a
    };

    $.ajax({
      type: 'POST',
      url: "/fahui/insert-relative-and-friends",
      data: formData,
      dataType: 'json',
      success: function(response)
      {
        // remove records with same devotee_id
        // $.each(response.devotee_id_list, function(index, data) {
        //   $('#different_xiaozai_familycode_table .append-devotee-id').each( function () {
        //     var current_devotee_id = $(this).val();
        //     var new_devotee_id = response.devotee_id_list[index];
        //     if(current_devotee_id == new_devotee_id)
        //     {
        //       $(this).closest('tr').remove();
        //     }
        //   });
        // });

        // $.each(response.devotee_id_list, function(index, data) {
            // $td_delete =            "<td>" +
            //                           "<i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i>" +
            //                         "</td>";
            // $td_is_checked =        "<td class='checkbox-col'>" +
            //                           "<input type='checkbox' class='xiaozai_id checkbox-multi-select-module-xiaozai-tab-raf-section-raf' name='xiaozai_id[]' value='" + response.devotee_id_list[index] + "'>" +
            //                           "<input type='hidden' class='hidden_xiaozai_id' name='is_checked[]' value=''>" +
            //                         "</td>";
            // $td_chinese_name =      "<td>" + response.chinese_name_list[index] +"</td>";
            // $td_devotee_id =        "<td>" +
            //                           "<input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + response.devotee_id_list[index] + "'>" + response.devotee_id_list[index] +
            //                         "</td>";
            // $td_register_by =       "<td>" + response.register_by_list[index] + "</td>";
            // $td_guiyi_id =          "<td>" + response.guiyi_id_list[index] + "</td>";
            // $td_gy =                "<td>" + response.gy_list[index] + "</td>";
            // $td_ops =               "<td>" + response.ops_list[index] + "</td>";
            // $td_item_description =  "<td>" + response.item_description_list[index] + "</td>";
            // $td_paid_by =           "<td>" + response.paid_by_list[index] + "</td>";
            // $td_trans_date =        "<td>" + response.trans_date_list[index] + "</td>";
            //
            // if(response.type_list[index] == "base_home"){
            //   $td_type = "<td><select class='type' name='hjgr[]'><option value='hj' selected>合家</option><option value='gr'>个人</option></select></td>";
            // }
            // else if(response.type_list[index] == "home"){
            //   $td_type = "<td><select class='type' name='hjgr[]'><option value='hj' selected>合家</option><option value='gr'>个人</option></select></td>";
            // }
            // else if(response.type_list[index] == "company"){
            //   $td_type = "<td>公司<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(response.type_list[index] == "stall"){
            //   $td_type = "<td>小贩<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(response.type_list[index] == "office"){
            //   $td_type = "<td>个人<input type='hidden' name='hjgr[]'  value='gr' /></td>";
            // }
            // else if(response.type_list[index] == "car"){
            //   $td_type = "<td>车辆<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else if(response.type_list[index] == "ship"){
            //   $td_type = "<td>船只<input type='hidden' name='hjgr[]'  value='' /></td>";
            // }
            // else{
            //   $td_type = "<td><input type='hidden' name='hjgr[]'  value='' /></td>";
            // }

            // $('#appendDifferentFamilyCodeTable').append("<tr>" +
            // $td_delete +
            // $td_is_checked +
            // $td_chinese_name +
            // $td_devotee_id +
            // $td_register_by +
            // $td_guiyi_id +
            // $td_gy +
            // $td_ops +
            // $td_type +
            // $td_item_description +
            // $td_paid_by +
            // $td_trans_date +
            // "</tr>"
            // );

            // "<td>"+
            //   "@if($devotee->type == 'base_home')"+
            //   "{{ Form::select('hjgr[]', ['hj' => '合家','gr' => '个人'],$devotee->hjgr) }}"+
            //   "@elseif($devotee->type == 'home')"+
            //   "{{ Form::select('hjgr[]', ['hj' => '合家','gr' => '个人'],$devotee->hjgr) }}"+
            //   "@elseif($devotee->type == 'company')"+
            //   "公司"+
            //   "{{ Form::hidden('hjgr[]',null)}}"+
            //   "@elseif($devotee->type == 'stall')"+
            //   "小贩"+
            //   "{{ Form::hidden('hjgr[]',null)}}"+
            //   "@elseif($devotee->type == 'office')"+
            //   "个人"+
            //   "{{ Form::hidden('hjgr[]','gr')}}"+
            //   "@elseif($devotee->type == 'car')"+
            //   "车辆"+
            //   "{{ Form::hidden('hjgr[]',null)}}"+
            //   "@elseif($devotee->type == 'ship')"+
            //   "船只"+
            //   "{{ Form::hidden('hjgr[]',null)}}"+
            //   "@else"+
            //   "{{ Form::hidden('hjgr[]',null)}}"+
            //   "@endif"+
            // "</td>"+

            // if(data.type == "sameaddress")
            // {
              // $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
              //   "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
              //   "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
              //   "<td>" + data.chinese_name +"</td>" +
              //   "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
              //   "<td></td>" +
              //   "<td>" + $.trim(data.guiyi_name) + "</td>" +
              //   "<td></td>" +
              //   "<td></td>" +
              //   "<td><select class='form-control type' name='type[]'><option value='sameaddress' selected>合家</option>" +
              //   "<option value='individual'>个人</option></select></td>" +
              //   "<td>" + (data.item_description !=null ? data.item_description : '') + "</td>" +
              //   "<td>" + $.trim(data.paytill_date) + "</td>" +
              //   "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
            // }
            //
            // else
            // {
              // $('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
              //   "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
              //   "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
              //   "<td>" + data.chinese_name +"</td>" +
              //   "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
              //   "<td></td>" +
              //   "<td>" + $.trim(data.guiyi_name) + "</td>" +
              //   "<td></td>" +
              //   "<td>" + data.ops + "</td>" +
              //   "<td><select class='form-control type' name='type[]' style='display: none;'><option value='" + data.type  + "' selected>" + data.type + "</option>" +
              //   "</select>" + data.chinese_type + "</td>" +
              //   "<td>" + (data.item_description !=null ? data.item_description : '') + "</td>" +
              //   "<td>" + $.trim(data.paytill_date) + "</td>" +
              //   "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
            // }
        // });

        if (response.error != '')
        {
          var errorMsgs = '';

          errorMsgs = response.error;

          $('html,body').animate({ scrollTop: 0 }, 'slow');

          $(".validation-error").addClass("bg-danger alert alert-error")
          $(".validation-error").html(errorMsgs);

          return false;
        }

        else
        {
          $(".validation-error").removeClass("bg-danger alert alert-error")
          $(".validation-error").empty();

          $('html,body').animate({ scrollTop: 0 }, 'slow');
          $(".validation-error").addClass("bg-success alert alert-error")
          $(".validation-error").html("New relative and friend has been inserted");

          setTimeout(function(){ window.location.reload(true); }, 0);

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
    var devotee_id = $(this).attr('data-devotee_id');

    if (!confirm("Are you sure you want to delete this devotee from Relative and Friends List? This process is irreversible.")) {
      return false;
    }

    else {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        devotee_id: devotee_id
      };

      $.ajax({
        type: 'POST',
        url: "/fahui/delete-relative-and-friends",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          $(".validation-error").removeClass("bg-danger alert alert-error")
          $(".validation-error").empty();
          $('html,body').animate({ scrollTop: 0 }, 'slow');
          $(".validation-error").addClass("bg-success alert alert-error")
          $(".validation-error").html("The devotee has been removed from Relative and Friends List");
          setTimeout(function(){ window.location.reload(true); }, 0);
          // alert(response);
          // if(response.devotee.length != 0)
          // {
          //  $(this).closest('tr').remove();
          // }

          // else
          // {
            // alert(response);
            // $('#search_devotee_lists tbody').append("<tr><td>Failed to delete record</td></tr>");
          // }
        },
        error: function (response) {
          // alert(response);
          //setTimeout(function(){ window.location.reload(true); }, 0);
            console.log(response);
        }
      });
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


    $("#add_from_tick_list").click(function() {

      var devotee_id_list = (function() {
        var devotee_id_list_array = [];
        $(".devotee_id_list:checkbox:checked").each(function() {
          devotee_id_list_array.push(this.value);
        });
        return devotee_id_list_array;
      })();

      if(devotee_id_list == ""){
        var errorMsgs = "Please select the devotee in the history table";

        $('html,body').animate({ scrollTop: 0 }, 'slow');

        $(".validation-error").addClass("bg-danger alert alert-error");
        $(".validation-error").html(errorMsgs);

      }

      else{

        var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          devotee_id_list: devotee_id_list
        };

        $.ajax({
          type: 'POST',
          url: "/fahui/insert-relative-and-friends-from-history",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            if (response.error != '')
            {
              var errorMsgs = '';

              errorMsgs = response.error + "<br/>";

              $('html,body').animate({ scrollTop: 0 }, 'slow');

              $(".validation-error").addClass("bg-danger alert alert-error")
              $(".validation-error").html(errorMsgs);

              return false;
            }

            else
            {
              $(".validation-error").removeClass("bg-danger alert alert-error")
              $(".validation-error").empty();

              $('html,body').animate({ scrollTop: 0 }, 'slow');
              $(".validation-error").addClass("bg-success alert alert-error")
              $(".validation-error").html("New relative and friend has been inserted");

              setTimeout(function(){ window.location.reload(true); }, 0);
            }

          },
          error: function (response) {
            console.log(response);
          }

        });

      }


    });


});
