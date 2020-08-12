var mysql = require('mysql');

var con = mysql.createConnection({
  host: "127.0.0.1",
  user: "remote",
  password: "Dshr@2020",
  database: "dshr"
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
  var sql = "CREATE TABLE customers (name VARCHAR(255), address VARCHAR(255))";
  con.query(sql, function (err, result) {
    if (err) throw err;
    console.log("Table created");
  });
});

async function getAvailableJobs() {
    let query = `
    select  ds_job.id, 
    ds_job.hotel_id, 
    ds_hotel.name as hotel_name, 
    ds_job.is_active, 
    ds_job.start_date,
    ds_job.job_type_id,
    ds_job_type.name,
    ds_job.start_time,
    ds_job.end_time,
    DATE_FORMAT(start_date, '%d %b') as day_month,
    DATE_FORMAT(start_date, '%W') as weekyday
    from ds_job  
    INNER JOIN ds_hotel 
    on ds_hotel.id = ds_job.hotel_id
    INNER JOIN ds_job_type 
    on ds_job.job_type_id = ds_job_type.id
    where start_date > CURRENT_TIMESTAMP()
    ORDER BY start_time;
    `;
    return database.simpleExecute(query)
}

module.exports = {
    getAvailableJobs: getAvailableJobs,
};