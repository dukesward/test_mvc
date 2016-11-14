var isString = function(obj) {
	return typeof obj === 'string';
}

var isArray = function(obj) {
	return (typeof obj === 'object' && Object.prototype.toString.call(obj) === '[object Array]');
}

var isNumber = function(num) {
	return num/1 !== NaN;
}

var capitalizeAllTokens = function(str, delimitter, camel, connector) {
	var result = null, connector = connector || '';
	if(typeof str === 'string') {
		if(delimitter) {
			//if delimitter is defined, replace every occurance with space
			str = str.replace(new RegExp(delimitter, 'g'), " ");
		}
		//this replace each token with a capitalized one
		result = str.replace(/\w\S*/g, function(s) {
			return s.charAt(0).toUpperCase() + s.substr(1);
		})
	}
	//the camel indicator simply uncapitalizes the first letter of the str
	if(camel) {
		result = result.charAt(0).toLowerCase() + result.substr(1);
	}
	//replace blanks with the connector
	result = result.split(' ').join(connector);
	return result;
}

var searchProp = function(obj, prop) {
	if(!obj || null === prop || typeof prop === 'undefined') {
		return null;
	}

	var currentProp = obj,
        propForwards = prop.split('*'),
        propLayers = propForwards.length;

    if(propLayers > 1) {
        for(var i=0; i<propLayers; i++) {
        	if(!propForwards[i]) {
                continue;
            }else {
            	var index = propForwards[i].charAt(0);
	            //this is for wild searching
	        	if(index === '?') {
	        		var hint = propForwards[i].substr(1).split('->'), found = false;
	        		if(hint.length > 0 || !isArray(currentProp)) {
	        			var hints = hint[0].split('='), key = 'id', value = hints[0];

	        			if(hints.length > 1) {
	        				key = hints[0];
	        				value = hints[1];
	        			}
	        			//search through the array for obj with key matching the value
	        			for(var i=0; i<currentProp.length; i++) {
	        				if(searchProp(currentProp[i], key) == value) {
	        					hint.shift();
	        					found = true;
	        					currentProp = searchProp(currentProp[i], hint.join('->'));
	        				}
	        			}
	        			if(!found) {
	        				break;
	        				currentProp = null;
	        			}
	        		}else {
	        			currentProp = null;
	        		}
	        	}else if(parseInt(index) || parseInt(index) === 0) {
	                currentProp = searchProp(currentProp[index], ' ' + propForwards[i].substr('1'));
	            }else {
	                currentProp = searchProp(currentProp, propForwards[i]);
	            }
            }
        }
    }else {
        propForwards = propForwards[0].split('->');
        propLayers = propForwards.length;

        for(var j=0; j<propLayers; j++) {
            if(propForwards[j] === ' ') {
                continue;
            }

            if(currentProp[propForwards[j]] != undefined) {
                currentProp = currentProp[propForwards[j]];
            }else {
                currentProp = null;
                break;
            }
        }
    }

    return currentProp;
};

var hexToRgb = function(hex) {
	var rgb = [];
	if(hex.length === 3) {
		hex = Array.prototype.join.call(hex.split('').map(function(c) {
			return c + c;
		}), '');
	}

	for(var i=0; i<hex.length; i++) {
		if(parseInt(hex[i], 16)) {
			if(i%2 === 1) {
				rgb.push(parseInt(hex[i-1] + hex[i], 16));
			}
		}else {
			rgb = [];
			break;
		}
	}
	return rgb.join(',');
}

var findMin = function(arr) {
	var min = null;

	if(arr.length > 0 && isNumber(arr[0])) {
		min = arr[0];
		for(var i = 0; i<arr.length; i++) {
			if(isNumber(arr[i]) && arr[i] < min) {
				min = arr[i];
			}
		}
	}
	return min;
}

var findFloor = function(num, interval) {
	var interval = interval || 1;
	return Math.floor(num/interval) * interval;
}

var findMax = function(arr) {
	var max = null;

	if(arr.length > 0 && isNumber(arr[0])) {
		max = arr[0];
		for(var i = 0; i<arr.length; i++) {
			if(isNumber(arr[i]) && arr[i] > max) {
				max = arr[i];
			}
		}
	}
	return max;
}

var findRoof = function(num, interval) {
	var interval = interval || 1;
	return Math.ceil(num/interval) * interval;
}

var extractAxisFromMap = function(map, type) {
	var axis = [];

	for(var m in map) {
		if(type === 'x') {
			axis.push(m);
		}else if(type === 'y') {
			axis.push(map[m]);
		}
	}
	return axis;
}

