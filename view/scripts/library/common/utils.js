;var Common_Utils = {};

Common_Utils.isArray = function(arr) {
	return Object.prototype.toString.call(arr) === '[object Array]';
}

Common_Utils.isString = function(obj) {
	return typeof obj === 'string';
}

Common_Utils.isArray = function(obj) {
	return (typeof obj === 'object' && Object.prototype.toString.call(obj) === '[object Array]');
}

Common_Utils.isNumber = function(num) {
	return num/1 !== NaN;
}

Common_Utils.createRandomNumber = function(min, max, decimals) {
	return Math.round(((max - min)*Math.random() + min)*Math.pow(10, decimals))/Math.pow(10, decimals);
}

Common_Utils.capitalizeAllTokens = function(str, delimitter, camel, connector) {
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
		//the camel indicator simply uncapitalizes the first letter of the str
		if(camel) {
			result = result.charAt(0).toLowerCase() + result.substr(1);
		}
		//replace blanks with the connector
		result = result.split(' ').join(connector);
	}
	if(result == null) result = str;
	return result;
}

Common_Utils.mapQualityWithColor = function(q) {
	var map = ['#bbb','#fff'], q = parseInt(q);
	return (q+1) ? map[q] : '#fff';
}

Common_Utils.reformatBigNumber = function(num, fixed, reformat) {
	var reformatted = num, fixed = fixed || 2;

	if(reformat !== false) reformat = true;

	if(num >= Math.pow(10, 9) && reformat) {
		var temp = num/Math.pow(10, 9);
		reformatted = Math.round(temp*10)/10;
		reformatted = reformatted.toFixed(1) + 'B';
	}else if(num >= Math.pow(10, 7) && reformat) {
		var temp = num/Math.pow(10, 6);
		reformatted = Math.round(temp*10)/10;
		reformatted = reformatted.toFixed(1) + 'M';
	}else {
		reformatted = Math.round(num*100)/100;
		reformatted = reformatted.toFixed(fixed);
	}
	return reformatted;
}

Common_Utils.mergeSort = function(array) {
	if(array.length <= 1) {
        return array;
    }

	var segment = array,
		length = segment.length,
		mid = Math.round(length/2);

	function merge(left, right) {
		var temp = [], l = left, r = right;

		while(l.length && r.length) {
            if(l.length > 1) var test = 1;
            if(l[0] < r[0]) {
                temp.push(l[0]);
                l.shift();
            }else {
                temp.push(r[0]);
                r.shift();
            }
        }
		temp = l.length ? temp.concat(l) : temp.concat(r);
		return temp;
	}

	return merge(this.mergeSort(segment.slice(0, mid)), this.mergeSort(segment.slice(mid, length)));
};

//must pass an array with obj type elements
Common_Utils.sortObjectsByProperty = function(objs, prop, order) {
	var temp = [],
		sortedTemp,
		sorted = [];

	for(var obj in objs) {
		//search for prop with name matching the arg prop
        temp.push(this.searchProp(objs[obj], prop));
    }

    sortedTemp = this.mergeSort(temp);

    while(sortedTemp.length && sortedTemp[0] != null) {
    	for(var obj in objs) {
    		if(sortedTemp[0] !== null && this.searchProp(objs[obj], prop) == sortedTemp[0]) {
	    		sorted.push(objs[obj]);
	    		sortedTemp.shift();
	    	}
    	}
    }

    if(sortedTemp.length) sorted.concat(sortedTemp);
    //if need descending order, pass 1 to the function
    if(order && order === 1) sorted.reverse();
	return sorted;
};

Common_Utils.searchProp = function(obj, prop) {
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

Common_Utils.query = function(data, key, val) {
	var result = [];
	for(var i in data) {
		var d = data[i];
		if(this.searchProp(d, key) === val) result.push(d);
	}
	return result;
}

Common_Utils.hexToRgb = function(hex) {
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

Common_Utils.findMin = function(arr) {
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

Common_Utils.findFloor = function(num, interval) {
	var interval = interval || 1;
	return Math.floor(num/interval) * interval;
}

Common_Utils.findMax = function(arr) {
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

Common_Utils.findRoof = function(num, interval) {
	var interval = interval || 1;
	return Math.ceil(num/interval) * interval;
}

Common_Utils.extractAxisFromMap = function(map, type) {
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

Common_Utils.processTimeFormat = function(time) {
	var tokens = time.split('|'),
		refined = [];
	for(var i=0; i<tokens.length; i++) {
		var t = tokens[i].toString();
		if(t.length === 1) t = 0 + t;
		refined.push(t);
	}
	return refined.join('|');
}

Common_Utils.processPrice = function(price) {
	var gold = Math.round(price/10000),
		silver = Math.round((price-gold*10000)/100),
		copper = Math.round(price-gold*10000-silver*100);

	return {
		'gold': gold, 
		'silver': silver, 
		'copper': copper
	};
}

Common_Utils.processEquipLiterals = function(data) {
	var literals = {},
		c = this.capitalizeAllTokens.bind(this);

	switch(data['sub_type']) {
		case 'weapon':
			//part
			literals['part'] = c(data['part']) + '-Hand';
			literals['type'] = c(data['type']);
			literals['damage'] = data['damage'] + ' Damage';
			literals['speed'] = this.reformatBigNumber(data['speed'], 2) + ' Speed';
			break;
		case 'armor':
			literals['part'] = c(data['part']);
			literals['type'] = c(data['type']);
			literals['armor'] = data['armor_i'] + ' Armor';
			break;
	}
	return literals;
}

Common_Utils.translate = function(str) {
	var pattern = /\{([a-zA-Z_]+?)\}/g,
		self = this,
		replaced = '';

	replaced += str.replace(pattern, function(r, c) {
		//console.log(self);
		if(c && self['_' + c]) {
			var modified = Common_Utils.capitalizeAllTokens(self['_' + c]);
			return "<span class='" + c + "'>" + modified + "</span>";
		}else if(c === 'evt') {

		}
	})
	return replaced;
}