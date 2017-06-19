var FlashCard = FlashCard || function(service, card) {
	this._service = new service();
	this._collection = null;
	this._container = null;
	this._progress = null;
	this._tabs = [];
	this.activeTab = "word";
};

(function(root, $) {

	var FlashCard;

	if(typeof root.FlashCard === 'function' && (FlashCard = root.FlashCard)) {

		var service = function() {
			this._host = 'localhost';
			this._url = 'flashcard';
			this._cards = null;
		}

		service.prototype.get = function(callback, params) {
			var url = this._url + '/get';

			if(this._host !== 'localhost') {
				url = this._host += ('/' + url);
			}

			/*if(params) {
				url += "?";
				for(var p in params) {
					url += p + "=" + params + "&";
				}
				url = url.substr(0, url.length - 1);
			}*/
			
			$.get(url, params, function(data) {
				//console.log(data);
				this._cards = data;

				if(typeof callback === 'function') {
					callback(JSON.parse(this._cards));
				}

			}.bind(this));
		}

		service.prototype.update = function(callback, data) {
			var url = this._url + '/update';

			if(this._host !== 'localhost') {
				url = this._host += ('/' + url);
			}

			if(data) {
				$.ajax({
					type: 'POST',
					url: url, 
					data: data, 
					success: function(data) {
						console.log(data);

						if(typeof callback === 'function') {
							callback(JSON.parse(data));
						}

					}.bind(this)
				});
			}
		}

		var card = function(word) {
			this.isCard = true;
			this.word = word;
		};

		card.prototype.testFilter = function(filter) {
			var result = true;

			if(filter) {
				//switch(filter) {
					
				//}
			}
			return result;
		}

		var collection = function(cards) {
			this.cards = this.makeCards(cards);
			this.currentCardIndex = null;
			this.size = cards.length;
		}

		collection.prototype.setSize = function(number) {
			if(!isNaN(number) && this.size > number) {
				this.size = number;
				this.refineCollection();
			}
		}

		collection.prototype.makeCards = function(cards) {
			var temp = [];

			for(var i=0; i<cards.length; i++) {
				var c = cards[i];
				temp.push(c.isCard ? c : new card(c));
			}
			return temp;
		}

		collection.prototype.getCurrentWord = function() {
			if(!this.currentCardIndex) {
				this.currentCardIndex = 1;
			}
			return this.cards[this.currentCardIndex - 1];
		}

		collection.prototype.refineCollection = function(filter) {
			if(this.size < this.cards.length) {
				this.applyFilter(filter);
			}else if(this.size > this.cards.length) {
				this.size = this.cards.length;
			}
		}

		collection.prototype.pickCard = function() {
			if(!this.currentCardIndex || this.currentCardIndex >= this.cards.length) {
				this.applyFilter(null, this.cards.length);
				this.currentCardIndex = 1;
			}else {
				this.currentCardIndex ++;
			}

			return this.cards[this.currentCardIndex - 1];
		}

		collection.prototype.applyFilter = function(filter, size, cards) {
			var temp = [],
				cards = cards || this.cards,
				size = size || this.size;

			if(size <= cards.length) {
				while(temp.length < size) {
					var rand = Common_Utils.createRandomNumber(0, cards.length - 1, 0),
						c = cards[rand].isCard ? cards[rand] : new card(cards[rand]);

					if(c.testFilter(filter)) {
						temp.push(c);
					}
					cards.splice(rand, 1);
				}
			}else {
				temp = cards;
			}
			//apply filter will auto replace the cards with the filtered cards
			this.cards = temp;
		}

		FlashCard.prototype.setContainer = function($el) {
			if($el.length) {
				this._container = $el;
				this._starList = $('.star-list', this._container);
				this._type = $('.type', this._container);
				this._word = $('.word', this._container);
				this._meaning = $('.meaning', this._container);
			}
		}

		FlashCard.prototype.setProgressContainer = function($el) {
			if($el.length) {
				this._progress = $el;
				this._progressCurrent = $('.current', this._progress);
				this._progressTotal = $('.total', this._progress);
			}
		}

		FlashCard.prototype.fetchCards = function(number) {
			var self = this,
				params = null;

			if(number) {
				params = {
					numberOfCards: number
				}
			}

			if(this._service) {
				this._service.get(function(cards) {
					self._collection = new collection(cards);
					self._collection.setSize(parseInt(number));
					self.injectCard(self._collection.pickCard());
				}, params);
			}
		}

		FlashCard.prototype.nextCard = function() {
			this.injectCard(this._collection.pickCard());
		}

		FlashCard.prototype.search = function() {

		}

		FlashCard.prototype.refreshStars = function(word) {
			if(this._starList.length) {
				this._starList.html('');
				for(var i=0; i<word.word.base; i++) {
					var star = "<li class='fa fa-asterisk' aria-hidden='true'></li>"
					this._starList.append($(star));
				}
			}
		}

		FlashCard.prototype.refreshWord = function(word) {
			if(this._type.length) {
				this._type.text(word.word.type);
			}

			if(this._meaning.length) {
				this._meaning.text(word.word.meaning);
			}

			if(this._word.length) {
				this._word.html('');
				var tokens = word.word.word.split('|');
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
					
					$word.on('click', function() {
						var index = $(this).attr('i');
						$('.label.l'+index).addClass('display');
					});

					this._word.append($word);
					this._word.css('display', 'table');
				}
			}	
		}

		FlashCard.prototype.refreshProgress = function() {
			if(this._progressCurrent.length) {
				this._progressCurrent.text(this._collection.currentCardIndex || 0);
			}

			if(this._progressTotal.length) {
				this._progressTotal.text(this._collection.size);
			}
		}

		FlashCard.prototype.injectCard = function(card) {
			var word = this._collection.getCurrentWord();
			this.refreshWord(word);
			this.refreshStars(word);
			this.refreshProgress();
		}

		FlashCard.prototype.attachHandler = function(target, scope) {
			if(target.length) {
				var self = this, 
					util = target.attr('util'),
					params = target.attr('param'),
					closure = function() {
						if(this[util])
							this[util].call(this, params);
						else
							console.warn("specified util:[" + util + "] does not exist");
					};

				target.on('click', closure.bind(scope || self));
			}
		}

		FlashCard.prototype.configureTabs = function(target) {
			this._tabs.push(target);
			var self = this,
				scope = {
					word: function() {
						self.activeTab = "word";
						refreshTab("word");

						self._word.css('display', 'table');
						self._meaning.css('display', 'none');
					},
					meaning: function() {
						self.activeTab = "meaning";
						refreshTab("meaning");

						self._word.css('display', 'none');
						self._meaning.css('display', 'block');
					},
					example: function() {

					}
				},
				refreshTab = function(tab) {
					for(var i=0; i<self._tabs.length; i++) {
						var util = self._tabs[i].attr('util');
						if(util === tab) {
							self._tabs[i].addClass('active');
						}else {
							self._tabs[i].removeClass('active');
						}
					}
				};

			this.attachHandler(target, scope);
		}

		root.FlashCard = new FlashCard(service, card);

	}

})(this, jQuery);