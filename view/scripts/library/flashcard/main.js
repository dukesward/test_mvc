if(typeof jQuery === 'function' && FlashCard) {
	(function initFlashCard($) {
		var flashCard = new FlashCard(),
			currentWord = null;

		flashCard._service.get(function(cards) {
			injectWordCards(cards);
		});

		function injectWordCards(data) {
			var $container = $('.card-container'),
				$wordContainer = $('.word-container.word', $container),
				$handlers = $('.handlers', $container),
				$tabs = $('ul li', $('.tab-container'));
				
			if(Common_Utils.isArray(data) && data.length > 0) {
				//refresh word container on click
				$("li[util='next']").on('click', function() {
					$wordContainer.empty();
					$('.star-list').empty();
					appendWord(data, $container);
				});

				$("li[util='earn']").on('click', function() {
					currentWord['points'] += 1;

					while(currentWord['points'] >= currentWord['base'] * currentWord['stars']) {
						currentWord['points'] -= currentWord['base'];
						currentWord['stars'] ++;

						$star = $('<li></li>')
						.addClass('fa fa-asterisk')
						.attr('aria-hidden', 'true');
						$('.star-list', $container).append($star);
					}

					$('.exp.current', $container).text(currentWord['points']);
					$('.exp.next', $container).text(currentWord['base'] * currentWord['stars']);

					flashCard._service.update(function(data) {

					}, currentWord);
				});

				/*$("li[util='update']").on('click', function() {
					flashCard._service.update(function(data) {

					}, currentWord);
				});*/

				appendWord(data, $container);
				$wordContainer.css('display', 'table');

				$tabs.each(function(i, el) {
					$(el).on('click', function() {
						var util = $(el).attr('util');
						$tabs.each(function(j, e) {
							if($(e).hasClass('active')) $(e).removeClass('active');
						})

						$('.word-container', $container).each(function(j, e) {
							$(e).css('display', 'none');
						})

						$('.word-container.'+util, $container).css('display', 'table');
						$(el).addClass('active');
					});
				});
			}
		};

		function appendWord(data, $container) {
			var $wordContainer = $('.word-container.word', $container),
				chosen = chooseRandomWord(data),
				word = chosen['word'] || '',
				meaning = chosen['meaning'],
				stars = chosen['stars'],
				points = chosen['points'],
				base = chosen['base'],
				type = chosen['type'],
				tokens = word.split('|');

			currentWord = chosen;

			$('.exp.current', $container).text(points);
			$('.exp.next', $container).text(base * stars);

			$('.word-container.meaning .wrapper', $container).text(meaning);
			$('.type', $container).addClass(type).text(type);

			for(var i=0; i<stars; i++) {
				$star = $('<li></li>')
				.addClass('fa fa-asterisk')
				.attr('aria-hidden', 'true');
				$('.star-list', $container).append($star);
			}

			for(var i=0; i<tokens.length; i++) {
				var token = tokens[i].split(':'),
					$word = $('<div></div>').addClass('word').attr('i', i);

				if(token.length === 2) {
					var $hiroLabel = $('<span></span>').addClass('label').addClass('l' + i).text(token[0]);
					$word.addClass('kanji').text(token[1]);
					$word.prepend($hiroLabel);
				}else {
					$word.addClass('hiro').text(token[0]);
				}
				$wordContainer.append($word);
				$word.on('click', function() {
					var index = $(this).attr('i');
					$('.label.l'+index).addClass('display');
				})
			}
		}

		function chooseRandomWord(words) {
			var num = words.length,
				random = Math.random() * (words.length);
			//console.log(words);
			return words[Math.floor(random)];
		}

	})(jQuery);
}