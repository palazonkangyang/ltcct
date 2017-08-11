$(function() {

		// Disabled Quick Search Button
    var chinese_name = $("#chinese_name").val();

    if(chinese_name)
    {
			$("#focus_devotee_form input[type=text]").attr("disabled", true);
			$("#quick_search").attr("disabled", true);
			$('.nav-tabs li:eq(6) a').tab('show');

    	$("#edit").removeClass("disabled");
    }

		// new button
		$("#new_search").click(function() {
				localStorage.removeItem('activeTab');

				$("#quick_search").attr("disabled", true);
		});

		var introduced_by1 = $("#edit_introduced_by1").val();

		if($.trim(introduced_by1).length > 0)
		{
			$("#edit_introduced_by1").attr("disabled", true);
			$("#edit_introduced_by2").attr("disabled", true);
			$("#edit_approved_date").attr("disabled", true);
		}

		$("#logout").click(function() {
			localStorage.removeItem('activeTab');
		});

		var path = window.location.pathname;

		$('.navbar-nav li a').each(function() {
	    if ($(this).attr('href') == path) {

				$(this).parent().addClass('active');
				$(this).closest(".mega-menu-dropdown" ).addClass('active');
	    }
   });

	 $("#new").click(function() {
		 $('.nav-tabs li:eq(3) a').tab('show');
	 });


		$("#quick_search").click(function() {
			localStorage.removeItem('activeTab');

			var search_table = $('#search_table');
			var id = $("#search_table tbody").attr("id");

			$('.nav-tabs li:eq(6) a').tab('show');
		});

    if($("#quick_search").prop('disabled') == false){
      $("#same_familycode").attr("disabled", true);
    }

		var search_table = $('#search_table');
		var id = $("#search_table tbody").attr("id");

		if(search_table.children().length >= 2 && id == 'records'){
			$('.nav-tabs li:eq(6) a').tab('show');
		}

		$("#same_familycode").click(function() {
			$('.nav-tabs li:eq(3) a').tab('show');

			var focus_address_houseno = $("#focus_address_houseno").val();
			var focus_address_street = $("#focus_address_street").val();
			var focus_address_postal = $("#focus_address_postal").val();
			var focus_address_unit = $("#focus_address_unit").val();

			var strVale = focus_address_unit;
      arr = strVale.split('-');

      for(i=0; i < arr.length; i++)
      {
          var focus_address_unit1 = arr[0];
					var focus_address_unit2 = arr[1];
      }

			$("#content_address_houseno").val(focus_address_houseno);
			$("#content_address_unit1").val(focus_address_unit1);
			$("#content_address_unit2").val(focus_address_unit2);
			$("#content_address_street").val(focus_address_street);
			$("#content_address_postal").val(focus_address_postal);

      $(".check_family_code").click();

		});

    $("#dialog-box").dialog({
     autoOpen: false,
     modal: true,
     buttons : {
          "No, Mistake" : function() {
              $(this).dialog("close");
          },
          "Yes, Cancel" : function() {
            $('#new-devotee-form')[0].reset();
            $(this).dialog("close");
          }
        }
    });

    $("#cancel_btn").on("click", function(e) {
      e.preventDefault();
      $("#dialog-box").dialog("open");
  });

  $("#edit_cancel_btn").on("click", function(e) {
    e.preventDefault();
    $("#dialog-box").dialog("open");
});
});
