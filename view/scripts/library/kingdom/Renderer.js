;var Renderer = function() {
	this._type = null;
	//this._board = null;
}

Renderer.prototype.initNewBoard = function(data) {
	this._type = data['type'];

	var type = Common_Utils.capitalizeAllTokens(this._type, "_"),
		board = new Board(type);

	this.renderPublicBoard(data);
	this['render' + type + 'Event'].call(this, data, board);
}

Renderer.prototype.renderPublicBoard = function(data, board) {
	var location = data['location'];

	if(data['public']) {
		for(var i=0; i<data['public'].length; i++) {
			var d = data['public'][i]
				pub = new Board('Public');

			pub.trigger({
				'public'  : d,
				'location': location,
				'world'   : data['world'] 
			});
			//pubBoards.push(pub);
		}
	}
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

Renderer.prototype.renderBattleEvent = function(data, board) {
	var battle = new Battle(data['battle']),
		location = data['location'];

	board.trigger({
		'location' : location,
		'world'    : data['world'],
		'battle'    : battle,
		'event'    : data['event']
	});
}