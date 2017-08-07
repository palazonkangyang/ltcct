$(function() {

  $(".opt_address").on('click', '.populate-data', function() {

    var populate_houseno = $("#populate_houseno").val();
    var populate_unit_1 = $("#populate_unit_1").val();
    var populate_unit_2 = $("#populate_unit_2").val();
    var populate_building = $("#populate_building").val();
    var populate_postal = $("#populate_postal").val();
    var populate_address_translate = $("#populate_address_translate").val();
    var populate_oversea_addr_in_china = $("#populate_oversea_addr_in_china").val();

    if($.trim(populate_unit_1).length <= 0)
    {
      var full_populate_address = "No." + populate_houseno + ", " + populate_building + ", " + populate_postal + ", Singapore";

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);

    }
    else
    {
      var full_populate_address = "No." + populate_houseno + ", #" + populate_unit_1 + "-" + populate_unit_2 + ", " + populate_building +  ", " +
                          populate_postal + ", Singapore";

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);
    }

  });

  $("#append_opt_address").on('click', '.populate-data', function() {

    var populate_houseno = $("#populate_houseno").val();
    var populate_unit_1 = $("#populate_unit_1").val();
    var populate_unit_2 = $("#populate_unit_2").val();
    var populate_building = $("#populate_building").val();
    var populate_postal = $("#populate_postal").val();
    var populate_address_translate = $("#populate_address_translate").val();
    var populate_oversea_addr_in_china = $("#populate_oversea_addr_in_china").val();

    if($.trim(populate_unit_1).length <= 0)
    {
      var full_populate_address = "No." + populate_houseno + ", " + populate_building + ", " + populate_postal + ", Singapore";

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);

    }
    else
    {
      var full_populate_address = "No." + populate_houseno + ", #" + populate_unit_1 + "-" + populate_unit_2 + ", " + populate_building +  ", " +
                          populate_postal + ", Singapore";

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);
    }
  });

  $(".opt_address").on('change', '.address-type', function() {
    var value = $(this).val();

    if(value == "home" || value == "office") {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the address on the right');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the address on the right');
    }

    else if (value == "company") {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Company Name here');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the Company Name here');
    }

    else {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Hawker Stall Name here');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the Hawker Stall Name here');
    }

  });

  $("#append_opt_address").on('change', '.address-type', function() {
    var value = $(this).val();

    if(value == "home" || value == "office") {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', true);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the address on the right');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the address on the right');
    }

    else if (value == "company") {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Company Name here');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the Company Name here');
    }

    else {
      $(this).closest("div.form-group").find(".address-data").attr('readonly', false);
      $(this).closest("div.form-group").find("input[name='address_data[]']").val('Please fill the Hawker Stall Name here');
      $(this).closest("div.form-group").find(".address-data").attr('title', 'Please fill the Hawker Stall Name here');
    }

  });

  $(".opt_address").on('focusout', '.address-data', function() {

    var value = $(this).val();

    $(this).val(value);
    $(this).attr('title', value);
  });

  $("#append_opt_address").on('focusout', '.address-data', function() {

    var value = $(this).val();

    $(this).val(value);
    $(this).attr('title', value);
  });

});
