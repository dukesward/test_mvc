;var Battle = function(config) {
	//this._queue = new ActionQueue();
	this._timer = this._prepareTimer();
	this._players = this.preparePlayers(config['players']);
	this._troop = config['troop'];
	this._enemies = this.prepareEnemies(config['enemies']);
	this._ai = new AI_PVE({
		'enemies' : this._enemies
	});
}

Battle.prototype.translate = function(str) {
	return Common_Utils.translate.call(this, str);
};

Battle.prototype.collectPlayerAvatars = function() {
	var avatars = [];

	for(var i=0; i<this._players.length; i++) {
		avatars.push(this._players[i]._avatar);
	}
	return avatars;
}

Battle.prototype.collectPlayerInfo = function() {
	var avatars = [];

	for(var i=0; i<this._players.length; i++) {
		avatars.push(this._players[i]._info);
	}
	return avatars;
}

Battle.prototype.collectEnemyAvatars = function() {
	var avatars = [];

	for(var i=0; i<this._enemies.length; i++) {
		avatars.push(this._enemies[i]._avatar);
	}
	return avatars;
}

Battle.prototype.collectEnemyInfo = function() {
	var avatars = [];

	for(var i=0; i<this._enemies.length; i++) {
		avatars.push(this._enemies[i]._info);
	}
	return avatars;
}

Battle.prototype.preparePlayers = function(players) {
	var temp = [];
	
	for(var i=0; i<players.length; i++) {
		var player = new Player(players[i], 'player', this._timer);
		player.createAvatar();
		player.createInfo();
		player._avatar.attachHandler('click', player._info);
		temp.push(player);
	}
	return temp;
}

Battle.prototype.prepareEnemies = function(enemies) {
	var temp = [];
	
	for(var i=0; i<enemies.length; i++) {
		var enemy = new Player(enemies[i], 'enemy', this._timer);
		enemy.createAvatar();
		enemy.createInfo();
		enemy._avatar.attachHandler('click', enemy._info);
		temp.push(enemy);
	}
	return temp;
}

Battle.prototype._prepareBattle = function() {

}

Battle.prototype.startBattle = function() {
	if(!this._timer) {
		this._timer = this._prepareTimer();
	}
	this._timer.resumeTimer(100);
	this._startBattle();
}

Battle.prototype.debugBattle = function() {
	this._timer.stopTimer();
	console.log(this._timer.getTimeInSecond());
}

Battle.prototype.inBattle = function() {
	return !this._timer.isStopped();
}

Battle.prototype._prepareTimer = function() {
	var timeCounter = 0, 
		stopped = false,
		timer = null;

	return {
		getTimeInMilliSecond: function() {
			return timeCounter;
		},
		getTimeInSecond: function() {
			return timeCounter/1000;
		},
		stopTimer: function() {
			clearInterval(timer);
			stopped = true;
		},
		resumeTimer: function(itv) {
			console.log(itv);
			stopped = false;
			timer = setInterval(function() {
				timeCounter += itv;
			}, itv || 100);
		},
		isStopped: function() {
			return stopped === true;
		}
	}
}

Battle.prototype._startBattle = function() {
	while(!this._isBattleComplete() && this.inBattle()) {
		this._startAction(this._decideNextAction());
	}
}

Battle.prototype._isBattleComplete = function() {
	return this._allDead(this._players) || this._allDead(this._enemies);
}

Battle.prototype._allDead = function(players) {
	var allDead = true;
	for(var i=0; i<players.length; i++) {
		if(!players[i]._isDead()) {
			allDead = false;
		}
	}
	return allDead;
}

Battle.prototype._startAction = function(action) {
	if(action) action.takeAction();
}

Battle.prototype._decideNextAction = function() {
	return this._searchPlayerAction(this._players) || this._searchPlayerAction(this._enemies);
}

Battle.prototype._searchPlayerAction = function(players) {
	var action = null;
	for(var i=0; i<players.length; i++) {
		var player = players[i];
		action = player.searchPlayerAction() || this._ai.decidePlayerAction(player);
		//this.debugBattle();
	}
	return action;
}
