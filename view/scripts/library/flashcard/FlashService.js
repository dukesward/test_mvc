var FlashCard = FlashCard || function() { this._service = new this.service(); };

FlashCard.prototype.service = function() {
	this._host = 'localhost';
	this._url = 'flashcard';
	this._cards = null;
}

var service = FlashCard.prototype.service;

service.prototype.get = function(callback, data) {
	var url = this._url + '/get';

	if(this._host !== 'localhost') {
		url = this._host += ('/' + url);
	}

	if(!data) {
		data = null;
	}
	
	$.get(url, data, function(data) {
		//console.log(data);
		this._cards = data;

		if(typeof callback === 'function') {
			callback(JSON.parse(this._cards));
		}

	}.bind(this));
}

service.prototype.update = function(callback, data) {
	var url = this._url + '/update';

	if(this._host !== 'localhost') {
		url = this._host += ('/' + url);
	}

	if(data) {
		$.ajax({
			type: 'POST',
			url: url, 
			data: data, 
			success: function(data) {
				console.log(data);

				if(typeof callback === 'function') {
					callback(JSON.parse(data));
				}

			}.bind(this)
		});
	}
}