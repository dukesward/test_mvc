var FlashCard = FlashCard || function(service, card) {
	this._service = new service();
	this._collection = null;
	this._container = null;
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
			this.word = word;
			this.tabs = ["word", "meaning", "example"];
			this.activeTab = this.tabs[0];
		};

		var collection = function(cards) {
			this.cards = cards;
			this.size = cards.length;
		}

		collection.prototype.setSize = function(number) {
			if(this.size > number) {
				this.size = number;
				this.refineCollection();
			}
		}

		collection.prototype.refineCollection = function(filter) {
			if(this.size < this.cards.length) {
				this.cards = this.applyFilter(this.cards, filter);
			}else if(this.size > this.cards.length) {
				this.size = this.cards.length;
			}
		}

		FlashCard.prototype.applyFilter = function(cards, filter, size) {
			var temp = [],
				size = size || this.size;

			if(size <= cards.size) {
				while(temp.size < size) {
					var rand = Common_Utils.createRandomNumber(0, cards.size, 0);
					temp.push(cards[rand]);
					cards.splice(rand, 1);
				}
			}else {
				temp = cards;
			}

			
			return temp;
		}

		FlashCard.prototype.setContainer = function($el) {
			if($el.length) {
				this._container = $el;
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
					this._collection = new collection(cards);
					this._collection.setSize(number);
					self.injectCard(new card(self.pickRandom(this._collection)));
				}, params);
			}
		}

		FlashCard.prototype.search = function() {

		}

		FlashCard.prototype.injectCard = function(card) {

		}

		FlashCard.prototype.pickRandom = function(collection) {

		}

		FlashCard.prototype.attachHandler = function(target) {
			if(target.length) {
				var self = this, 
					util = target.attr('util'),
					params = target.attr('param'),
					closure = function() {
						if(self[util])
							self[util](params);
						else
							console.warn("specified util:[" + util + "] does not exist");
					};

				target.on('click', closure);
			}
		}

		root.FlashCard = new FlashCard(service, card);

	}

})(this, jQuery);