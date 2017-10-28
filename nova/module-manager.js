const utils = require('utils-general');
const root = require('module-root');

module.exports = root.extend({

	hook: function(modules) {
		if(utils.isString(modules)) {

		}else if(utils.isArray(modules)) {
			console.log('array');
			for(var m in modules) {
				try {
					console.log(modules[m]);
					var loaded = require(utils.parseModule(modules[m]));
				} catch(err) {
					console.log(err);
				}
			}
		}
	}
	
});