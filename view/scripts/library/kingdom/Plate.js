;var Plate = function(type, sub) {
	this._proto = 'Plate';
	this._type = type;
	this._sub = sub;
	this._attr = [];
	this._children = [];
	this._parent = null;
	this._source = null;
	this._template = $("<div></div>");
	this._base = "/staticcontent/image/";
	this.addAttr(this._type);
	this.addAttr(this._sub);
	//this._loader = EventLoader($);
};

Plate.prototype.addAttr = function(attr) {
	this._attr.push(attr);
}

Plate.prototype.attachChild = function(children) {
	if(Common_Utils.isArray(children)) {
		for(var i=0; i<children.length; i++) {
			//now undefined child will not be attached
			if(children[i]) {
				children[i]._parent = this._template;
				this._children.push(children[i]);
			}
		}
	}else {
		this._children.push(children);
		children._parent = this._template;
	}
}

Plate.prototype.attachParent = function(parent) {
	if(parent) this._parent = parent;
}

Plate.prototype.attachHandler = function(evt, el) {
	switch(evt) {
		case 'click':
			var self = this;
			el.hideTemplate();
			this._template.on('click', function() {
				if(el._proto === 'Plate') {
					el.showTemplate();
				}else if(typeof el === 'function') {
					el.call(self);
				}
			})
			break;
	}
}

Plate.prototype.settlePlate = function(data) {
	this._source = data;
	//'cls_' + this._player._class
	switch(this._type) {
		case 'icon':
			if(this._source['style']) this._template.addClass(this._source['style']);
			
			if(!this._imgBox) this._imgBox = $("<div></div>");
			if(this._source['style']) {
				this._imgBox
				.css('background-color', this._source.style || '#fff');
			}
			
			if(!this._levelTag) this._levelTag = $("<div></div>");
			if(this._source['sub']) {
				this._levelTag
				.text(this._source.sub);
			}

			if(this._source['subStyle']) {
				this._levelTag
				.addClass(this._source.subStyle);
			}

			if(this._source['main']) {
				this._url = this._base + "icon/" + this._sub + '/' + this._source.main + '.' + (this._source.ext || 'jpg');
				$img = $("<img>");
				$img.attr('src', this._url);
				this._imgBox
				.append($img);
			}
			break;
		case 'avatar':
			this._player = data;

			this._icon = new Plate('icon', 'player');
			//console.log(this._player._class);
			this._icon.settlePlate({
				'main': 'cls_' + this._player._class,
				'style': 'big',
				'sub': this._player._level,
				'subStyle': this._player._isPlayer() ? this._player._gender.toLowerCase() : 'N'
			});
			this.attachChild(this._icon);

			this._statePlate = new Plate('container', 'state'); 

			this._nameCard = new Plate('label', 'name');
			this._nameCard.settlePlate(this._player._name);
			this._statePlate.attachChild(this._nameCard);

			this._hpCard = new Plate('label', 'state');
			this._hpCard.settlePlate('hp:' + this._player._hp + '|' + this._player._max_hp);
			this._statePlate.attachChild(this._hpCard);

			this.attachChild(this._statePlate);
			break;
		case 'label':
			this._text = this._settleLabelText(data);
			if(this._stateName) {
				this._template.append(this._generateInner());
				this._template.append(this._generateStateText());
			}else {
				this._template.text(this._text);
			}
			this._settleTemplateStyles(data);
			break;
		case 'message':
			this._msgContent = data;
			break;
		case 'calendar':
			this._date = this._query(data, 'setting', 'global_time_day', 'value');
			this._time = this._query(data, 'setting', 'global_time_time', 'value');

			this._dateCard = new Plate('label', 'date');
			this._dateCard.settlePlate(this._date);
			this.attachChild(this._dateCard);

			this._timeCard = new Plate('label', 'time');
			this._timeCard.settlePlate(this._time);
			this.attachChild(this._timeCard);
			//console.log(this._date);
			break;
		case 'table':
			this._data = data;
			if(!this._table) this._table = $("<table></table>");
			if(this._sub === 'price') {
				//console.log(data);
				for(var p in data) {
					if(data[p]) {
						var icon = new Plate('icon', 'price'),
							price = new Plate('label', 'text');

						icon.settlePlate({
							'main': p,
							'ext' : 'gif'
						});
						price.settlePlate(data[p]);

						this['_' + p].append(price.render());
						this['_' + p].append(icon.render());
					}else {
						this['_' + p].css('display', 'none');
					}
				}
			}
			break;
		case 'list':
			this._data = data;
			this._list = $("<ul></ul>");
		case 'item':
			this._data = data;
			if(this._icon) {
				this._icon.settlePlate({
					'main' : this._data['icon']
				});
			}

			if(this._board) {
				this._board.settlePlate(this._data);
			}
			break;
		case 'container':
			if(this._sub === 'itemInfo') {
				this._itemName.settlePlate(data['brief'] + '|' + data['quality']);
				this._itemQuality.settlePlate('Item Level ' + data['level']);
				//this._itemRequire.settlePlate('Requires Level ' + data['require_level']);
				this._priceLabel.settlePlate(data['price']);
				this._usage.settlePlate(data['usage']);
				this._desc.settlePlate('"' + data['description'] + '"');
			}else if(this._sub === 'equipInfo') {
				this._itemName.settlePlate(data['name'] + '|' + data['quality']);
				this._itemQuality.settlePlate('Item Level ' + data['level']);
				this._priceLabel.settlePlate(data['price']);
				this._equipDetail.settlePlate(data);
			}else if(this._sub === 'equipDetail') {
				var equipLiterals = Common_Utils.processEquipLiterals(data);
				for(var a in equipLiterals) {
					var label = new Plate('label', a);
					label.settlePlate(equipLiterals[a]);
					this._template.append(label.render());
				}
			}
			this._url = this._base + this._sub + '/' + this._source + '.jpg';
			break;
		case 'priceLabel':
			var price = Common_Utils.processPrice(data);
			this._table.settlePlate(price);
			break;
		case 'button':
			this._prepareButton();
			this._attachButtonEvent(data);
			break;
	}
	
}

