$(function() {

	// Disabled Quick Search Button
    var title = $("#title").val();

    if(title)
    {
        $("#focus_devotee_form input[type=text]").attr("disabled", true);
        $("#quick_search").attr("disabled", true);               
    }

});