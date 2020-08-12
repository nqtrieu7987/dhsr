'use strict';

const http = require('http');

http.createServer((req, res) => {
  res.writeHead(200);
  res.end('33333333333333333333333333333333333333333333333333333333333');
}).listen(process.env.PORT || 3000, () => {
  console.log('App listening on port 666666666666666666666666666666');
});
