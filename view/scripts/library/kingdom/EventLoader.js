;var EventLoader = function($) {
	
	var host = "mvc",
		url_base = "kingdom";

	var processAjaxRequest = function(url, post, callback) {
		var url_full = url_base + '/' + url,
			method = post ? 'POST' : 'GET';

		$.ajax({
			url: url_full,
			data: post || null,
			method: method,
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
		pullItem: function(r, callback) {
			processAjaxRequest("request", r, callback);
		},
		pullEquip: function(r, callback) {
			processAjaxRequest("request", r, callback);
		},
		push: function(data) {
			processAjaxRequest("pushing", data);
		}
	}
};

window.loader = EventLoader($);
