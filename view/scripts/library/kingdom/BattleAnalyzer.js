;var BattleAnalyzer = function(battle) {
	this._battle = battle;
	this._players = battle._players;
	this._troop = battle._troop;
	this._report = this.makeBattleReport();
}

BattleAnalyzer.prototype.makeBattleReport = function() {
	var report = {};
	for(var i=0; i<this._players.length; i++) {
		var player = this._players[i],
			name = player._name;

		report[name] = {
			troop: this._troop,
			player_type: player._class_type,
			level: player._level,
			damage: 0,
			threat: 0,
			healing: 0,
			reduction: 0
		};
	}
	return report;
}

BattleAnalyzer.prototype.updateReport = function(action) {
	var name = action._actor._name;
	if(this._report[name]) {
		var report = this._report[name],
			results = action._results;

		for(var j=0; j<results.length; j++) {
			var types = results[j].types;

			for(var i=0; i<types.length; i++) {
				if(types[i].type == 'heal') {
					if(types[i].damage) report['healing'] += types[i].damage;
				}else {
					if(types[i].damage) report['damage'] += types[i].damage;
				}
				if(types[i].reduction) report['reduction'] += Math.round(types[i].reduction);
			}
			if(results[j].threat) report['threat'] += results[j].threat;
		}
	}
}

BattleAnalyzer.prototype.finishReport = function() {
	var last = Math.ceil(this._battle._timer.getTimeInSecond());
	for(var r in this._report) {
		var report = this._report[r],
			dps = report['damage']/last;

		report['dps'] = Common_Utils.reformatBigNumber(dps, 2);
	}
	return this._report;
}