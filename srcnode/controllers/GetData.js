var utils = require("../Utils.js");
const database = require('../config/database.js');

async function getListJobOnGoing(req, res) {
    var hotel_id = req.headers.hotel_id;
    if (req.headers.hotel_id == undefined || utils.isEmptyObject(req.headers.hotel_id)) {
        var sql =
        `SELECT ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday 
        FROM ds_job INNER JOIN ds_hotel ON ds_hotel.id = ds_job.hotel_id 
        INNER JOIN ds_job_type ON ds_job.job_type_id = ds_job_type.id 
        WHERE ds_hotel.is_active = 1 AND ds_job.is_active = 1 AND current_slot < slot AND start_date > CURRENT_TIMESTAMP() ORDER BY start_date
        `;
    }else{
        var sql = `SELECT ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday 
        FROM ds_job INNER JOIN ds_hotel ON ds_hotel.id = ds_job.hotel_id 
        INNER JOIN ds_job_type ON ds_job.job_type_id = ds_job_type.id 
        WHERE ds_job.hotel_id = `+hotel_id+` AND ds_hotel.is_active = 1 AND ds_job.is_active = 1 AND current_slot < slot AND start_date > CURRENT_TIMESTAMP() ORDER BY start_date`;
    }
    utils.writeLog("Ongoing="+ sql);
    return database.simpleExecute(sql);
}

async function getMyJob(req, res) {
    var user_id = req.headers.user_id;
    var status = req.headers.status;
    if (req.headers.user_id == undefined || utils.isEmptyObject(req.headers.user_id)) {
      res.json({ message: 'UserId not null!', resultCode: 1 });
      return "";
    }
    if (status == undefined || utils.isEmptyObject(status)) {
      status = null;
    }

    if (status == null) {
        var sql = "SELECT ds_all_jobs.id AS all_job_id, ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active,ds_all_jobs.status AS STATUS, ds_job.start_date, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday FROM ds_all_jobs LEFT JOIN ds_job ON ds_all_jobs.job_id = ds_job.id LEFT JOIN ds_hotel ON ds_job.hotel_id = ds_hotel.id LEFT JOIN ds_job_type ON ds_all_jobs.job_id = ds_job_type.id WHERE ds_all_jobs.user_id="+user_id+" ORDER BY ds_job.start_date DESC";
    }else{
        var sql = "SELECT ds_all_jobs.id AS all_job_id, ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active,ds_all_jobs.status AS STATUS, ds_job.start_date, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday FROM ds_all_jobs LEFT JOIN ds_job ON ds_all_jobs.job_id = ds_job.id LEFT JOIN ds_hotel ON ds_job.hotel_id = ds_hotel.id LEFT JOIN ds_job_type ON ds_all_jobs.job_id = ds_job_type.id WHERE ds_all_jobs.user_id="+user_id+" AND ds_all_jobs.status="+status+" ORDER BY ds_job.start_date DESC";
    }
    utils.writeLog("MyJob="+ sql);
    return database.simpleExecute(sql);
}

module.exports = {
    getListJobOnGoing: getListJobOnGoing,
    getMyJob: getMyJob,
};