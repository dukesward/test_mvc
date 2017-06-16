if(typeof jQuery === 'function' && window.FlashCard) {
	(function initFlashCard($) {
		var flashCard = window.FlashCard,
			currentWord = null;

		var $tabContainer = $('.card-container'),
			$loadButtons = $('.load-button'),
			$controlButtons = $('.control-button'),
			$tabs = $('.tab-container');

		flashCard.setContainer($tabContainer);
		flashCard.setProgressContainer($('.progress-container'));

		$loadButtons.find('li').each(function() {
			flashCard.attachHandler($(this));
		});

		$controlButtons.find('li').each(function() {
			flashCard.attachHandler($(this));
		});

		$tabs.find('li').each(function() {
			flashCard.configureTabs($(this));
		});

	})(jQuery);
}