Plate.prototype.render = function() {
	var template = this._template;

	switch(this._type) {
		case 'icon':
			//var size = this._configIconSize();
			this._prepareIconImg();
			//this._imgBox.width(size[0] + 2);
			//this._imgBox.height(size[1] + 2);
			template.append(this._imgBox);
			template.append(this._levelTag);
			break;
		case 'tag':
			var url = this._base + 'tag/' + this._sub + '.jpg',
				content = this._settleTagText();
			template.css('min-width', '70px');
			template.height(16);
			template.css('background', 'url(' + url + ')');
			template.text(content);
			break;
		case 'label':
			var content = this._text || 'Err: Content Not Defined';
			//template.css('min-width', '100px');
			break;
		case 'message':
			var content = this._msgContent || 'Err: Message Content Not Defined';
			template.append($.parseHTML(content));
			break;		
		case 'container':
			if(this._url) {
				template.css('background', 'url(' + this._url + ')');
				template.css('background-size', '100% auto');
			}

			if(this._sub === 'itemInfo' || this._sub === 'equipInfo') {
				this._itemName = new Plate('label', 'item');
				this._itemQuality = new Plate('label', 'quality');
				this._itemRequire = new Plate('label', 'text');
				this._priceLabel = new Plate('priceLabel');
				this._moreInfo = new Plate('container', 'moreInfo');
				this._usage = new Plate('label', 'usage');
				this._desc = new Plate('label', 'desc');
				this._moreInfo.attachChild([this._usage, this._desc]);
				if(this._sub === 'equipInfo') {
					this._equipDetail = new Plate('container', 'equipDetail');
				}
				this.attachChild([this._itemName, this._itemQuality, this._priceLabel, this._equipDetail, this._moreInfo]);
			}
			break;
		case 'table':
			if(this._sub === 'price') {
				this._table = $("<table></table>");
				var $tr = $("<tr></tr>");

				this._gold = $("<td class='gold'></td>");
				this._silver = $("<td class='silver'></td>");
				this._copper = $("<td class='copper'></td>");

				$tr.append(this._gold);
				$tr.append(this._silver);
				$tr.append(this._copper);
				this._table.append($tr);
			}else {
				for(var d in this._data) {
					var $tr = $("<tr></tr>"),
						$td = $("<td></td>");

					$tr.addClass(d);
					$d = this._createTableLabel(Common_Utils.capitalizeAllTokens(d), $td);
					$data = this._createTableValue(this._data[d], $td);

					$td.append($d).append($data);
					$tr.append($td);
					this._table.append($tr);
				};
			}
			template.append(this._table);
			break;
		case 'list':
			for(var d in this._data) {
				var $li = $("<li></li>");

				$data = this._createListData(d, this._data[d], $li);
				this.attachChild($data);
				$data.attachParent($li);
				this._list.append($li);
			}
			template.append(this._list);
			break;
		case 'item':
			if(!this._icon) this._icon = new Plate('icon', this._sub);
			this._icon.settlePlate({
				'style': 'big'
			});
			this.attachChild(this._icon);

			if(!this._board) this._board = new Plate('container', this._sub + 'Info');
			this.attachChild(this._board);
			break;
		case 'priceLabel':
			this._table = new Plate('table', 'price');
			this.attachChild(this._table);
			break;
		case 'button':
			//this._prepareButton();
			break;
	}

	for(var attr in this._attr) {
		if(this._attr[attr]) template.addClass(this._attr[attr]);
	}

	for(var child in this._children) {
		//console.log(this._children[child]);
		this._children[child]._parent.append(this._children[child].render());
		//template.append(this._children[child].render());
	}
	return template;
}

