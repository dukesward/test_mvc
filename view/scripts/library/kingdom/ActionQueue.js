;var ActionQueue = function() {
	this._queue = [];
	this._timer = null;
}

ActionQueue.prototype.setTimer = function(timer) {
	this._timer = timer;
}