$(document).ready(function(event) {

	(function($, doc, win) {

		win.ChartUtils = (function() {
			//currently the api only supports init by id
			var init = function(config) {
				var id = searchProp(config, 'id');

				if(isString(id)) {
					var container = doc.getElementById(id),
						canvas;
					
					if(container) {
						canvas = new CanvasCustom(container);
						canvas.configureCanvas(config);
					}else {
						console.warn('must provide a valid document object id');
					}
				}

				return {
					drawDot: canvas.drawDot.bind(canvas),
					drawLineChart: canvas.drawLineChart.bind(canvas)
				};
			}

			var CanvasCustom = function(container) {
				this._container = container;
				this._canvas = this._initCanvas();
			}

			CanvasCustom.prototype._initCanvas = function() {
				if(!this._canvas) {
					var canvas = document.createElement("canvas"),
						id = this._container.getAttribute('id') + '_canvas',
						ctx;

					canvas.setAttribute('id', id);
					canvas.setAttribute('class', 'canvas_custom');

					if(canvas.getContext) {
						ctx = canvas.getContext('2d');
						this._ctx = ctx;
					}

					this._container.appendChild(canvas);

					return {
						'width': canvas.width,
						'height': canvas.height,
						setWidth: function(width) {
							this.width = width;
							canvas.width = width;
						},
						setHeight: function(height) {
							this.height = height;
							canvas.height = height;
						},
						setBackgroundHex: function(hex) {
							var rgb = hexToRgb(hex);

							if(rgb) {
								ctx.fillStyle = "rgb(" + rgb + ")";
								ctx.fillRect(0, 0, canvas.width, canvas.height);
								this.backgroundColor = rgb;
							}
						},
						setAxisConfig: function(hex, x, y) {
							var rgb = hexToRgb(hex),
								x = x || 30,
								y = y || 50;

							this.yTop = x;
							this.xTop = y;

							if(rgb) {
								ctx.fillStyle = "rgb(" + rgb + ")";
								ctx.fillRect(0, 0, canvas.width, x);
								ctx.fillRect(canvas.width-y, 0, y, canvas.height);
								this.axisColor = rgb;

								ctx.fillStyle = "rgb(16,16,16)";
								ctx.fillRect(0, x, canvas.width-y, 1);
								ctx.fillRect(canvas.width-y, x, 1, canvas.height-x);
							}
						}
					}
				}else {
					return this._canvas;
				}
			}

			CanvasCustom.prototype.configureCanvas = function(config) {
				for(var c in config) {
					var funcName = 'set' + capitalizeAllTokens(c, '_'),
						func = this._canvas[funcName];

					if(func) {
						if(isString(config[c])) {
							func.apply(this, config[c].split(','));
						}else {
							func.call(this, config[c]);
						}
					}else {
						this._canvas[c] = config[c];
					}
				}
			}

			CanvasCustom.prototype.findDot = function(x, y, intx, inty) {
				var dotx = (x - this.rangeX[0])*intx,
					doty = (y - this.rangeY[0])*inty;
				return [Math.floor(dotx), this.height - Math.floor(doty)];
			}

			CanvasCustom.prototype.drawDot = function(dot, size, color) {
				var start = [0, this.yTop],
					dot = [start[0] + dot[0], start[1] + dot[1]],
					ctx = this._ctx;

				ctx.fillStyle = color;
				ctx.fillRect(dot[0], dot[1], size[0], size[1]);

			}

			CanvasCustom.prototype.drawText = function(dot, text, color) {
				var ctx = this._ctx;

				ctx.font = "14px Raleway";
				ctx.fillStyle = color || 'rgb(0,0,0)';
				ctx.fillText(text, dot[0], dot[1] + this.yTop);
			}

			CanvasCustom.prototype.drawLineChart = function(map) {
				this.drawXAxisGrids(extractAxisFromMap(map, 'x'));
				this.drawYAxisGrids(extractAxisFromMap(map, 'y'));
				this.drawLineSections(map);
			}

			CanvasCustom.prototype.drawXAxisGrids = function(mapX) {
				var interval = this.intervalX || 1,
					min = this.startDot || findFloor(findMin(mapX), interval),
					max = this.endDot || findRoof(findMax(mapX), interval),
					grids = Math.floor((max - min)/interval) + 1,
					intervalPx = Math.floor((this.width - this.xTop)/(grids - 1)),
					color = 'rgb(16,16,16)';

				this.rangeX = [min, max];
				this.intervalPxX = intervalPx;
					
				//console.log(mapX);
				for(var i=0; i<grids; i++) {
					if(i === 0) {
						this.drawDot([0, -5], [1, 5], color);
						this.drawText([0, -10], min, 'rgb(160, 160, 160)');
					}else {
						var center = [i*intervalPx, -5];
						this.drawDot([center[0] - 1, center[1]], [2, 5], color);
						this.drawText([i*intervalPx, -10], min + i*interval, 'rgb(160, 160, 160)');
					}
				}
			}

			CanvasCustom.prototype.drawYAxisGrids = function(mapY) {
				var interval = this.intervalY || 1,
					min = this.startDot || findFloor(findMin(mapY), interval),
					max = this.endDot || findRoof(findMax(mapY), interval),
					grids = Math.floor((max - min)/interval) + 1,
					intervalPx = Math.floor((this.height - this.yTop)/(grids - 1)),
					color = 'rgb(16,16,16)';

				this.rangeY = [min, max];
				this.intervalPxY = intervalPx;
					
				for(var i=0; i<grids; i++) {
					if(i === 0) {
						this.drawDot([this.width - this.xTop, this.height - this.yTop], [5, 2], color);
						this.drawText([this.width - this.xTop + 10, this.height - this.yTop], min, 'rgb(160, 160, 160)');
					}else {
						var center = [this.width - this.xTop, this.height - i*intervalPx - this.yTop];
						this.drawDot([center[0], center[1] - 1], [5, 2], color);
						this.drawText([this.width - this.xTop + 10, this.height - i*intervalPx - this.yTop], min + i*interval, 'rgb(160, 160, 160)');
					}
				}
			}

			CanvasCustom.prototype.drawLineSections = function(map) {
				var ctx = this._ctx,
					intX = this.intervalX || 1,
					intY = this.intervalY || 1,
					started = false;

				ctx.beginPath();
				for(var m in map) {
					var dot = this.findDot(m, map[m], this.intervalPxX, this.intervalPxY);
					//console.log(dot);
					if(started) {
						ctx.lineTo(dot[0], dot[1]);
					}else {
						ctx.moveTo(dot[0], dot[1]);
						started = true;
					}
				}
				//ctx.closePath();
				ctx.stroke();
			}

			return {
				'init': init
			}
		})();

	})(jQuery, document, window);
});


