$(function() {

	// tab update URL
	  $('.tabbable-bordered ul li a').click(function () {location.hash = $(this).attr('href');});

	$('.nav-tabs a').click(function(){
	    $(".alert-success").remove();
			$(".validation-error").empty();
			$(".validation-error").removeClass("bg-danger alert alert-error");
	});

	$(window).bind("load", function() {
		var samefamilycode = localStorage.getItem('samefamilycode');
		var tab = localStorage.getItem('tab');
		var newtab = localStorage.getItem('newtab');
		var trans = localStorage.getItem('trans');
		var path = window.location.pathname;

		if(samefamilycode)
		{
			var focus_address_houseno = $("#focus_address_houseno").val();
			var focus_address_street = $("#focus_address_street").val();
			var focus_address_postal = $("#focus_address_postal").val();
			var focus_address_unit = $("#focus_address_unit").val();
			var focus_address_translate = $("#focus_address_translate").val();
			var focus_oversea_addr_in_chinese = $("#focus_oversea_addr_in_chinese").val();

			if($.trim(focus_address_houseno).length > 0)
			{
				var strVale = focus_address_unit;
				arr = strVale.split('-');

				for(i = 0; i < arr.length; i++)
				{
					var focus_address_unit1 = arr[0];
					var focus_address_unit2 = arr[1];
				}

				$("#content_address_houseno").val(focus_address_houseno);
				$("#content_address_unit1").val(focus_address_unit1);
				$("#content_address_unit2").val(focus_address_unit2);
				$("#content_address_street").val(focus_address_street);
				$("#content_address_postal").val(focus_address_postal);
				$("#content_address_translated").val(focus_address_translate);

				$("#content_address_houseno").attr('readonly', true);
				$("#content_address_street").attr('readonly', true);
				$("#content_address_unit1").attr('readonly', true);
				$("#content_address_unit2").attr('readonly', true);
				$("#content_address_postal").attr('readonly', true);
				$("#content_address_translated").attr('readonly', true);
				$("#content_oversea_addr_in_chinese").attr('readonly', true);

				setTimeout(function(){ $("input:radio[name=familycode_id]").prop( "checked", true ); }, 1000);
			}

			else
			{
				$("#content_oversea_addr_in_chinese").val(focus_oversea_addr_in_chinese);

				setTimeout(function(){ $("input:radio[name=familycode_id]").prop( "checked", true ); }, 1000);
			}

			$(".check_family_code").click();
			localStorage.removeItem('samefamilycode');
		}

		if(tab == 1 && path == '/operator/index')
		{
			$('.nav-tabs li:eq(6) a').tab('show');
			localStorage.removeItem('tab');
		}

		if(newtab == 1)
		{
			$('.nav-tabs li:eq(0) a').tab('show');
			localStorage.removeItem('newtab');
		}
	});

	var chinese_name = $("#chinese_name").val();

	if(chinese_name)
	{
		$("#focus_devotee_form input[type=text]").attr("disabled", true);
		$("#quick_search").attr("disabled", true);

		$("#edit").removeClass("disabled");
	}

		// new button
		$("#new_search").click(function() {
			localStorage.removeItem('activeTab');

			localStorage.setItem('newtab', '1');

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
			localStorage.removeItem('tab');
			localStorage.removeItem('newtab');
		});

		var path = window.location.pathname;

		$('.navbar-nav li a').each(function() {
	    if ($(this).attr('href') == path) {

				$(this).parent().addClass('active');
				$(this).closest(".mega-menu-dropdown" ).addClass('active');
	    }
   });

	 $("#new").click(function() {

		localStorage.removeItem('activeTab');
		localStorage.removeItem('samefamilycode');

		window.location.href = "http://" + location.host + "/operator/index#tab_newdevotee";
		var hash = document.location.hash;

		if (hash) {
		 $('.nav-tabs a[href="'+hash+'"]').tab('show');
		}

		$("#new-devotee-form")[0].reset();
		$("#familycode-table tbody").empty();
		$("#familycode-table tbody").append("<tr id='no_familycode'><td colspan='3'>No Family Code</td></tr>");
	 });

    if($("#quick_search").prop('disabled') == false){
      $("#same_familycode").attr("disabled", true);
    }

		$("#quick_search").click(function() {
			localStorage.setItem('tab', '1');
		});

		$("#phone_no").keypress(function (e) {
	     //if the letter is not digit then display error and don't type anything
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        //display error message
	        return false;
			}
		});

		$("#new_same_familycode").click(function() {

			$("#content_address_houseno").attr('readonly', true);
			$("#content_address_unit1").attr('readonly', true);
			$("#content_address_unit2").attr('readonly', true);
			$("#content_address_street").attr('readonly', true);
			$("#content_address_postal").attr('readonly', true);
			$("#content_oversea_addr_in_chinese").attr('readonly', true);

			localStorage.removeItem('activeTab');

			window.location.href = "http://" + location.host + "/operator/index#tab_newdevotee";
			var hash = document.location.hash;

			if (hash) {
			 $('.nav-tabs a[href="'+hash+'"]').tab('show');
			}

			localStorage.setItem('samefamilycode', '1');

			var focus_address_houseno = $("#focus_address_houseno").val();
			var focus_address_street = $("#focus_address_street").val();
			var focus_address_postal = $("#focus_address_postal").val();
			var focus_address_unit = $("#focus_address_unit").val();
			var focus_address_translate = $("#focus_address_translate").val();
			var focus_oversea_addr_in_chinese = $("#focus_oversea_addr_in_chinese").val();

			if($.trim(focus_address_houseno).length > 0)
			{
				var strVale = focus_address_unit;
				arr = strVale.split('-');

				for(i = 0; i < arr.length; i++)
				{
					var focus_address_unit1 = arr[0];
					var focus_address_unit2 = arr[1];
				}

				$("#content_address_houseno").val(focus_address_houseno);
				$("#content_address_unit1").val(focus_address_unit1);
				$("#content_address_unit2").val(focus_address_unit2);
				$("#content_address_street").val(focus_address_street);
				$("#content_address_postal").val(focus_address_postal);
				$("#content_address_translated").val(focus_address_translate);
			}

			else
			{
				$("#content_oversea_addr_in_chinese").val(focus_oversea_addr_in_chinese);
			}

			$(".check_family_code").click();

			setTimeout(function(){
				$("input:radio[name=familycode_id]").prop( "checked", true );
			}, 1000);

		});

    $( "#dialog-box" ).dialog({
      autoOpen: false
    });

    $( "#edit-dialog-box" ).dialog({
      autoOpen: false
    });

    $("#dialog-box").dialog({
     autoOpen: false,
     modal: true,
     buttons : {
          "No, Mistake" : function() {
              $(this).dialog("close");
          },
          "Yes, Cancel" : function() {
            window.location.reload(true);
            $(this).dialog("close");
          }
        }
    });

    $("#edit-dialog-box").dialog({
     autoOpen: false,
     modal: true,
     buttons : {
          "No, Mistake" : function() {
            $(this).dialog("close");
          },
          "Yes, Cancel" : function() {
            window.location.reload(true);
            $(this).dialog("close");
          }
        }
    });

		$("#relocation-dialog-box").dialog({
     autoOpen: false,
     modal: true,
     buttons : {
          "No, Mistake" : function() {
              $(this).dialog("close");
          },
          "Yes, Cancel" : function() {
            window.location.reload(true);
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
		   $("#edit-dialog-box").dialog("open");
		});

		$("#cancel_relocation_btn").on("click", function(e) {
      e.preventDefault();
      $("#relocation-dialog-box").dialog("open");
  	});

		$(".hylink").click(function() {
			localStorage.removeItem('activeTab');
			localStorage.removeItem('tab');
			localStorage.removeItem('newtab');
		});
});
