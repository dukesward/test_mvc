if(typeof jQuery === 'function' && FlashCard) {
	(function initFlashCard($) {
		var flashCard = new FlashCard();

		flashCard._service.get();
		injectWordCards(flashCard._service._card);

		function injectWordCards(data) {
			var $container = $('.card-container');

			
		}

	})(jQuery);
}