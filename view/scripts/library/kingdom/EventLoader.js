;var EventLoader = function($) {
	
	var host = "mvc",
		url_base = "kingdom";

	var processAjaxRequest = function(url, post, callback) {
		var url_full = url_base + '/' + url;

		$.ajax({
			url: url_full,
			dataType: 'json'
		})
		.done(function(data) {
			if(callback) {
				callback(data);
			}
		})
	}

	return {
		pull: function(callback) {
			processAjaxRequest("pulling", null, callback);
		},
		push: function(data) {
			processAjaxRequest("pushing", data);
		}
	}
};
