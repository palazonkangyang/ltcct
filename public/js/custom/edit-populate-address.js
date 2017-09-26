$(function() {

  // function edit_populate()
  // {
  //   var populate_houseno = $('#edit_populate_houseno').val();
  //   var populate_unit_1 = $('#edit_populate_unit_1').val();
  //   var populate_unit_2 = $('#edit_populate_unit_2').val();
  //   var populate_street = $('#edit_populate_street').val();
  //   var populate_postal = $('#edit_populate_postal').val();
  //
  //   if($.trim(populate_unit_1).length <= 0)
  //   {
  //     var full_populate_address = populate_houseno + ", " + populate_street + ", " + populate_postal;
  //
  //     $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_populate_address);
  //     $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_populate_address);
  //   }
  //   else
  //   {
  //     var full_populate_address = populate_houseno + ", " + populate_unit_1 + "-" + populate_unit_2 + ", " + populate_street + ", " +
  //                         populate_postal;
  //
  //     $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_populate_address);
  //     $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_populate_address);
  //   }
  // }

  $("#edit_populate_unit_1").on('keyup', function() {
    // edit_populate();

    $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-unit1-hidden").val($(this).val());
  });

  $("#edit_populate_unit_2").on('keyup', function() {
    // edit_populate();

    $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-unit2-hidden").val($(this).val());
  });

  $("#edit_populate_oversea_addr_in_china").on('keyup', function() {
    $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-oversea-hidden").val($(this).val());
  });

  $("#edit_opt_address").on('mouseover', '.edit-populate-data', function() {

    $(".edit_inner_opt_addr").find('button').removeClass('hover');
    $(this).addClass('hover');

    var address = $(this).closest(".edit_inner_opt_addr").find("input[name='address_data_hidden[]']").val();

    if(address.length > 0)
    {
      var array = $(this).closest(".edit_inner_opt_addr").find("input[name='address_data_hidden[]']").val().split(",");
      var edit_populate_address_translate = $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val();

      $.each(array,function(i){
        $("#edit_populate_houseno").val(array[0]);

        var strVale = array[1];
        var char = "-";

        if(strVale.indexOf(char) != -1){
          arr = strVale.split('-');

          $("#edit_populate_unit_1").val($.trim(arr[0]));
          $("#edit_populate_unit_2").val(arr[1]);

          $("#edit_populate_street").val(array[2]);
          $("#edit_populate_postal").val(array[3]);
          $("#edit_populate_address_translate").val(edit_populate_address_translate);
          $("#edit_populate_oversea_addr_in_china").val('');
        }

        else
        {
          $("#edit_populate_unit_1").val('');
          $("#edit_populate_unit_2").val('');
          $("#edit_populate_street").val(array[1]);
          $("#edit_populate_postal").val(array[2]);
          $("#edit_populate_address_translate").val(edit_populate_address_translate);
          $("#edit_populate_oversea_addr_in_china").val('');
        }
      });
    }

    else
    {
      var oversea_address = $(this).closest(".edit_inner_opt_addr").find("input[name='address_oversea_hidden[]']").val().split(",");

      $("#edit_populate_unit_1").val('');
      $("#edit_populate_unit_2").val('');
      $("#edit_populate_houseno").val('');
      $("#edit_populate_street").val('');
      $("#edit_populate_postal").val('');
      $("#edit_populate_address_translate").val(edit_populate_address_translate);

      $("#edit_populate_oversea_addr_in_china").val(oversea_address);
    }
  });

  $("#edit_opt_address").on('change', '.edit-address-type', function() {
    var value = $(this).val();

    if(value == "home" || value == "office") {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").attr('placeholder', 'Please fill address on the right');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill address on the right');
    }

    else if (value == "company") {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").attr('placeholder', 'Please fill Company Name here');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill Company Name here');
    }

    else {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").attr('placeholder', 'Please fill Hawker Stall Name here');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill Hawker Stall Name here');
    }

  });

  // $("#edit_append_opt_address").on('change', '.address-type', function() {
  //   var value = $(this).val();
  //
  //   alert(value);
  //
  //   if(value == "company" || value == "stall") {
  //     $(this).closest("div.form-group").find(".populate-data").attr('disabled', true);
  //     $(this).closest("div.form-group").find("input[name='address_data[]']").val('');
  //   }
  //
  //   else {
  //     $(this).closest("div.form-group").find(".populate-data").attr('disabled', false);
  //   }
  //
  // });

  $(".edit_inner_opt_addr").on('focusout', '.edit-address-data', function() {

    var value = $(this).val();

    $(this).attr('placeholder', value);
    $(this).attr('title', value);
  });

});
