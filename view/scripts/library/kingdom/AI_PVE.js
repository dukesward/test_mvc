;var AI_PVE = function(config) {
	this._enemies = config['enemies'];
}

AI_PVE.prototype.decidePlayerAction = function(player) {
	var action;
	if(player._isPlayer()) {
		if(this._enemies) {
			switch (player._class_type) {
				case 'tank':
					action = this._decideTankAction(player);
					break;
				case 'dps':
					action = this._decideDpsAction(player);
					break;
				case 'healer':
					action = this._decideHealerAction(player);
					break;
			}
		}
	}else {
		action = this._decideEnemyAction(player);
	}
	return action;
}

AI_PVE.prototype._decideTankAction = function(player) {
	var enemy = this._findTankableEnemy(), action, ability;
	if(enemy) {
		player._setTarget(enemy);
		var abilities = player.hasAbilityType('tank');
		if(abilities.length > 0) {
			ability = abilities[0];
		}else {
			abilities = player.sortAbilityByThreat();
			if(abilities.length > 0) {
				ability = abilities[0]['a'];
			}
		}
	}
	return player.createAction(ability);
}

AI_PVE.prototype._decideDpsAction = function(player) {
	
}

AI_PVE.prototype._decideHealerAction = function(player) {
	
}

//start: tank related AIs
AI_PVE.prototype._findTankableEnemy = function() {
	var ordered, tankable;
	ordered = this._orderEnemyByType(['king','knight','rook','bishop','pawn']);
	tankable = this._findEnemyWithoutTarget(ordered);
	//console.log(tankable);
	return tankable;
}
//end: tank related AIs

//start: enemy related AIs
AI_PVE.prototype._decideEnemyAction = function(enemy) {
	
}
//end: enemy related AIs

AI_PVE.prototype._orderEnemyByType = function(order) {
	var ordered = [], enemies = this._enemies;
	for(var i=0; i<order.length; i++) {
		for(var j=0; j<enemies.length; j++) {
			if(enemies[j]['type'] === order[i]) ordered.push(enemies[j]);
		}
	}
	return ordered;
}

AI_PVE.prototype._findEnemyType = function(t) {
	var enemies = [];
	for(var i=0; i<this._enemies.length; i++) {
		if(this._enemies[i].type === t) {
			enemies.push(this._enemies[i]);
		}
	}
	return enemies;
}

AI_PVE.prototype._findEnemyWithoutTarget = function(enemies) {
	var enemies = enemies || this._enemies;
	for(var i=0; i<this._enemies.length; i++) {
		if(!this._enemies[i]._hasTarget()) {
			return this._enemies[i];
		}
	}
	return null;
}