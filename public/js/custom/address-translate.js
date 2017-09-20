$(function() {

  var translate_street = "";
  var populate_translate_street = "";
  var edit_populate_translate_street = "";
  var edit_translate_street = "";

  var edit_address_postal = $("#edit_address_postal").val();

  if(edit_address_postal)
  {
    var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_postal: edit_address_postal
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search/address_translate",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          edit_translate_street = response.translate_street[0]['chinese'];
          console.log(edit_translate_street);
        },

        error: function (response) {
            console.log(response);
        }
    });
  }

  $("#content_address_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#content_address_postal').val(ui.item.value);
    }
  });

  $("#content_oversea_addr_in_chinese").on('focusout', function() {
    var oversea_addr_in_chinese = $(this).val();

    if($.trim(oversea_addr_in_chinese).length <= 0)
    {
      $("#confirm_btn").attr('disabled', true);

      $("#familycode-table tbody").empty();
	    $('#familycode-table tbody').append("<tr id='no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#content_address_houseno").on('focusout', function() {
    var address_houseno = $(this).val();
    var address_street = $("#content_address_street").val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#content_address_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#content_address_unit1").val();
            var address_unit2 = $("#content_address_unit2").val();
            var address_postal = $("#content_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;

              translate_street = response.address[0]['chinese'];
              $("#address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.address[0]['chinese'] +  ", " +
                                  address_postal;

              translate_street = response.address[0]['chinese'];
              $("#address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#content_address_postal").val('');
      $("#address_translated").val('');

      $("#confirm_btn").attr('disabled', true);

      $("#familycode-table tbody").empty();
	    $('#familycode-table tbody').append("<tr id='no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#content_address_street").on('focusout', function() {
    var address_houseno = $("#content_address_houseno").val();
    var address_street = $(this).val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#content_address_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#content_address_unit1").val();
            var address_unit2 = $("#content_address_unit2").val();
            var address_postal = $("#content_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;

              translate_street = response.address[0]['chinese'];
              $("#address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.address[0]['chinese'] +  ", " +
                                  address_postal;

              translate_street = response.address[0]['chinese'];
              $("#address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#content_address_postal").val('');
      $("#address_translated").val('');

      $("#confirm_btn").attr('disabled', true);

      $("#familycode-table tbody").empty();
	    $('#familycode-table tbody').append("<tr id='no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#content_address_postal").on('focusout', function() {
    var address_postal = $(this).val();

    if(address_postal)
    {
      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          address_postal: address_postal
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/address_translate",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#content_address_houseno").val(response.translate_street[0].address_houseno);
            $("#content_address_street").val(response.translate_street[0].english);

            var address_houseno = $("#content_address_houseno").val();
            var address_unit1 = $("#content_address_unit1").val();
            var address_unit2 = $("#content_address_unit2").val();
            var address_postal = $("#content_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;

              translate_street = response.translate_street[0]['chinese'];
              $("#address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              translate_street = response.translate_street[0]['chinese'];
              $("#address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#content_address_houseno").val('');
      $("#content_address_unit1").val('');
      $("#content_address_unit2").val('');
      $("#content_address_street").val('');
      $("#content_address_postal").val('');
      $("#address_translated").val('');

      $("#confirm_btn").attr('disabled', true);

      $("#familycode-table tbody").empty();
	    $('#familycode-table tbody').append("<tr id='no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#content_address_unit1").on('keyup', function() {

    var address_houseno = $("#content_address_houseno").val();
    var address_unit1 = $("#content_address_unit1").val();
    var address_unit2 = $("#content_address_unit2").val();
    var address_street = $("#content_address_street").val();
    var address_postal = $("#content_address_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + translate_street + ", " + address_postal;

      $("#address_translated").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + translate_street +  ", " +
                       address_postal;

      $("#address_translated").val(full_address);
    }
  });

  $("#content_address_unit2").on('keyup', function() {

    var address_houseno = $("#content_address_houseno").val();
    var address_unit1 = $("#content_address_unit1").val();
    var address_unit2 = $("#content_address_unit2").val();
    var address_street = $("#content_address_street").val();
    var address_postal = $("#content_address_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + translate_street + ", " + address_postal;

      $("#address_translated").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + translate_street +  ", " +
                       address_postal;

      $("#address_translated").val(full_address);
    }
  });

  $("#populate_houseno").on('focusout', function() {
    var address_houseno = $(this).val();
    var address_street = $("#populate_street").val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#populate_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#populate_unit_1").val();
            var address_unit2 = $("#populate_unit_2").val();
            var address_postal = $("#populate_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;
              var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

              populate_translate_street = response.address[0]['chinese'];

              $("#populate_address_translate").val(full_address);
              $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                      address_postal;

              populate_translate_street = response.address[0]['chinese'];

              $("#populate_address_translate").val(full_address);
              $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
            }

            $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val($('#populate_postal').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val($('#populate_street').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val($('#populate_houseno').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val($('#populate_address_translate').val());
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#populate_postal").val('');
      $("#populate_address_translate").val('');

      $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val('');
    }
  });

  $("#populate_street").on('focusout', function() {
    var address_houseno = $("#populate_houseno").val();
    var address_street = $(this).val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#populate_postal").val(response.address[0].address_postal);

            var address_houseno = $("#populate_houseno").val();
            var address_unit1 = $("#populate_unit_1").val();
            var address_unit2 = $("#populate_unit_2").val();
            var address_street = $("#populate_street").val();
            var address_postal = $("#populate_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;
              var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

              populate_translate_street = response.address[0]['chinese'];

              $("#populate_address_translate").val(full_address);
              $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                      address_postal;

              populate_translate_street = response.address[0]['chinese'];

              $("#populate_address_translate").val(full_address);
              $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
            }

            $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val($('#populate_postal').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val($('#populate_street').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val($('#populate_houseno').val());
            $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val($('#populate_address_translate').val());
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#populate_postal").val('');
      $("#populate_address_translate").val('');

      $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val('');
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val('');
    }
  });

  $("#populate_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#populate_postal').val(ui.item.value);
    }
  });

  $("#populate_postal").on('focusout', function() {
    var address_postal = $(this).val();

    var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_postal: address_postal
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search/address_translate",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          $("#populate_houseno").val(response.translate_street[0].address_houseno);
          $("#populate_street").val(response.translate_street[0].english);

          var address_houseno = $("#populate_houseno").val();
          var address_unit1 = $("#populate_unit_1").val();
          var address_unit2 = $("#populate_unit_2").val();
          var address_street = $("#populate_street").val();
          var address_postal = $("#populate_postal").val();

          if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
          {
            var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;
            var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

            populate_translate_street = response.translate_street[0]['chinese'];

            $("#populate_address_translate").val(full_address);
            $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
            $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
          }
          else
          {
            var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                address_postal;

            var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                    address_postal;

            populate_translate_street = response.translate_street[0]['chinese'];

            $("#populate_address_translate").val(full_address);
            $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
            $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
          }

          $(".hover").closest("div.inner_opt_addr").find(".address-postal-hidden").val($('#populate_postal').val());
          $(".hover").closest("div.inner_opt_addr").find(".address-street-hidden").val($('#populate_street').val());
          $(".hover").closest("div.inner_opt_addr").find(".address-houseno-hidden").val($('#populate_houseno').val());
          $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val($('#populate_address_translate').val());
        },

        error: function (response) {
            console.log(response);
        }
    });
  });

  $("#populate_unit_1").on('keyup', function() {

    var address_houseno = $("#populate_houseno").val();
    var address_unit1 = $("#populate_unit_1").val();
    var address_unit2 = $("#populate_unit_2").val();
    var address_street = $("#populate_street").val();
    var address_postal = $("#populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + populate_translate_street + ", " + address_postal;
      var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

      $("#populate_address_translate").val(full_address);
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + populate_translate_street +  ", " +
                       address_postal;
      var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street + ", " + address_postal;

      $("#populate_address_translate").val(full_address);
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val($("#populate_address_translate").val());
    }
  });

  $("#populate_unit_2").on('keyup', function() {

    var address_houseno = $("#populate_houseno").val();
    var address_unit1 = $("#populate_unit_1").val();
    var address_unit2 = $("#populate_unit_2").val();
    var address_street = $("#populate_street").val();
    var address_postal = $("#populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + populate_translate_street + ", " + address_postal;
      var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

      $("#populate_address_translate").val(full_address);
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val($("#populate_address_translate").val());
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + populate_translate_street +  ", " +
                       address_postal;

      var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street + ", " + address_postal;

      $("#populate_address_translate").val(full_address);
      $(".hover").closest("div.inner_opt_addr").find(".address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.inner_opt_addr").find(".address-translate-hidden").val(full_address);
    }
  });

  $("#new_address_houseno").on('focusout', function() {
    var address_houseno = $(this).val();
    var address_street = $("#new_address_street").val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#new_address_postal").val(response.address[0].address_postal);

            // var address_unit1 = $("#content_address_unit1").val();
            // var address_unit2 = $("#content_address_unit2").val();
            // var address_postal = $("#content_address_postal").val();
            //
            // if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            // {
            //   var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;
            //
            //   translate_street = response.address[0]['chinese'];
            //   $("#address_translated").val(full_address);
            // }
            // else
            // {
            //   var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.address[0]['chinese'] +  ", " +
            //                       address_postal;
            //
            //   translate_street = response.address[0]['chinese'];
            //   $("#address_translated").val(full_address);
            // }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#new_address_postal").val('');

      $("#relocation-table tbody").empty();
	    $('#relocation-table tbody').append("<tr id='relocation_no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#new_address_street").on('focusout', function() {
    var address_houseno = $("#new_address_houseno").val();
    var address_street = $(this).val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#new_address_postal").val(response.address[0].address_postal);
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#new_address_postal").val('');

      $("#relocation-table tbody").empty();
	    $('#relocation-table tbody').append("<tr id='relocation_no_familycode'>" +
	                        "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#new_address_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#new_address_postal').val(ui.item.value);
    }
  });

  $("#new_address_postal").on('focusout', function() {
    var address_postal = $(this).val();

    var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_postal: address_postal
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search/address_translate",
        data: formData,
        dataType: 'json',
        success: function(response)
        {
          $("#new_address_houseno").val(response.translate_street[0].address_houseno);
          $("#new_address_street").val(response.translate_street[0].english);
        },

        error: function (response) {
            console.log(response);
        }
    });
  });

  $("#edit_populate_houseno").on('focusout', function() {
    var address_houseno = $(this).val();
    var address_street = $("#edit_populate_street").val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_populate_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#edit_populate_unit_1").val();
            var address_unit2 = $("#edit_populate_unit_2").val();
            var address_postal = $("#edit_populate_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;
              var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

              edit_populate_translate_street = response.address[0]['chinese'];

              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                      address_postal;

              edit_populate_translate_street = response.address[0]['chinese'];

              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
            }

            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-postal-hidden").val($('#edit_populate_postal').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-street-hidden").val($('#edit_populate_street').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-houseno-hidden").val($('#edit_populate_houseno').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val($('#edit_populate_address_translate').val());
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_populate_postal").val('');
      $("#edit_populate_address_translate").val('');

      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-postal-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-street-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val('');
    }
  });

  $("#edit_populate_street").on('focusout', function() {
    var address_houseno = $("#edit_populate_houseno").val();
    var address_street = $(this).val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_populate_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#edit_populate_unit_1").val();
            var address_unit2 = $("#edit_populate_unit_2").val();
            var address_postal = $("#edit_populate_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;
              var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

              edit_populate_translate_street = response.address[0]['chinese'];

              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                      address_postal;

              edit_populate_translate_street = response.address[0]['chinese'];

              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
            }

            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-postal-hidden").val($('#edit_populate_postal').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-street-hidden").val($('#edit_populate_street').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-houseno-hidden").val($('#edit_populate_houseno').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val($('#edit_populate_address_translate').val());
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_populate_postal").val('');
      $("#edit_populate_address_translate").val('');

      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-postal-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-street-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val('');
    }
  });

  $("#edit_populate_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#edit_populate_postal').val(ui.item.value);
    }
  });

  $("#edit_populate_postal").on('focusout', function() {
    var address_postal = $(this).val();

    if(address_postal)
    {
      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          address_postal: address_postal
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/address_translate",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_populate_houseno").val(response.translate_street[0].address_houseno);
            $("#edit_populate_street").val(response.translate_street[0].english);

            var address_houseno = $("#edit_populate_houseno").val();
            var address_unit1 = $("#edit_populate_unit_1").val();
            var address_unit2 = $("#edit_populate_unit_2").val();
            var address_street = $("#edit_populate_street").val();
            var address_postal = $("#edit_populate_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;
              var full_address_eng = address_houseno + ", " + address_street + ", " + address_postal;

              edit_populate_translate_street = response.translate_street[0]['chinese'];
              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eng);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              var full_address_eng = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street + ", " + address_postal;

              edit_populate_translate_street = response.translate_street[0]['chinese'];
              $("#edit_populate_address_translate").val(full_address);
              $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eng);
            }

            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-postal-hidden").val($('#edit_populate_postal').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-street-hidden").val($('#edit_populate_street').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-houseno-hidden").val($('#edit_populate_houseno').val());
            $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val($('#edit_populate_address_translate').val());
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_populate_houseno").val('');
      $("#edit_populate_unit_1").val('');
      $("#edit_populate_unit_2").val('');
      $("#edit_populate_street").val('');
      $("#edit_populate_address_translate").val('');

      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val('');
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val('');
    }
  });

  $("#edit_populate_unit_1").on('keyup', function() {

    // var address_houseno = $("#populate_houseno").val();
    // var address_unit1 = $("#populate_unit_1").val();
    // var address_unit2 = $("#populate_unit_2").val();
    // var address_street = $("#edit_populate_street").val();
    // var address_postal = $("#populate_postal").val();

    var address_houseno = $("#edit_populate_houseno").val();
    var address_unit1 = $("#edit_populate_unit_1").val();
    var address_unit2 = $("#edit_populate_unit_2").val();
    var address_street = $("#edit_populate_street").val();
    var address_postal = $("#edit_populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_populate_translate_street + ", " + address_postal;
      var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

      $("#edit_populate_address_translate").val(full_address);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_populate_translate_street +  ", " +
                       address_postal;

      var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                                        address_postal;

      $("#edit_populate_address_translate").val(full_address);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
    }
  });

  $("#edit_populate_unit_2").on('keyup', function() {

    var address_houseno = $("#edit_populate_houseno").val();
    var address_unit1 = $("#edit_populate_unit_1").val();
    var address_unit2 = $("#edit_populate_unit_2").val();
    var address_street = $("#edit_populate_street").val();
    var address_postal = $("#edit_populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_populate_translate_street + ", " + address_postal;
      var full_address_eg = address_houseno + ", " + address_street + ", " + address_postal;

      $("#edit_populate_address_translate").val(full_address);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_populate_translate_street +  ", " +
                       address_postal;

      var full_address_eg = address_houseno + ", " + address_unit1 + "-" + address_unit2 + ", " + address_street +  ", " +
                            address_postal;

      $("#edit_populate_address_translate").val(full_address);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-data-hidden").val(full_address_eg);
      $(".hover").closest("div.edit_inner_opt_addr").find(".edit-address-translate-hidden").val(full_address);
    }
  });

  $("#edit_address_houseno").on('focusout', function() {
    var address_houseno = $(this).val();
    var address_street = $("#edit_address_street").val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_address_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#edit_address_unit1").val();
            var address_unit2 = $("#edit_address_unit2").val();
            var address_postal = $("#edit_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;

              translate_street = response.address[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.address[0]['chinese'] +  ", " +
                                  address_postal;

              translate_street = response.address[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_address_postal").val('');
      $("#edit_address_translated").val('');
      $("#update_btn").attr('disabled', true);

      $("#edit-familycode-table tbody").empty();
      $('#edit-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
          "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#edit_address_street").on('focusout', function() {
    var address_houseno = $("#edit_address_houseno").val();
    var address_street = $(this).val();

    if(address_houseno && address_street)
    {
      var formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        address_houseno: address_houseno,
        address_street: address_street
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/populate_address_postal",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_address_postal").val(response.address[0].address_postal);

            var address_unit1 = $("#edit_address_unit1").val();
            var address_unit2 = $("#edit_address_unit2").val();
            var address_postal = $("#edit_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.address[0]['chinese'] + ", " + address_postal;

              translate_street = response.address[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.address[0]['chinese'] +  ", " +
                                  address_postal;

              translate_street = response.address[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_address_postal").val('');
      $("#edit_address_translated").val('');

      $("#update_btn").attr('disabled', true);

      $("#edit-familycode-table tbody").empty();
      $('#edit-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
          "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#edit_address_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#edit_address_postal').val(ui.item.value);
    }
  });

  $("#edit_address_postal").on('focusout', function() {
    var address_postal = $(this).val();

    if(address_postal)
    {
      var formData = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          address_postal: address_postal
      };

      $.ajax({
          type: 'GET',
          url: "/operator/search/address_translate",
          data: formData,
          dataType: 'json',
          success: function(response)
          {
            $("#edit_address_houseno").val(response.translate_street[0].address_houseno);
            $("#edit_address_street").val(response.translate_street[0].english);

            var address_houseno = $("#edit_address_houseno").val();
            var address_unit1 = $("#edit_address_unit1").val();
            var address_unit2 = $("#edit_address_unit2").val();
            var address_postal = $("#edit_address_postal").val();

            if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
            {
              var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;

              edit_translate_street = response.translate_street[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
            else
            {
              var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                  address_postal;

              edit_translate_street = response.translate_street[0]['chinese'];
              $("#edit_address_translated").val(full_address);
            }
          },

          error: function (response) {
              console.log(response);
          }
      });
    }

    else
    {
      $("#edit_address_houseno").val('');
      $("#edit_address_unit1").val('');
      $("#edit_address_unit2").val('');
      $("#edit_address_street").val('');
      $("#edit_address_translated").val('');

      $("#update_btn").attr('disabled', true);

      $("#edit-familycode-table tbody").empty();
      $('#edit-familycode-table tbody').append("<tr id='relocation_no_familycode'>" +
          "<td colspan='3'>No Family Code</td></tr>");
    }
  });

  $("#edit_address_unit1").on('keyup', function() {

    var address_houseno = $("#edit_address_houseno").val();
    var address_unit1 = $("#edit_address_unit1").val();
    var address_unit2 = $("#edit_address_unit2").val();
    var address_street = $("#edit_address_translated").val();
    var address_postal = $("#edit_address_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_translate_street + ", " + address_postal;

      $("#edit_address_translated").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_translate_street +  ", " +
                       address_postal;

      $("#edit_address_translated").val(full_address);
    }
  });

  $("#edit_address_unit2").on('keyup', function() {

    var address_houseno = $("#edit_address_houseno").val();
    var address_unit1 = $("#edit_address_unit1").val();
    var address_unit2 = $("#edit_address_unit2").val();
    var address_street = $("#edit_address_translated").val();
    var address_postal = $("#edit_address_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_translate_street + ", " + address_postal;

      $("#edit_address_translated").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_translate_street +  ", " +
                       address_postal;

      $("#edit_address_translated").val(full_address);
    }
  });
});
