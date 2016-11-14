$(document).ready(function(event) {

	window.DataUtils = (function() {

		var isNumber = function(num) {
			return num/1 !== NaN;
		}

		var generateRandomNumber = function(decimal, min, max) {
			var rand = Math.random(),
				factor = Math.pow(10, decimal);

			if(isNumber(min) && isNumber(max)) {
				rand = min + (max - min) * rand;
			}
			return Math.round(rand*factor)/factor;

		}

		var generateRandomData = function(size, decimal, min, max) {
			var data = [];
			
			if(isNumber(size)) {
				for(var i=0; i<size; i++) {
					var d = generateRandomNumber(decimal, min, max);
					if(isNumber(d)) data.push(d);
				}
			}
			return data;
		}

		var generateSequencedData = function(min, max, interval) {
			var data = [], total;

			if(isNumber(min) && isNumber(max) && isNumber(interval)) {
				total = Math.round((max - min)/interval);
				for(var i=0; i<total+1; i++) {
					var next = min + i*interval;

					if(next <= max) {
						data.push(next);
					}
				}
			}
			return data;
		}

		var generateMap = function(mapX, mapY) {
			var map = {};

			if(isArray(mapX) && isArray(mapY)) {
				for(var i=0; i<mapX.length; i++) {
					if(i<mapY.length) {
						map[mapX[i]] = mapY[i];
					}
				}
			}
			return map;
		}

		return {
			random: generateRandomData,
			sequence: generateSequencedData,
			map: generateMap
		}

	})(jQuery);

});