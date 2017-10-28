const express = require('express');
const pug = require('pug');
const moduleManager = require('./module-manager');
const app = express();

app.set('view engine', 'pug');

app.get('/', function(req, res) {
	res.render('template/auth', {title: 'root', message: 'hello node'});
});

//app.listen(3000, function() {
	//console.log('Testing strategy hooked on port 3000');
	//moduleManager.hook(['application.core.adapter']);
//});

moduleManager.hook(['application.core.adapter']);