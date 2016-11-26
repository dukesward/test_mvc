;var Board = function(type) {
	this._type = type;
	this.renderBoard();
};

Board.prototype.renderBoard = function() {
	this._stage = $("#kingdom_middle_field");
	this._left = $("#kingdom_left_field");
	this._container = new Plate('container', 'scene');
	this._info = new Plate('container', 'info');
	this['create' + this._type + 'Board'].call(this);
}

Board.prototype.createNewPlayerBoard = function() {
	this._player = null;
	this._location = null;
	this._world = null;
	this._tag = new Plate('tag', 'recruit');
	this._calendar = new Plate('calendar');
	this._message = new Plate('message', 'recruit');

	this._avatar = new Plate('avatar', 'player');
	this._leftAvatar = new Plate('avatar', 'player');

	this._leftInfo = new Plate('table', 'text'),
	this._leftIcons = new Plate('table', 'icon');
	this._leftEquips = new Plate('table', 'equips');
	this._leftItems = new Plate('list', 'items');
	this._leftItemInfo = new Plate('container', 'item');
	this.createPlayerInfoBoard();

	this._container.attachChild([
		this._tag, 
		this._calendar, 
		this._avatar, 
		this._message, 
		//this._info
	]);

	this._avatar.attachHandler('click', this._info);
}

Board.prototype.createNewEventBoard = function() {
	this._player = null;
	this._event = null;
	this._world = null;
	this._location = null;
	this._tag = new Plate('tag', 'event');
	this._calendar = new Plate('calendar');
	this._message = new Plate('message', 'event');

	this._evt = new Plate('container', 'event');

	this._avatar = new Plate('avatar', 'player');
	this._leftAvatar = new Plate('avatar', 'player');

	this._leftInfo = new Plate('table', 'text'),
	this._leftIcons = new Plate('table', 'icon');
	this._leftEquips = new Plate('table', 'equips');
	this._leftItems = new Plate('list', 'items');
	this._leftItemInfo = new Plate('container', 'item');
	this.createPlayerInfoBoard();

	this._container.attachChild([
		this._tag, 
		this._calendar,
		this._avatar,
		this._evt,
		this._message
		//this._info
	]);

	this._avatar.attachHandler('click', this._info);
}

Board.prototype.createPlayerInfoBoard = function() {
	infoPlate = this._info;
	infoPlate.attachChild(this._leftAvatar);
	infoPlate.attachChild(this._leftIcons);
	infoPlate.attachChild(this._leftInfo);
	infoPlate.attachChild(this._leftEquips);
	infoPlate.attachChild(this._leftItems);
	infoPlate.attachChild(this._leftItemInfo);
	this._info.attachParent(this._left);
}

Board.prototype.trigger = function(config) {
	if(null === this._player && config['player']) {
		this._player = config['player'];
		this._preparePlayerData();
	}

	if(null === this._location && config['location']) {
		this._location = config['location'];
		this._container.settlePlate(this._location.brief);
		switch (this._type) {
			case 'NewPlayer':
				this._message.settlePlate(this._player.translate(this._location.welcome));
				break;
		}	
	}

	if(null === this._event && config['event']) {
		this._event = config['event'];
		this._message.settlePlate(this._player.translate(this._event.description));
		this._evt.settlePlate(this._event.effect);
	}

	if(null === this._world && config['world']) {
		this._world = config['world'];
		this._calendar.settlePlate(Common_Utils.query(this._world, 'collection', 'calendar'));
	}

	this.assembly();
}

Board.prototype._preparePlayerData = function() {
	var cls = this._player._class,
		icon_id = 'cls_' + cls;

	if(Common_Utils.isArray(this._avatar)) {

	}else {
		if(this._avatar) this._avatar.settlePlate(this._player);
		if(this._leftAvatar) this._leftAvatar.settlePlate(this._player);
		if(this._leftInfo) {
			this._leftInfo.settlePlate({
				'str': this._player._attrs.str,
				'agi': this._player._attrs.agi,
				'int': this._player._attrs['int'],
				'sta': this._player._attrs.sta,
				'spr': this._player._attrs.spr,
				'luc': this._player._attrs.luc,
				'ap': this._player._props.ap,
				'blk': this._player._props.blk,
				'crt': this._player._props.crt,
				'eva': this._player._props.eva,
				'hit': this._player._props.hit
			});
		}

		if(this._leftIcons) {
			this._leftIcons.settlePlate({
				'damage': this._player.calculateDamage('literal'),
				'armor': this._player.calculateArmor()
			});
		}

		if(this._leftEquips) this._leftEquips.settlePlate(this._player.collectEquips());
		if(this._leftItems) {
			this._leftItems.settlePlate(this._player._items);
			this._leftItems._infoBoard = this._leftItemInfo;
		}
	}
}

Board.prototype.assembly = function() {
	this._stage.append(this._container.render());
	this._left.append(this._info.render());
}