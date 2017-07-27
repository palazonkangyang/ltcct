$(function() {

	// Disabled Quick Search Button
    var title = $("#title").val();

    if(title)
    {
        $("#focus_devotee_form input[type=text]").attr("disabled", true);
        $("#quick_search").attr("disabled", true);
				$("#tab_editdevotee").attr("disabled", false);
    }

		$("#main-page").click(function() {
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
