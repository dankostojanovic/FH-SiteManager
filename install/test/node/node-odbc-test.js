var perv = require('odbc')();
var config = require('../../src/config');

var connectionStringOptions = config.odbc;

var cn = Object.keys(connectionStringOptions).map( function(key){
	return key+'='+connectionStringOptions[key];
}).join(';');

console.log('\n'+cn+'\n');

// There is a segfault on open, so this doesn't work
// This is the only odbc library on npm, so sorry
perv.open(cn, function(err){
	console.log('Opened connection.');
	if(err){
		return console.log(err);
	}

	var query = 'select * from "OHCND01".po_header where po_no=203163;';

	console.log(query);

	perv.query(query, [], function(err, data){
		if(err){
			return console.log(err);
		}
		console.log(data);

		perv.close(function(){
			console.log('done!');
		});
		
	});
});
