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

		// $("#main-page").click(function() {
		// 		localStorage.removeItem('activeTab');
		// });

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

	//  $("#focus_devotee_form").submit(function(){
	 //
  //       var valid = 0;
  //       $(this).find('input[type=text]').each(function(){
  //           if($(this).val() != "") valid+=1;
  //       });
	 //
	// 			$('.nav-tabs li:eq(6) a').tab('show');
  //   });
	 //
		$("#quick_search").click(function() {
			var search_table = $('#search_table');
			var id = $("#search_table tbody").attr("id");

	    if(search_table.children().length >= 2 && id == 'no-record'){
				localStorage.removeItem('activeTab');
				$('.nav-tabs li:eq(6) a').tab('show');
	    }
		});

		var search_table = $('#search_table');
		var id = $("#search_table tbody").attr("id");

		if(search_table.children().length >= 2 && id == 'records'){
			$('.nav-tabs li:eq(6) a').tab('show');
		}
});
