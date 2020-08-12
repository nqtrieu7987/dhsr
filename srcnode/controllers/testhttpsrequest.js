var https = require('https');
var HttpsProxyAgent = require('https-proxy-agent');
var proxy = 'http://192.168.1.240:8001';
var agent = new HttpsProxyAgent(proxy);
var post_req = https.request({
	host: 'pbx.edupia.vn',
	port: '8777',
	path: '/api/misscall.php',
	method: 'POST',
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded'
	},
	agent: agent,
	timeout: 10000,
	followRedirect: true,
    maxRedirects: 10,
    body: {
        "begin": "2019-10-17 12:00:00",
        "end": "2019-10-17 15:00:00"
    }
}, function(res) {
	res.setEncoding('utf8');
	res.on('data', function(chunk) {
		console.log('Response: ' + chunk);
	});
});
post_req.write("name=john");
post_req.end();