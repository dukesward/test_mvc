;var Renderer = function() {
	this._type = null;
	//this._board = null;
}

Renderer.prototype.initNewBoard = function(data) {
	this._type = data['type'];

	var type = Common_Utils.capitalizeAllTokens(this._type, "_"),
		board = new Board(type);
	this['render' + type + 'Event'].call(this, data, board);
}

Renderer.prototype.renderNewPlayerEvent = function(data, board) {
	var player = new Player(data['player']),
		location = data['location'];

	board.trigger({
		'player'  : player,
		'location': location,
		'world'   : data['world']
	});
}

Renderer.prototype.renderNewEventEvent = function(data, board) {
	var player = new Player(data['player']),
		location = data['location'];

	board.trigger({
		'player'   : player,
		'location' : location,
		'world'    : data['world'],
		'event'    : data['event']   
	});
}