;(function($) {

	$(document).ready(function() {
		var loader = EventLoader($),
			renderer = new Renderer(),
			$refresh = $('.refresh');

		function processPostData(data) {
			
		}

		if(loader) {
			$refresh.on('click', function() {
				loader.pull(function(data) {
					renderer.initNewBoard(data);
				});
			})

			loader.pull(function(data) {
				renderer.initNewBoard(data);
			});
		}
	})

}(jQuery));