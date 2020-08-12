var mysql = require('mysql');

var con = mysql.createPool({
  connectionLimit : 100,
  host: "localhost",
  user: "root",
  password: "",
  database: "dshr"
});

con.getConnection(function(err, conn) {
  if(err) {
      console.log(err);
  } else {
      console.log("Success");
  }
  
});