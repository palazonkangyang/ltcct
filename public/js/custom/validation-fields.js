$(function() {

  $("#content_other_dialect").on('focusout', function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var other_dialect = $(this).val();

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      other_dialect: other_dialect,
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search-dialect",
        data: formData,
        context: this,
        dataType: 'json',
        success: function(response)
        {
          if(response.dialect != null)
          {
            $(this).parent().addClass('has-error');
          }

          else
          {
            $(this).parent().removeClass('has-error');
          }
        },

        error: function (response) {
          console.log(response);
        }
    });

  });

  $("#content_deceased_year").on('focusout', function() {

    var deceased_year = $(this).val();

    if(deceased_year)
    {
      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var currentYear = (new Date).getFullYear();

      if(deceased_year > currentYear)
      {
        $(this).parent().addClass('has-error');
      }

      else
      {
        $(this).parent().removeClass('has-error');
      }
    }

    else
    {
      $(this).parent().removeClass('has-error');
    }

  });

  $("#content_other_race").on('focusout', function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var other_race = $(this).val();

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      other_race: other_race,
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search-race",
        data: formData,
        context: this,
        dataType: 'json',
        success: function(response)
        {
          if(response.race != null)
          {
            $(this).parent().addClass('has-error');
          }

          else
          {
            $(this).parent().removeClass('has-error');
          }
        },

        error: function (response) {
          console.log(response);
        }
    });
  });

  $("#edit_deceased_year").on('focusout', function() {

    var deceased_year = $(this).val();

    if(deceased_year)
    {
      var count = 0;
      var errors = new Array();
      var validationFailed = false;

      var currentYear = (new Date).getFullYear();

      if(deceased_year > currentYear)
      {
        $(this).parent().addClass('has-error');
      }

      else
      {
        $(this).parent().removeClass('has-error');
      }
    }

    else
    {
      $(this).parent().removeClass('has-error');
    }

  });

  $("#edit_other_dialect").on('focusout', function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var other_dialect = $(this).val();

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      other_dialect: other_dialect,
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search-dialect",
        data: formData,
        context: this,
        dataType: 'json',
        success: function(response)
        {
          if(response.dialect != null)
          {
            $(this).parent().addClass('has-error');
          }

          else
          {
            $(this).parent().removeClass('has-error');
          }
        },

        error: function (response) {
          console.log(response);
        }
    });

  });

  $("#edit_other_race").on('focusout', function() {

    var count = 0;
    var errors = new Array();
    var validationFailed = false;

    var other_race = $(this).val();

    var formData = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      other_race: other_race,
    };

    $.ajax({
        type: 'GET',
        url: "/operator/search-race",
        data: formData,
        context: this,
        dataType: 'json',
        success: function(response)
        {
          if(response.race != null)
          {
            $(this).parent().addClass('has-error');
          }

          else
          {
            $(this).parent().removeClass('has-error');
          }
        },

        error: function (response) {
          console.log(response);
        }
    });
  });
});