Plate.prototype.hideTemplate = function() {
	this._template.css('display', 'none');
}

Plate.prototype.foldTemplate = function() {
	this._template.slideUp();
}

Plate.prototype.showTemplate = function() {
	this._parent.children().each(function(i, e) {
		$(e).slideUp();
	})
	this._template.slideDown();
}

Plate.prototype._prepareIconImg = function() {
	if(!this._imgBox) this._imgBox = $("<div></div>");

	this._imgBox
	.addClass('box');

	if(!this._levelTag) this._levelTag = $("<div></div>");
	this._levelTag
	.addClass('level');
}

Plate.prototype._prepareButton = function() {
	var self = this;
	switch (this._sub) {
		case 'play':
			this._play = $("<i class='fa fa-play-circle'></i>"),
			this._stop = $("<i class='fa fa-pause' style='display:none'></i>");

			this._play.on('click', function() {
				self._play.css('display', 'none');
				self._stop.css('display', 'block');
			});

			this._stop.on('click', function() {
				self._stop.css('display', 'none');
				self._play.css('display', 'block');
			});

			this._template.append(this._play);
			this._template.append(this._stop);
			break;
	}
}

Plate.prototype._attachButtonEvent = function(data) {
	var self = this;
	switch (this._sub) {
		case 'play':
			if(data['play']) {
				this._play.on('click', function() {
					data['play'].call(this);
				})
			}

			if(data['stop']) {
				this._stop.on('click', function() {
					data['stop'].call(this);
				})
			}
			break;
	}
}

Plate.prototype._settleTemplateStyles = function(data) {
	var template = this._template;
	switch(this._sub) {
		case 'name':
			template.css('background', 'linear-gradient(to right, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0))');
			break;
		case 'state':
			template.addClass(this._stateName);
			break;
		case 'item':
			var quality = data.split('|')[1];
			template.css('color', Common_Utils.mapQualityWithColor(quality));
			template.css('background', 'linear-gradient(to right, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0))');
			break;
		case 'quality':
			template.css('color', '#ffd100');
			break;
		case 'text':
			template.css('color', '#fff');
			template.css('font-size', '12px');
			break;
	}
}

Plate.prototype._settleTagText = function() {
	var text = '';
	switch(this._sub) {
		case 'recruit':
			text = "new recruit";
			break;
		case 'event':
			text = "event";
			break;
		case 'announcement':
			text = "announcement";
			break;
		case 'battle':
			text = "battle";
			break;
	}
	return text;
}

