if(typeof jQuery === 'function' && FlashCard) {
	(function initFlashCard($) {
		var flashCard = new FlashCard();

		flashCard._service.get();
	})(jQuery);
}