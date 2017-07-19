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

		var path = window.location.pathname;

		$('.navbar-nav li a').each(function() {
				console.log(path);
				console.log(this.herf);
				
	      if (this.href === path) {
					//    	$(this).parent().addClass('active');
					console.log("same path");
	      }

				else {
						console.log("different path");
				}
   });

});
