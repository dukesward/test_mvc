var FlashCard = FlashCard || function(service, card) {
	this._service = new service();
	this._collection = null;
	this._container = null;
	this._progress = null;
	this._tabs = [];
	this.activeTab = "word";
	this._cachedData = null;
};

(function(root, $) {

	var KanjiMap = {

		"hirogana": ["あ","い","う","え","お"
					,"か","き","く","け","こ"
					,"さ","し","す","せ","そ"
					,"た","ち","つ","て","と"
					,"な","に","ぬ","ね","の"
					,"は","ひ","ふ","へ","ほ"
					,"ま","み","む","め","も"
					,"や","ゆ","よ","を","ん"
					,"が","ぎ","ぐ","げ","ご"
					,"ざ","じ","ず","ぜ","ぞ"
					,"だ","ぢ","づ","で","ど"
					,"ば","び","ぶ","べ","ぼ"
					,"ぱ","ぴ","ぷ","ぺ","ぽ"
					,"ゃ","ゅ","ょ"
					,"ぁ","ぇ","ぉ","っ","ー"],

		"katagana": ["ア","イ","ウ","エ","オ"
					,"カ","キ","ク","ケ","コ"
					,"サ","シ","ス","セ","ソ"
					,"タ","チ","ツ","テ","ト"
					,"ナ","ニ","ヌ","ネ","ノ"
					,"ハ","ヒ","フ","ヘ","ホ"
					,"マ","ミ","ム","メ","モ"
					,"ヤ","ユ","ヨ","ヲ","ン"
					,"ガ","ギ","グ","ゲ","ゴ"
					,"ザ","ジ","ズ","ゼ","ゾ"
					,"ダ","ヂ","ヅ","デ","ド"
					,"バ","ビ","ブ","ベ","ボ"
					,"パ","ピ","プ","ペ","ポ"
					,"ャ","ュ","ョ"
					,"ァ","ェ","ォ","ッ","ー"],

		"kanji": {
			//b
			"拜": ["拝"],
			//j
			"极": ["極"],
			"见": ["見"],
			"惊": ["驚"],
			//k
			"夸": ["誇"],
			//m
			"满": ["満"],
			//w
			"务": ["務"],
			"稳": ["穏"],
			//x
			"细": ["細"]
		}
	}

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

		service.prototype.update = function(data, callback) {
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
			this.lastSearch = -1;
		};

		card.prototype.testFilter = function(filter, referrer) {
			var result = true;

			if(filter) {
				switch(filter) {
					case 'match':
						result = this.matchWord(referrer);
						break;
				}
			}
			return result;
		}

		card.prototype.matchWord = function(r) {
			var result = false, 
				w = this.word.word, 
				kanji = KanjiMap.kanji,
				index = -1;

			if(r) {
				if(kanji[r]) {
					for(var i=0; i<kanji[r].length; i++) {
						if(w.indexOf(kanji[r][i]) >= 0) {
							index = w.indexOf(kanji[r][i]);
							result = (index > this.lastSearch);
						}
					}
				}else {
					index = w.indexOf(r);
					if(index >= 0) {
						result = (index > this.lastSearch);
					}
				}
				this.lastSearch = index;

			}else {
				result = true;
			}
			return result;
		}

		card.prototype.reformat = function() {
			var w = this.word.word,
				tokens = w.split('|');

			return tokens.reduce(function(prev, next, i) {
				var t, t0, t1;
				if(i == 1) {
					t0 = prev.split(':');
					t = t0.length > 1 ? t0[1] : t0[0];
				}else {
					t = prev;
				}
				
				t1 = next.split(':');
				t += t1.length > 1 ? t1[1] : t1[0];

				return t;
			})
		}

		var collection = function(cards) {
			this.cards = this.makeCards(cards);
			this.currentCardIndex = null;
			this.size = cards.length;
			this.referrer = null;
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
			if(!this.currentCardIndex || this.currentCardIndex > this.cards.length) {
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
				size = size || this.size,
				referrer = this.referrer;

			if(filter) {
				for(var i=0; i<this.cards.length; i++) {
					var c = this.cards[i];
					if(c.testFilter(filter, referrer)) {
						temp.push(c);
					}
				}
			}else {
				temp = cards;
			}

			while(temp.length > size) {
				var rand = Common_Utils.createRandomNumber(0, temp.length - 1, 0),
					c = temp[rand].isCard ? temp[rand] : new card(temp[rand]);

				temp = temp.splice(rand, 1);
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

		FlashCard.prototype.setSearchContainer = function($el) {
			if($el.length) {
				this._searchInput = $('input', $el);
				this._searchResult = $('.results-list', $el);
				this._searchHandler = $('.search-btn', $el);
			}
		}

		FlashCard.prototype.fetchCards = function(number, cb) {
			var self = this,
				params = null;

			if(number) {
				params = {
					numberOfCards: number
				}
			}

			if(this._service) {
				this._service.get(function(cards) {
					self._cachedData = cards;
					self._collection = new collection(cards);
					self._collection.setSize(parseInt(number));
					if(cb) {
						cb.call(self);
					}else {
						self.injectCard(self._collection.pickCard());
					}
				}, params);
			}
		}

		FlashCard.prototype.nextCard = function() {
			this.injectCard(this._collection.pickCard());
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
			var word = card || this._collection.getCurrentWord();
			this.refreshWord(word);
			this.refreshStars(word);
			this.refreshProgress();
		}

		FlashCard.prototype.injectResult = function(result) {
			var self = this,
				r = $("<li util='inject'>"
						 + "<span>"
						 	+ result.reformat()
						 + "</span>"
						 + "<span class='tag-id'>"
						 	+ result.word.id
						 + "</span>"
					 + "</li>"),
				scope = {
					inject: function() {
						self._service.update({ id: result.word.id, times_used: (result.word.times_used+1) });
						self.injectCard(result);
						self.refreshResults();
					}
				};

			this.attachHandler(r, scope);
			this._searchResult.append(r);
		}

		FlashCard.prototype.refreshResults = function() {
			this._searchResult.html('');
		}

		FlashCard.prototype.attachHandler = function(target, scope) {
			if(target.length) {
				var self = this,
					util = target.attr('util'),
					params = target.attr('param'),
					closure = function() {
						if(this[util])
							this[util].call(self, params);
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
						this.activeTab = "word";
						refreshTab("word");

						this._word.css('display', 'table');
						this._meaning.css('display', 'none');
					},
					meaning: function() {
						this.activeTab = "meaning";
						refreshTab("meaning");

						this._word.css('display', 'none');
						this._meaning.css('display', 'block');
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

		FlashCard.prototype.configureSearch = function(target) {
			this.setSearchContainer(target);
			this.attachHandler(this._searchHandler, { search: this.searchHandler })
		}

		FlashCard.prototype.searchHandler = function() {
			var input = this._searchInput.val(),
				closure = function() {
					var c = new collection(this._cachedData);
					//c.referrer = input;
					while(input) {
						c.referrer = input[0];
						c.applyFilter("match");
						input = input.substr(1);
					}
					
					for(var i=0; i<c.cards.length; i++) {
						this.injectResult(c.cards[i]);
					}
				};

			this.refreshResults();
			
			if(!this._cachedData) {
				this.fetchCards(null, closure);
			}else {
				closure.call(this);
			}
		}

		root.FlashCard = new FlashCard(service, card);

	}

})(this, jQuery);