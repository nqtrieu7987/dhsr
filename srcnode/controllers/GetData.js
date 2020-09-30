var utils = require("../Utils.js");
const database = require('../config/database.js');
var fs = require('fs');
var jwt = require('jsonwebtoken');
const { stringify } = require("querystring");
var cert = fs.readFileSync('./educa.vn.key');  // get private key

async function getListJobOnGoing(req, res) {
    var hotel_id = req.headers.hotel_id;
    if (req.headers.hotel_id == undefined || utils.isEmptyObject(req.headers.hotel_id)) {
        var sql =
            `SELECT ds_job.id, ds_job.view_type, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday
        FROM ds_job INNER JOIN ds_hotel ON ds_hotel.id = ds_job.hotel_id 
        INNER JOIN ds_job_type ON ds_job.job_type_id = ds_job_type.id 
        WHERE ds_hotel.is_active = 1 AND ds_job.is_active = 1 AND current_slot < slot AND start_date >= CURDATE() ORDER BY start_date
        `;
    } else {
        var sql = `SELECT ds_job.id, ds_job.view_type, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday
        FROM ds_job INNER JOIN ds_hotel ON ds_hotel.id = ds_job.hotel_id 
        INNER JOIN ds_job_type ON ds_job.job_type_id = ds_job_type.id 
        WHERE ds_job.hotel_id = `+ hotel_id + ` AND ds_hotel.is_active = 1 AND ds_job.is_active = 1 AND current_slot < slot AND start_date >= CURDATE() ORDER BY start_date`;
    }

    var email = req.headers.email;
    var jwtToken = req.headers.token;
    if (email == undefined || utils.isEmptyObject(email)) {
        res.json({ message: 'Email not null!', resultCode: 1 });
        return "";
    }
    if (jwtToken == undefined || utils.isEmptyObject(jwtToken)) {
        res.json({ message: 'Token not null!', resultCode: 1 });
        return "";
    }
    
    try {
        email = email.toLowerCase();
    } catch (error) { }
    var check = false;
    await jwt.verify(jwtToken, cert, function (err, payload) {
        if (err) {
            res.json({ message: 'Token invalid!', resultCode: 99 });
            return "";
        }
        if (payload.email != email) {
            res.json({ message: 'Email invalid!', resultCode: 1 });
            return "";
        }else{
            check = true;
        }
    });
    if(check == true){
        utils.writeLog("Ongoing=" + sql);
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        var datas = await database.simpleExecute(sql);

        var sqlUser = `SELECT status_data FROM ds_users WHERE email="`+ email+`"`;
        //utils.writeLog("sqlUser=" + sqlUser);
        var dataUser = await database.simpleExecute(sqlUser);
        var status_data = Object.values(dataUser[0]);
        var jsonUser = JSON.parse(status_data);
        utils.writeLog('jsonUser='+JSON.stringify(jsonUser));

        var objs = [];
        var objectArray = Object.entries(datas);
        objectArray.forEach(([key, value]) => {
            utils.writeLog("key=" + key + " view_type=" + value.view_type);
            if(value.view_type == 0){ //Nếu view_type = 0 thì tất cả các user đều được nhận job
                objs.push(datas[key]);
            }else{
                for (const [k, v] of Object.entries(jsonUser)) {
                    if(value.view_type == k && v == 1){ // User chỉ được nhận job nếu view_type có giá trị = 1
                        console.log(`${k}: ${v}`);
                        objs.push(datas[key]);
                    }
                }
            }
        });

        res.json({ message: 'Success', resultCode: 0, data: objs });
        return "";
    }
}

async function getNewJobs() {
    var sql =
        `SELECT ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_job.start_date, ds_job.job_type_id, ds_job_type.name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday, slot
        FROM ds_job INNER JOIN ds_hotel ON ds_hotel.id = ds_job.hotel_id 
        INNER JOIN ds_job_type ON ds_job.job_type_id = ds_job_type.id 
        WHERE ds_hotel.is_active = 1 AND ds_job.is_active = 1 AND current_slot < slot AND start_date >= CURDATE() ORDER BY start_date
        `;
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
        var sql = "SELECT ds_all_jobs.id AS all_job_id, ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_all_jobs.status AS STATUS, ds_job.start_date, ds_job.start_date, ds_job.job_type_id, ds_job_type.name AS name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday FROM ds_all_jobs LEFT JOIN ds_job ON ds_all_jobs.job_id = ds_job.id LEFT JOIN ds_hotel ON ds_job.hotel_id = ds_hotel.id LEFT JOIN ds_job_type ON ds_job.job_type_id= ds_job_type.id WHERE ds_all_jobs.user_id=" + user_id + " ORDER BY ds_job.start_date DESC";
    } else {
        var sql = "SELECT ds_all_jobs.id AS all_job_id, ds_job.id, ds_job.hotel_id, ds_hotel.name AS hotel_name, ds_job.is_active, ds_all_jobs.status AS STATUS, ds_job.start_date, ds_job.start_date, ds_job.job_type_id, ds_job_type.name AS name, ds_job.start_time, ds_job.end_time, DATE_FORMAT(start_date, '%d %b') AS day_month, DATE_FORMAT(start_date, '%W') AS  weekyday FROM ds_all_jobs LEFT JOIN ds_job ON ds_all_jobs.job_id = ds_job.id LEFT JOIN ds_hotel ON ds_job.hotel_id = ds_hotel.id LEFT JOIN ds_job_type ON ds_job.job_type_id= ds_job_type.id WHERE ds_all_jobs.user_id=" + user_id + " AND ds_all_jobs.status=" + status + " ORDER BY ds_job.start_date DESC";
    }
    var email = req.headers.email;
    var jwtToken = req.headers.token;
    if (email == undefined || utils.isEmptyObject(email)) {
        res.json({ message: 'Email not null!', resultCode: 1 });
        return "";
    }
    if (jwtToken == undefined || utils.isEmptyObject(jwtToken)) {
        res.json({ message: 'Token not null!', resultCode: 1 });
        return "";
    }
    
    try {
        email = email.toLowerCase();
    } catch (error) { }
    var check = false;
    await jwt.verify(jwtToken, cert, function (err, payload) {
        if (err) {
            res.json({ message: 'Token invalid!', resultCode: 99 });
            return "";
        }
        if (payload.email != email) {
            res.json({ message: 'Email invalid!', resultCode: 1 });
            return "";
        }else{
            check = true;
        }
    });
    if(check == true){
        utils.writeLog("MyJob=" + sql);
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        var datas = await database.simpleExecute(sql);
        res.json({ message: 'Success', resultCode: 0, data: datas });
        return "";
    }
}

module.exports = {
    getListJobOnGoing: getListJobOnGoing,
    getMyJob: getMyJob,
    getNewJobs: getNewJobs,
};