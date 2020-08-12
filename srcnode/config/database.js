var mysql = require('mysql');


var pool = mysql.createPool({
  connectionLimit : 100,
  host: "databasemy",
  user: "root",
  password: "Dshr2020",
  database: "dshr"
});


pool.getConnection(function(err, conn) {
  if(err) {
      console.log(err);
  } else {
      console.log("Success");
  }
  
});

async function close() {
  await pool.end();
}
// *** previous code above this line ***

function simpleExecute(sql) {
  return new Promise(async (resolve, reject) => {
    pool.getConnection(function (err, connection) {      
      if (err) reject(err); // not connected!
      // Use the connection
      connection.query(sql, function (error, results, fields) {
        resolve(results);
        // When done with the connection, release it.
        connection.release();
        // Handle error after the release.
        if (error) reject(error);
        // Don't use the connection here, it has been returned to the pool.
      });
    });
  });
}

module.exports = {
  close: close,
  simpleExecute: simpleExecute,
};
