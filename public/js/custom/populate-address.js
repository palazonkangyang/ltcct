$(function() {

  $("#populate_houseno").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val($(this).val());
  });

  $("#populate_unit_1").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-unit1-hidden").val($(this).val());
  });

  $("#populate_unit_2").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-unit2-hidden").val($(this).val());
  });

  $("#populate_street").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val($(this).val());
  });

  $("#populate_postal").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val($(this).val());
  });

  $("#populate_oversea_addr_in_china").on('keyup', function() {
    $(".hover").closest("div.inner_opt_addr").find(".address-oversea-hidden").val($(this).val());
  });

  $(".opt_address").on('mouseover', '.populate-data', function() {

    $("#append_opt_address").find('button').removeClass('hover');
    $(this).addClass('hover');

    var populate_houseno = $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val();
    var populate_unit_1 = $(".hover").closest("div.inner_opt_addr").find(".address-unit1-hidden").val();
    var populate_unit_2 = $(".hover").closest("div.inner_opt_addr").find(".address-unit2-hidden").val();
    var populate_street = $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val();
    var populate_postal = $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val();
    var populate_oversea_addr_in_china = $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val();

    $("#populate_houseno").val(populate_houseno);
    $("#populate_unit_1").val(populate_unit_1);
    $("#populate_unit_2").val(populate_unit_2);
    $("#populate_street").val(populate_street);
    $("#populate_postal").val(populate_postal);
    $("#populate_unit_2").val(populate_unit_2);

  });

  $(".opt_address").on('mouseover', '.populate-data', function() {

    var address_houseno = $(".address-houseno-hidden").val();
    var address_unit1 = $(".address-unit1-hidden").val();
    var address_unit2 = $(".address-unit2-hidden").val();
    var address_street = $(".address-street-hidden").val();
    var address_postal = $(".address-postal-hidden").val();

    if($.trim(address_unit1).length <= 0)
    {
      var full_populate_address = address_houseno + ", " + address_street + ", " + address_postal;

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);

      $(".populate-data").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_populate_address);

    }
    else
    {
      var full_populate_address = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street + ", " +
                          address_postal;

      $(".populate-data").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_populate_address);

      // $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      // $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);
    }
  });

  $("#append_opt_address").on('mouseover', '.populate-data', function() {

    $("#append_opt_address").find('button').removeClass('hover');
    $(".opt_address").find('button').removeClass('hover');
    $(this).addClass('hover');

    var populate_houseno = $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val();
    var populate_unit_1 = $(".hover").closest("div.inner_opt_addr").find(".address-unit1-hidden").val();
    var populate_unit_2 = $(".hover").closest("div.inner_opt_addr").find(".address-unit2-hidden").val();
    var populate_street = $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val();
    var populate_postal = $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val();
    var populate_oversea_addr_in_china = $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val();

    $("#populate_houseno").val(populate_houseno);
    $("#populate_unit_1").val(populate_unit_1);
    $("#populate_unit_2").val(populate_unit_2);
    $("#populate_street").val(populate_street);
    $("#populate_postal").val(populate_postal);
    $("#populate_unit_2").val(populate_unit_2);

    if($.trim(populate_unit_1).length <= 0)
    {
      var full_populate_address = populate_houseno + ", " + populate_street + ", " + populate_postal;

      $(this).closest("div.inner_opt_addr").find("input[name='address_data_hidden[]']").val(full_populate_address);
      $(this).closest("div.inner_opt_addr").find(".address-data-hidden").attr('title', full_populate_address);
    }
    else
    {
      var full_populate_address = populate_houseno + ", " + populate_unit_1 + "-" + populate_unit_2 + ", " + populate_street + ", " +
                          populate_postal;

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
