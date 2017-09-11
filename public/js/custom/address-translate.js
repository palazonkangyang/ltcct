$(function() {

  var translate_street = "";
  var populate_translate_street = "";
  var edit_populate_translate_street = "";
  var edit_translate_street = "";

  $("#content_address_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#content_address_postal').val(ui.item.value);
    }
  });

  $("#content_address_postal").on('focusout', function() {
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
          var address_postal = $("#populate_postal").val();

          if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
          {
            var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;

            populate_translate_street = response.translate_street[0]['chinese'];
            $("#populate_address_translate").val(full_address);
          }
          else
          {
            var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                address_postal;

            populate_translate_street = response.translate_street[0]['chinese'];
            $("#populate_address_translate").val(full_address);
          }
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

      $("#populate_address_translate").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + populate_translate_street +  ", " +
                       address_postal;

      $("#populate_address_translate").val(full_address);
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

      $("#populate_address_translate").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + populate_translate_street +  ", " +
                       address_postal;

      $("#populate_address_translate").val(full_address);
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

  $("#edit_populate_postal").autocomplete({
    source: "/operator/search/address_postal",
    minLength: 1,
    select: function(event, ui) {
      $('#edit_populate_postal').val(ui.item.value);
    }
  });

  $("#edit_populate_postal").on('focusout', function() {
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
          $("#edit_populate_houseno").val(response.translate_street[0].address_houseno);
          $("#edit_populate_street").val(response.translate_street[0].english);

          var address_houseno = $("#edit_populate_houseno").val();
          var address_unit1 = $("#edit_populate_unit_1").val();
          var address_unit2 = $("#edit_populate_unit_2").val();
          var address_postal = $("#edit_populate_postal").val();

          if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
          {
            var full_address = address_houseno + ", " + response.translate_street[0]['chinese'] + ", " + address_postal;

            edit_populate_translate_street = response.translate_street[0]['chinese'];
            $("#edit_populate_address_translate").val(full_address);
          }
          else
          {
            var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + response.translate_street[0]['chinese'] +  ", " +
                                address_postal;

            edit_populate_translate_street = response.translate_street[0]['chinese'];
            $("#edit_populate_address_translate").val(full_address);
          }
        },

        error: function (response) {
            console.log(response);
        }
    });
  });

  $("#edit_populate_unit_1").on('keyup', function() {

    var address_houseno = $("#edit_populate_houseno").val();
    var address_unit1 = $("#edit_populate_unit_1").val();
    var address_unit2 = $("#edit_populate_unit_2").val();
    var address_street = $("#edit_populate_address_translate").val();
    var address_postal = $("#edit_populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_populate_translate_street + ", " + address_postal;

      $("#edit_populate_address_translate").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_populate_translate_street +  ", " +
                       address_postal;

      $("#edit_populate_address_translate").val(full_address);
    }
  });

  $("#edit_populate_unit_2").on('keyup', function() {

    var address_houseno = $("#edit_populate_houseno").val();
    var address_unit1 = $("#edit_populate_unit_1").val();
    var address_unit2 = $("#edit_populate_unit_2").val();
    var address_street = $("#edit_populate_address_translate").val();
    var address_postal = $("#edit_populate_postal").val();

    if($.trim(address_unit1).length <= 0 && $.trim(address_unit2).length <= 0)
    {
      var full_address = address_houseno + ", " + edit_populate_translate_street + ", " + address_postal;

      $("#edit_populate_address_translate").val(full_address);
    }
    else
    {
      var full_address = address_houseno + ", #" + address_unit1 + "-" + address_unit2 + ", " + edit_populate_translate_street +  ", " +
                       address_postal;

      $("#edit_populate_address_translate").val(full_address);
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
