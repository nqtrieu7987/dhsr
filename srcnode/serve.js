#!/usr/bin/env node
'use strict';

var utils = require("./Utils.js");
var https = require('https');
var http = require('http');
var path = require('path');
var port = process.argv[2] |8443;
var insecurePort = process.argv[3] || 4080;
var fs = require('fs');
var checkip = require('check-ip-address');
var server;
var insecureServer;
var options;
var certsPath = path.join(__dirname, 'certs', 'server');
var caCertsPath = path.join(__dirname, 'certs', 'ca');
var querystring = require('querystring');
//
// SSL Certificates
//
options = {
 // key: fs.readFileSync(path.join(certsPath, 'my-server.key.pem'))
//key: fs.readFileSync('./educa.vn.key')
  // This certificate should be a bundle containing your server certificate and any intermediates
  // cat certs/cert.pem certs/chain.pem > certs/server-bundle.pem
//, cert: fs.readFileSync('./educa.vn.crt')
  // ca only needs to be specified for peer-certificates
//, ca: [ fs.readFileSync(path.join(caCertsPath, 'my-root-ca.crt.pem')) ]
 requestCert: false
, rejectUnauthorized: true
};


//
// Serve an Express App securely with HTTPS
//
//server = https.createServer(options);
//checkip.getExternalIp().then(function (ip) {
//  var host = ip || 'local.helloworld3000.com';

//  function listen(app) {
//    server.on('request', app);
//    server.listen(port, function () {
//      port = server.address().port;
      // utils.writeLog('Listening on https://127.0.0.1:' + port);
//      utils.writeLog('Listening on https://127.0.0.1:' + port);
//      if (ip) {
//        utils.writeLog('Listening on https://' + ip + ':' + port);
//      }
//    });
//  }

//  var publicDir = path.join(__dirname, 'public');
//  var app = require('./app').create(server, host, port, publicDir);
//  listen(app);
//});


//
// Redirect HTTP ot HTTPS
//
// This simply redirects from the current insecure location to the encrypted location
//
server= http.createServer();
  var host = '127.0.0.1';
  function listen(app) {
    server.on('request', app);
    server.listen(port, function () {
      port = server.address().port;
      // utils.writeLog('Listening on https://127.0.0.1:' + port);
      utils.writeLog('Listening on https://127.0.0.1:' + port);
     
    });
  }

  var publicDir = path.join(__dirname, 'public');
  var app = require('./app').create(server, host, port, publicDir);
  listen(app);
