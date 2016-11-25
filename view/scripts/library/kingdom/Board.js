;var Board = function(type) {
	this._type = type;
	this.renderBoard();
};

Board.prototype.renderBoard = function() {
	this._stage = $("#kingdom_middle_field");
	this._left = $("#kingdom_left_field");
	this._container = new Plate('container', 'scene');
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
	this._info = this.createPlayerInfoBoard();

	this._container.attachChild([
		this._tag, 
		this._calendar, 
		this._avatar, 
		this._message, 
		//this._info
	]);

	this._avatar.attachHandler('click', this._info);
}

Board.prototype.createPlayerInfoBoard = function() {
	var infoPlate = new Plate('container', 'info');

	infoPlate.attachChild(this._leftAvatar);
	infoPlate.attachChild(this._leftIcons);
	infoPlate.attachChild(this._leftInfo);
	infoPlate.attachChild(this._leftEquips);
	return infoPlate;
}

Board.prototype.trigger = function(config) {
	if(null === this._player) {
		this._player = config['player'];
		console.log(this._player);
		this._preparePlayerData();
	}

	if(null === this._location) {
		this._location = config['location'];
		this._container.settlePlate(this._location.brief);
		switch (this._type) {
			case 'NewPlayer':
				this._message.settlePlate(this._player.translate(this._location.welcome));
				break;
		}
		
	}

	if(null === this._world) {
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
		this._avatar.settlePlate(this._player);
		this._leftAvatar.settlePlate(this._player);
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

		this._leftIcons.settlePlate({
			'damage': this._player.calculateDamage('literal'),
			'armor': this._player.calculateArmor()
		});

		this._leftEquips.settlePlate(this._player.collectEquips());
	}
}

Board.prototype.assembly = function() {
	this._stage.append(this._container.render());
	this._left.append(this._info.render());
}