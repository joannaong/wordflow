jQuery(document).ready(function($) {

	$(".visi-build").on("click", function(e) {
		e.preventDefault();

		var siteURL = $(this).data("siteurl");
		var getfunction = $(this).data("getfunction");
		var URL = siteURL+'/?json=custom.get_'+getfunction;
		var href = $(this).attr("href");
		var target = $(this).attr("target");
		var postID = $(this).data("postid");


		if (getfunction == "current_episode") {

			$.get(VOTE_API+"vote/"+postID, function(data) {
				console.log("1done", data);
				console.log(typeof data.skeptic === 'undefined');

				// if no entry, create db entry
				if (typeof data.skeptic === 'undefined' || typeof data.believer === 'undefined') {
					$.ajax({ 
						type: "POST", 
						url: VOTE_API+"vote/collection", 
						data: {id: postID, answers:["skeptic","believer"]},
						success: function() {
							console.log("2");

							setTimeout(function() {
								$.ajax({ 
									type: "POST", 
									url: VOTE_API+"vote/"+postID,
									data: {answer:"skeptic"},
									success: function() {
										console.log("3");
										setTimeout(function() {
											$.ajax({ 
												type: "POST", 
												url: VOTE_API+"vote/"+postID,
												data: {answer:"believer"},
												success: function() {
													console.log("4");
													$.get(URL, function() {
														window.location = href
													});
												}
											});
										}, 2050);
									}
								});
							}, 1050);
						}
					});

				// if has entry, redirect and build
				} else {
					$.get(URL, function() {
						window.location = href
					});
				}

			}); // get end


		} else {
			$.get(URL, function() {
				window.location = href
			});
		}

	});


	$(".visi-build-all").on("click", function(e) {
		e.preventDefault();

		var siteURL = $(this).data("siteurl");
		var href = $(this).attr("href");

		$.when(
			$.get(siteURL+"/?json=custom.get_menu"),
			$.get(siteURL+"/?json=custom.get_current_episode"),
			$.get(siteURL+"/?json=custom.get_about_page"),
			$.get(siteURL+"/?json=custom.get_sponsor_page"),
			$.get(siteURL+"/?json=custom.get_media_page"),
			$.get(siteURL+"/?json=custom.get_privacy_page")
		).then(function(a, b, c, d, e) { 
			window.location = href;
		});

	});

});