;var Board = function(type) {
	this._type = type;
	this.renderBoard();
};

Board.prototype.renderBoard = function() {
	this._stage = $("#kingdom_middle_field");
	this._left = $("#kingdom_left_field");
	this._container = new Plate('container', 'scene');
	//this._info = new Plate('container', 'info');
	this['create' + this._type + 'Board'].call(this);
}

Board.prototype.createPublicBoard = function() {
	this._annc = null;
	this._world = null;
	this._location = null;
	this._tag = new Plate('tag', 'announcement');
	this._calendar = new Plate('calendar');
	this._anncIcon = new Plate('icon', 'event');
	this._anncMsg = new Plate('message', 'annc');

	this._container.attachChild([
		this._tag, 
		this._calendar, 
		this._anncIcon,
		this._anncMsg
		//this._info
	]);
}

Board.prototype.createNewPlayerBoard = function() {
	this._player = null;
	this._location = null;
	this._world = null;
	this._tag = new Plate('tag', 'recruit');
	this._calendar = new Plate('calendar');
	this._message = new Plate('message', 'recruit');
	this._avatar = new Plate('avatar', 'player');

	this._info = new Plate('container', 'info');
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

	this._info = new Plate('container', 'info');
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

Board.prototype.createBattleBoard = function() {
	this._battle = null;
	this._evt = null;
	this._world = null;
	this._location = null;
	this._tag = new Plate('tag', 'battle');
	this._button = new Plate('button', 'play');
	this._calendar = new Plate('calendar');
	this._playerField = new Plate('container', 'players');
	this._enemyField = new Plate('container', 'enemies');
	this._message = new Plate('message', 'event');

	this._container.attachChild([
		this._tag, 
		this._button,
		this._calendar,
		this._playerField,
		this._message,
		//this._info
		this._enemyField
	]);
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

	if(null === this._annc && config['public']) {
		this._annc = config['public'];
		//console.log(this._annc);
		this._anncMsg.settlePlate(this._annc.message);
		this._anncIcon.settlePlate({
			'main' : this._annc.icon,
			'style': 'big'
		});
	}

	if(null === this._battle && config['battle']) {
		var self = this;
		this._battle = config['battle'];
		this._event = config['event'];
		this._message.settlePlate(this._battle.translate(this._event.description));

		this._playerAvatars = this._battle.collectPlayerAvatars();
		for(var i=0; i<this._playerAvatars.length; i++) {
			this._playerField.attachChild(this._playerAvatars[i]);
		}

		this._playerInfos = this._battle.collectPlayerInfo();
		for(var i=0; i<this._playerInfos.length; i++) {
			this._playerInfos[i].attachParent(this._left);
			this._left.append(this._playerInfos[i].render());
		}

		this._enemyAvatars = this._battle.collectEnemyAvatars();
		for(var i=0; i<this._enemyAvatars.length; i++) {
			this._enemyField.attachChild(this._enemyAvatars[i]);
		}

		this._enemyInfos = this._battle.collectEnemyInfo();
		for(var i=0; i<this._enemyInfos.length; i++) {
			this._enemyInfos[i].attachParent(this._left);
			this._left.append(this._enemyInfos[i].render());
		}

		this._button.settlePlate({
			play: function() {
				console.log('play');
				self._battle.startBattle();
			},
			stop: function() {
				console.log('stop');
				self._battle.debugBattle();
			}
		});
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
	if(this._info) this._left.append(this._info.render());
}