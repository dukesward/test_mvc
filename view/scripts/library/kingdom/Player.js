;var Player = function(player) {
	for(var prop in player) {
		this['_' + prop] = player[prop];
	}
	this._max_hp = this._hp;
}

Player.prototype.translate = function(str) {
	var pattern = /\{([a-zA-Z_]+?)\}/g,
		self = this,
		replaced = '';

	replaced += str.replace(pattern, function(r, c) {
		//console.log(self);
		if(c && self['_' + c]) {
			var modified = Common_Utils.capitalizeAllTokens(self['_' + c]);
			return "<span class='" + c + "'>" + modified + "</span>";
		}
	})
	return replaced;
}

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
	var ap = this._props.ap + this.calculatePropFromEquips('ap'),
		damage = this.calculatePropFromEquips('dmg'),
		result;

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
			equips[part] = iconName;
		}
	}
	return equips;
}