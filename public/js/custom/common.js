$(function() {

	// Disabled Quick Search Button
    var title = $("#title").val();

    if(title)
    {
        $("#focus_devotee_form input[type=text]").attr("disabled", true);
        $("#quick_search").attr("disabled", true);
    }

		$("#main-page").click(function() {
				localStorage.removeItem('activeTab');
		});

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
				// console.log(path);
				// console.log($(this).attr('href'));

	      if ($(this).attr('href') == path) {
					$(this).parent().addClass('active');
					$(this).closest(".mega-menu-dropdown" ).addClass('active');
	      }
   });

});
