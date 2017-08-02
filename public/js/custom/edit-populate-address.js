$(function() {

  $("#edit_opt_address").on('click', '.edit-populate-data', function() {

    var populate_houseno = $("#edit_populate_houseno").val();
    var populate_unit_1 = $("#edit_populate_unit_1").val();
    var populate_unit_2 = $("#edit_populate_unit_2").val();
    var populate_building = $("#edit_populate_building").val();
    var populate_postal = $("#edit_populate_postal").val();
    var populate_address_translate = $("#edit_populate_address_translate").val();
    var populate_oversea_addr_in_china = $("#edit_populate_oversea_addr_in_china").val();

    if($.trim(populate_unit_1).length <= 0)
    {
      var full_populate_address = "No." + populate_houseno + ", " + populate_building + ", " + populate_postal + ", Singapore";

      $(this).closest("div.form-group").find("input[name='address_data[]']").val(full_populate_address);
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', full_populate_address);

    }
    else
    {
      var full_populate_address = "No." + populate_houseno + ", #" + populate_unit_1 + "-" + populate_unit_2 + ", " + populate_building +  ", " +
                          populate_postal + ", Singapore";

      $(this).closest("div.form-group").find("input[name='address_data[]']").val(full_populate_address);
      $(this).closest("div.form-group").find(".edit-address-data").attr('title', full_populate_address);
    }

  });

  $("#edit_opt_address").on('change', '.address-type', function() {
    var value = $(this).val();

    if(value == "company" || value == "stall") {
      $(this).closest("div.form-group").find(".edit-populate-data").attr('disabled', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('');
    }

    else {
      $(this).closest("div.form-group").find(".edit-populate-data").attr('disabled', false);
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

});
