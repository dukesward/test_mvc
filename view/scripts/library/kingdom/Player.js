;var Player = function(player, role, timer) {
	this._role = role || 'player';

	for(var prop in player) {
		this['_' + prop] = player[prop];
	}

	if(this._isPlayer()) {
		this._max_hp = this._props.hp_max;
	}
	if(!this._hp) this._hp = this._max_hp;
	this._queue = [];
	this._timer = timer;
	this._setBattleInitAttrs();
}

Player.prototype.translate = function(str) {
	return Common_Utils.translate.call(this, str);
};

Player.prototype._isPlayer = function() {
	return this._role === 'player';
}

Player.prototype._isDead = function() {
	return this._hp <= 0;
}

//===============================
//start: battle related functions
Player.prototype.searchPlayerAction = function() {

}

Player.prototype.createAction = function(ability) {
	var action = new Action(this, this._targets, ability);
	return action;
}

Player.prototype._setBattleInitAttrs = function() {
	this._cooldown = 0;
	this._targets = [];
	this._processNormalAttack();
	this._processPassiveAbilities();
}

Player.prototype._setTarget = function(target) {
	this._targets = [];
	if(Common_Utils.isArray(target)) {
		this._targets = target;
	}else {
		this._targets.push(target);
	}
}

Player.prototype._hasTarget = function() {
	return this._targets.length > 0;
}

Player.prototype._numOfTargets = function() {
	return this._targets.length;
}

Player.prototype._processNormalAttack = function() {
	var normal = {
		'type'        : 'basic',
		'sub_type'    : 'attack',
		'is_active'   : 'T',
		'threat_basic': 1,
		'threat_extra': 0,
		'attrs'       : 'physical',
		'bonus'       : '{ap}',
		'target'      : 'target',
		'num_target'  : 'single',
		'ability_name': '普通攻击'
	}

	if(!this._abilities) {
		this._abilities = [];
	}

	this._abilities.push(normal);
}

Player.prototype._processPassiveAbilities = function() {

}
//end: battle related functions
//=============================

//=================================
//start: ui board related functions
Player.prototype.createAvatar = function() {
	//console.log(this);
	this._avatar = new Plate('avatar', 'player');
	this._avatar.settlePlate(this);
}

Player.prototype.createInfo = function() {
	this._info = new Plate('container', 'info');
	this._leftAvatar = new Plate('avatar', 'player');
	this._leftInfo = new Plate('table', 'text'),
	this._leftIcons = new Plate('table', 'icon');
	if(this._isPlayer()) {
		this._leftEquips = new Plate('table', 'equips');
		this._leftItems = new Plate('list', 'items');
	}else {
		this._leftItems = new Plate('list', 'equips');
	}
	this._leftItemInfo = new Plate('container', 'item');

	if(this._isPlayer()) {
		this._info.attachChild([
			this._leftAvatar, this._leftIcons, this._leftInfo, this._leftEquips, this._leftItems, this._leftItemInfo
		]);
	}else {
		this._info.attachChild([
			this._leftAvatar, this._leftIcons, this._leftInfo, this._leftItems, this._leftItemInfo
		]);
	}
	this._settleInfoBoard();
}

Player.prototype._settleInfoBoard = function() {
	this._leftAvatar.settlePlate(this);
	if(this._isPlayer()) {
		this._leftInfo.settlePlate({
			'str': this._attrs.str,
			'agi': this._attrs.agi,
			'int': this._attrs['int'],
			'sta': this._attrs.sta,
			'spr': this._attrs.spr,
			'luc': this._attrs.luc,
			'ap': this._props.ap,
			'blk': this._props.blk,
			'crt': this._props.crt,
			'eva': this._props.eva,
			'hit': this._props.hit
		});
		this._leftEquips.settlePlate(this.collectEquips());
	}else {
		this._leftInfo.settlePlate({
			'blk': this._blk || 0,
			'crt': this._crt || 0,
			'eva': this._eva || 0,
			'hit': this._hit || 0
		})
	}
	this._leftIcons.settlePlate({
		'damage': this.calculateDamage('literal'),
		'armor': this.calculateArmor()
	});
	if(this._isPlayer()) {
		this._leftItems.settlePlate(this._items);
	}else {
		this._leftItems.settlePlate(this._loot);
	}
	this._leftItems._infoBoard = this._leftItemInfo;
}
//end: ui board related functions

//====================================
//start: player general util functions
Player.prototype.calculatePropFromEquips = function(prop) {
	var equips = this._equips, sum = 0;
	for(var e in equips) {
		if(equips[e]) {
			var added = Common_Utils.searchProp(equips[e], prop);
			sum += (added || 0);
		}
	}
	return sum;
}

Player.prototype.calculateDamage = function(form) {
	var ap = 0, damage = 0, result;
	if(this._isPlayer()) {
		ap = this._props.ap + this.calculatePropFromEquips('ap');
		damage = this.calculatePropFromEquips('dmg');
	}else {
		ap = this._max_dmg - this._min_dmg;
		damage = this._min_dmg;
	}

	switch(form) {
		case 'literal':
			result = damage + ' - ' + (damage + ap);
			break;
	}
	return result;
}

Player.prototype.calculateArmor = function() {
	return this.calculatePropFromEquips('armor');
}

Player.prototype.collectEquips = function() {
	var equips = {};
	for(var e in this._equips) {
		if(this._equips[e]) {
			var part = this._equips[e].part,
				iconName = this._equips[e].icon;
			equips[part] = iconName + '|' + this._equips[e].quality;
		}
	}
	//console.log(this._equips);
	return equips;
}

Player.prototype.hasAbilityType = function(type, targets) {
	var abilities = [], targets = targets || 'single';
	for(var i=0; i<this._abilities.length; i++) {
		var a = this._abilities[i];
		if(a.sub_type === type && a.num_target === targets) {
			if(!a._cooldown) abilities.push(a);
		}
	}
	return abilities;
}

Player.prototype.sortAbilityByThreat = function(target) {
	var targets = targets || 'single', 
		unsorted = [],
		t = 0;

	for(var i=0; i<this._abilities.length; i++) {
		var a = this._abilities[i];
		if(a.sub_type === 'attack' && a.num_target === targets && !a._cooldown) {
			unsorted.push({
				'a' : a,
				't' : a.threat_basic + a.threat_extra
			});
		}
	}
	return Common_Utils.sortObjectsByProperty(unsorted, 't');
}
//end: player general util functions
//==================================