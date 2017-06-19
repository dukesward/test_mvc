;var Map = function() {
	this._map = {};
}

Map.prototype.feed = function(key, weight) {
	var w = parseInt(weight);
	if(w) {
		this._map[key] = w;
	}else {
		this._map[key] = 0;
	}
}

Map.prototype.feedArr = function(arr) {
	for(var i=0; i<arr.length; i++) {
		this.feed(i, arr[i]);
	}
}

Map.prototype.makeDecision = function() {
	var total = this._calculateTotal(),
		decision = null;

	if(total > 0) {
		var random = Common_Utils.generateRandomNumber(0, 1, total),
			decision = this._findDecision(random, 1);
	}
	return decision;
}

Map.prototype._calculateTotal = function() {
	var total = 0;
	for(var k in this._map) {
		total += this._map[k];
	}
	return total;
}

Map.prototype._findDecision = function(ticket) {
	var start = 0,
		decision = null;

	for(key in this._map) {
		start += this._map[key];
		if(start >= ticket) {
			decision = key;
			return decision;
		}
	}
	return decision;
}