$(function() {

  $(".edit_inner_opt_addr").on('click', '.edit-populate-data', function() {

    var array = $(this).closest(".edit_inner_opt_addr").find("input[name='address_data_hidden[]']").val().split(",");

    alert(array);

    $.each(array,function(i){
      $("#edit_populate_houseno").val(array[0]);

      var strVale = array[1];
      arr = strVale.split('-');

      for(i=0; i < arr.length; i++)
      {
          console.log(arr[i]);
      }

      $("#edit_populate_unit_1").val(arr[0]);
      $("#edit_populate_unit_2").val(arr[1]);
      
      $("#edit_populate_street").val(array[2]);
      $("#edit_populate_postal").val(array[3]);
    });


  });

  $(".edit_inner_opt_addr").on('change', '.edit-address-type', function() {
    var value = $(this).val();

    if(value == "home" || value == "office") {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the address on the right');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill the address on the right');
    }

    else if (value == "company") {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Company Name here');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill the Company Name here');
    }

    else {
      $(this).closest("div.form-group").find(".edit-address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Hawker Stall Name here');
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', 'Please fill the Hawker Stall Name here');
    }

  });

  $("#edit_append_opt_address").on('change', '.address-type', function() {
    var value = $(this).val();

    alert(value);

    if(value == "company" || value == "stall") {
      $(this).closest("div.form-group").find(".populate-data").attr('disabled', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('');
    }

    else {
      $(this).closest("div.form-group").find(".populate-data").attr('disabled', false);
    }

  });

  $(".edit_inner_opt_addr").on('focusout', '.edit-address-data', function() {

    var value = $(this).val();

    $(this).val(value);
    $(this).attr('title', value);
  });

});
