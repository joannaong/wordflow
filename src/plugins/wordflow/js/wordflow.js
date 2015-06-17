jQuery(document).ready(function($) {

	$(".wordflow-build").on("click", function(e) {
		e.preventDefault();
		var siteURL = $(this).data("siteurl");
		var getfunction = $(this).data("getfunction");
		var URL = siteURL+'/?json=wordflow.get_'+getfunction;
		var href = $(this).attr("href");
		var target = $(this).attr("target");
		var postID = $(this).data("postid");

		$.get(URL, function() {
			window.location = href
		});

	});


	$(".wordflow-build-all").on("click", function(e) {
		e.preventDefault();
		var siteURL = $(this).data("siteurl");
		var href = $(this).attr("href");

		$.when(
			$.get(siteURL+"/?json=wordflow.get_menu"),
			$.get(siteURL+"/?json=wordflow.get_sample_page")
		).then(function(a, b, c, d, e) { 
			window.location = href;
		});

	});

});