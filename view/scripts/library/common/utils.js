var Common_Utils = {};

Common_Utils.isArray = function(arr) {
	return Object.prototype.toString.call(arr) === '[object Array]';
}