Plate.prototype._generateInner = function() {
	var $inner = $("<span></span>"),
		width = this._stateInner / this._stateOuter;

	$inner.addClass('inner');
	$inner.width(Math.round(width * 95));
	return $inner;
}

Plate.prototype._generateStateText = function() {
	var $text = $("<span></span>");

	$text.addClass('text');
	$text.text(this._text);
	return $text;
}

Plate.prototype._settleLabelText = function(data) {
	var text = '';
	switch(this._sub) {
		case 'name':
			text = Common_Utils.capitalizeAllTokens(data);
			break;
		case 'date':
			text = Common_Utils.processTimeFormat(data);
			text = text.split('|').join(' / ');
			break;
		case 'time':
			text = Common_Utils.processTimeFormat(data);
			text = text.split('|').join(' : ');
			break;
		case 'state':
			var tokens = data.split(':'),
				states = tokens[1].split('|');
			this._stateName = tokens[0];
			this._stateInner = states[0];
			this._stateOuter = states[1];
			text = tokens[1].split('|').join(' / ');
			break;
		case 'item':
			text = data.split('|')[0];
			break;
		default:
			text = data;
			break;
	}
	return text;
}

Plate.prototype._query = function(data, key, val, attr) {
	var collection = Common_Utils.query(data, key, val),
		value = null;

	if(collection.length > 0) {
		value = Common_Utils.searchProp(collection, '0->' + attr);
	}
	return value;
}

Plate.prototype._createTableLabel = function(data, parent) {
	var $label;
	switch(this._sub) {
		case 'text':
			$label = $("<span></span>");
			$label.text(data);
			break;
		case 'icon':
			$label = new Plate('icon', 'table');
			$label.settlePlate({
				'main': data
			});
			//this.attachChild($label);
			$label = $label.render();
			break;
		case 'equips':
			$label = $("<span></span>");
			$label.text(data);
			break;
	}
	return $label;
}

Plate.prototype._createTableValue = function(data, parent) {
	var $label;
	switch(this._sub) {
		case 'text':
			$label = $("<span></span>");
			$label.text(data);
			break;
		case 'icon':
			$label = $("<span></span>");
			$label.text(data);
			break;
		case 'equips':
			var tokens = data.split('|');
			$label = new Plate('icon', 'equip');
			$label.settlePlate({
				'main': tokens[0],
				'style': Common_Utils.mapQualityWithColor(tokens[1])
			});
			//this.attachChild($label);
			$label = $label.render();
			break;
	}
	return $label;
}

Plate.prototype._createListData = function(d, n, parent) {
	var $li;
	switch(this._sub) {
		case 'items':
			$li = new Plate('icon', 'item');
			this._hold = true;
			var self = this,
				$itemInfo = new Plate('item', 'item');
			loader.pullItem({'type':'item','id':d}, function(item) {
				//console.log(item);
				$li.settlePlate({
					'main' : item.icon,
					'sub'  : n,
					'style': Common_Utils.mapQualityWithColor(item.quality)
				});
				if(self._infoBoard) {
					$itemInfo.settlePlate(item);
				}
			});
			//$li.attachChild($itemInfo);
			this._infoBoard.attachChild($itemInfo);
			$li.attachHandler('click', $itemInfo);
			//$li.attachParent(parent);
			break;
		case 'equips':
			$li = new Plate('icon', 'equip');
			this._hold = true;
			var self = this,
				$itemInfo = new Plate('item', 'equip');
			loader.pullItem({'type':'equips','id':d}, function(equip) {
				//console.log(equip);
				$li.settlePlate({
					'main': equip.icon,
					'sub' : n,
					'style': Common_Utils.mapQualityWithColor(equip.quality)
				});
				if(self._infoBoard) {
					$itemInfo.settlePlate(equip);
				}
			});
			//$li.attachChild($itemInfo);
			this._infoBoard.attachChild($itemInfo);
			$li.attachHandler('click', $itemInfo);
			//$li.attachParent(parent);
			break;
	}
	return $li;
}