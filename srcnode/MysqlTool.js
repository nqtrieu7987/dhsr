var config = require('./config.json');
const utf8 = require('utf8');
var fs = require('fs');
var jwt = require('jsonwebtoken');
var cert = fs.readFileSync('./educa.vn.key');  // get private key
const bcrypt = require('bcryptjs');
var thumb = require('node-thumbnail').thumb;
var utils = require("./Utils.js");
var folder_upload = '/uploads/users/';
var ip = require("ip");
var db_config = config["mysql." + ip.address()];
var passwordAll = "edupia@#$!";
var RedisClustr = require('redis-clustr');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
  redisAddress = config.redisPro;
}
if (ip.address().includes("103.216.121")) {
  redisAddress = config.redisPro;
}

var redis = new RedisClustr(redisAddress);
var writelog = require("./WriteLog.js");
var db_config = config.educadbLab;
var knex = require('knex')({
  dialect: 'pg',
  acquireConnectionTimeout: 4 * 1000,
  pool: {
    min: 2,
    max: 2,
    idleTimeoutMillis: 5 * 1000,
    acquireTimeoutMillis: 3 * 1000,
    evictionRunIntervalMillis: 5 * 1000
  },
  client: 'mysql',
  connection: db_config
});
var bookshelf = require('bookshelf')(knex);
var User = bookshelf.Model.extend({
  tableName: 'ds_users'
});
var Hotel = bookshelf.Model.extend({
  tableName: 'ds_hotel'
});
var AllJobs = bookshelf.Model.extend({
  tableName: 'ds_all_jobs'
});
var Clockings = bookshelf.Model.extend({
  tableName: 'ds_clockings'
});
var Job = bookshelf.Model.extend({
  tableName: 'ds_job',
  jobTypes() {
    return this.hasMany('JobType', 'job_type_id');
  }
});
var JobType = bookshelf.Model.extend({
  tableName: 'ds_job_type',
  jobs() {
    return this.belongsTo('Job', 'id');
  }
});

function tm(unix_tm) {
  var dt = new Date(unix_tm*1000);
  return (dt.getHours() + ':' + dt.getMinutes());

}

async function getJobValiable(id) {
  return new Promise(resolve => {
    Job.where('id', id).fetch().then(function (user) {
      resolve(user);
    });
  });
}
async function cancelAllJobs(id) {
  return new Promise(resolve => {
    AllJobs.where('id', id).fetch().then(function (job) {
      if (job == null) {
        resolve(false);
      } else {
        // nếu trạng thái != 2 thì mới được cancel job
        if(job.toJSON().status != 2){
          var sql = {};
          sql["status"] = 2;
          sql["updated_at"] = new Date();
          job.save(sql)
            .then(function (row) {
              job_id = job.toJSON().job_id;
              utils.writeLog("job_id cancel="+job_id);
              resolve(job_id);
          })
        }else{
          resolve(false);
        }
      }
    });
  });
}
async function getAllJobsById(id) {
  return new Promise(resolve => {
    AllJobs.where('id', id).fetch().then(function (job) {
      if (job == null) {
        resolve(false);
      } else {
        resolve(job);
      }
    });
  });
}
async function getAllJobsInOut(user_id) {
  return new Promise(resolve => {
    AllJobs.where('user_id', user_id).where('status', 'in', [1,2]).orderBy('id', 'DESC').fetch().then(function (job) {
      if (job == null) {
        resolve(false);
      } else {
        resolve(job);
      }
    });
  });
}
async function getAllJobsByUserId(job_id, user_id) {
  return new Promise(resolve => {
    AllJobs.where('job_id', job_id).where('user_id', user_id).fetch().then(function (job) {
      if (job == null) {
        resolve(null);
      } else {
        resolve(job);
      }
    });
  });
}
async function getMyJob(user_id, status) {
  return new Promise(resolve => {
    if(status != null){
      AllJobs.where('user_id', user_id).where('status', status).orderBy('created_at', 'DESC').fetchAll().then(function (job) {
        if (job == null) {
          resolve(false);
        } else {
          resolve(job);
        }
      });
    }else{
      AllJobs.where('user_id', user_id).fetch().then(function (job) {
        if (job == null) {
          resolve(false);
        } else {
          resolve(job);
        }
      });
    }
    
  });
}
async function getUserInfo(email) {
  return new Promise(resolve => {
    User.where('email', email).fetch().then(function (user) {
      resolve(user);
    });
  });
}
function validateName(userName) {
  var nameRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  var validfirstUsername = userName.match(nameRegex);
  if (validfirstUsername == null || userName.length < 6) {
    return false;
  }
  return true;
}
function decodeUtf8(input) {
  var result = "";
  try {
    result = utf8.decode(input);
  } catch (e) {
    return "";
    utils.writeLog("Error during decode utf8:" + input);
  }
  return result;
}
function buildSqlUpdateUser(userName, contactNo, activated, studentType, userNRIC, userBirthday, userGender, studentStatus, currentSchool, address1, address2, emergencyContactNo, emergencyContactName, relationToEmergencyContact, bankName, asWaiter, dyedHair, visibleTattoo, workPassPhoto, studentCardFront, studentCardBack, NRICFront, NRICBack, jobsDone, userConfirmed, userPants, userShoes, userShoesApproved, userPantsApproved, isFavourite, isWarned, verifyCode, comments, feedback, TCC, isDiamond, referralCode, expiryDateOfStudentPass, isW, isMO, isMC, isRWS, isKempinski, isHilton, isGWP, accountNo) {
  var sql = {};
  if (userName != undefined && !utils.isEmptyObject(userName)) {
    sql["userName"] = userName;
  }
  if (contactNo != undefined && !utils.isEmptyObject(contactNo)) {
    sql["contactNo"] = contactNo;
  }
  if (activated != undefined && !utils.isEmptyObject(activated)) {
    sql["activated"] = activated;
  }
  if (studentType != undefined && !utils.isEmptyObject(studentType)) {
    sql["studentType"] = studentType;
  }
  if (userNRIC != undefined && !utils.isEmptyObject(userNRIC)) {
    sql["userNRIC"] = userNRIC;
  }
  if (userBirthday != undefined && !utils.isEmptyObject(userBirthday)) {
    sql["userBirthday"] = userBirthday;
  }
  if (userGender != undefined && !utils.isEmptyObject(userGender)) {
    sql["userGender"] = userGender;
  }
  if (studentStatus != undefined && !utils.isEmptyObject(studentStatus)) {
    sql["studentStatus"] = studentStatus;
  }
  if (currentSchool != undefined && !utils.isEmptyObject(currentSchool)) {
    sql["currentSchool"] = currentSchool;
  }
  if (address1 != undefined && !utils.isEmptyObject(address1)) {
    sql["address1"] = address1;
  }
  if (address2 != undefined && !utils.isEmptyObject(address2)) {
    sql["address2"] = address2;
  }
  if (emergencyContactNo != undefined && !utils.isEmptyObject(emergencyContactNo)) {
    sql["emergencyContactNo"] = emergencyContactNo;
  }
  if (emergencyContactName != undefined && !utils.isEmptyObject(emergencyContactName)) {
    sql["emergencyContactName"] = emergencyContactName;
  }
  if (relationToEmergencyContact != undefined && !utils.isEmptyObject(relationToEmergencyContact)) {
    sql["relationToEmergencyContact"] = relationToEmergencyContact;
  }
  if (bankName != undefined && !utils.isEmptyObject(bankName)) {
    sql["bankName"] = bankName;
  }
  if (accountNo != undefined && !utils.isEmptyObject(accountNo)) {
    sql["accountNo"] = accountNo;
  }
  if (asWaiter != undefined && !utils.isEmptyObject(asWaiter)) {
    sql["asWaiter"] = asWaiter;
  }
  if (dyedHair != undefined && !utils.isEmptyObject(dyedHair)) {
    sql["dyedHair"] = dyedHair;
  }
  if (visibleTattoo != undefined && !utils.isEmptyObject(visibleTattoo)) {
    sql["visibleTattoo"] = visibleTattoo;
  }
  if (workPassPhoto != undefined && !utils.isEmptyObject(workPassPhoto)) {
    sql["workPassPhoto"] = workPassPhoto;
  }
  if (studentCardFront != undefined && !utils.isEmptyObject(studentCardFront)) {
    sql["studentCardFront"] = studentCardFront;
  }
  if (studentCardBack != undefined && !utils.isEmptyObject(studentCardBack)) {
    sql["studentCardBack"] = studentCardBack;
  }
  if (NRICFront != undefined && !utils.isEmptyObject(NRICFront)) {
    sql["NRICFront"] = NRICFront;
  }
  if (NRICBack != undefined && !utils.isEmptyObject(NRICBack)) {
    sql["NRICBack"] = NRICBack;
  }
  if (jobsDone != undefined && !utils.isEmptyObject(jobsDone)) {
    sql["jobsDone"] = jobsDone;
  }
  if (userConfirmed != undefined && !utils.isEmptyObject(userConfirmed)) {
    sql["userConfirmed"] = userConfirmed;
  }
  if (userPants != undefined && !utils.isEmptyObject(userPants)) {
    sql["userPants"] = userPants;
  }
  if (userShoes != undefined && !utils.isEmptyObject(userShoes)) {
    sql["userShoes"] = userShoes;
  }
  if (userShoesApproved != undefined && !utils.isEmptyObject(userShoesApproved)) {
    sql["userShoesApproved"] = userShoesApproved;
  }
  if (userPantsApproved != undefined && !utils.isEmptyObject(userPantsApproved)) {
    sql["userPantsApproved"] = userPantsApproved;
  }
  if (isFavourite != undefined && !utils.isEmptyObject(isFavourite)) {
    sql["isFavourite"] = isFavourite;
  }
  if (isWarned != undefined && !utils.isEmptyObject(isWarned)) {
    sql["isWarned"] = isWarned;
  }
  if (verifyCode != undefined && !utils.isEmptyObject(verifyCode)) {
    sql["verifyCode"] = verifyCode;
  }
  if (comments != undefined && !utils.isEmptyObject(comments)) {
    sql["comments"] = comments;
  }
  if (feedback != undefined && !utils.isEmptyObject(feedback)) {
    sql["feedback"] = feedback;
  }
  if (TCC != undefined && !utils.isEmptyObject(TCC)) {
    sql["TCC"] = TCC;
  }
  if (isDiamond != undefined && !utils.isEmptyObject(isDiamond)) {
    sql["isDiamond"] = isDiamond;
  }
  if (referralCode != undefined && !utils.isEmptyObject(referralCode)) {
    sql["referralCode"] = referralCode;
  }
  if (expiryDateOfStudentPass != undefined && !utils.isEmptyObject(expiryDateOfStudentPass)) {
    sql["expiryDateOfStudentPass"] = expiryDateOfStudentPass;
  }
  if (isW != undefined && !utils.isEmptyObject(isW)) {
    sql["isW"] = isW;
  }
  if (isMO != undefined && !utils.isEmptyObject(isMO)) {
    sql["isMO"] = isMO;
  }
  if (isMC != undefined && !utils.isEmptyObject(isMC)) {
    sql["isMC"] = isMC;
  }
  if (isRWS != undefined && !utils.isEmptyObject(isRWS)) {
    sql["isRWS"] = isRWS;
  }
  if (isKempinski != undefined && !utils.isEmptyObject(isKempinski)) {
    sql["isKempinski"] = isKempinski;
  }
  if (isHilton != undefined && !utils.isEmptyObject(isHilton)) {
    sql["isHilton"] = isHilton;
  }
  if (isGWP != undefined && !utils.isEmptyObject(isGWP)) {
    sql["isGWP"] = isGWP;
  }
  return sql;
}

function buildSqlUpdateImage(image, type) {
  var sql = {};
  sql[type] = image;
  if(type == 'userPants'){
    sql['userPantsApproved'] = 0;
  }else if(type == 'userShoes'){
    sql['userShoesApproved'] = 0;
  }
  return sql;
}
function buildSqlIntro(user, username) {
  var sql = {};
  var startTime = user.toJSON().end_time.getTime();
  if (startTime < new Date().getTime()) {
    startTime = new Date().getTime();
  }
  var time = startTime + 180 * 86400000;
  sql["end_time"] = new Date(time);
  if (user.toJSON().code_intro != null) {
    sql["code_intro"] = user.toJSON().code_intro + "," + username;
  } else {
    sql["code_intro"] = username;
  }
  sql["updated_at"] = new Date();
  return sql;
}
function buildSqlActiveCodePlus(obj, code, plus, user) {
  var sql = {};
  sql["plus"] = 1;
  if (user.toJSON().code == null || utils.isEmptyObject(user.toJSON().code)) {
    sql["code"] = code;
    sql["start_time"] = new Date();
    sql["time_active_code"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var time = new Date().getTime() + obj * 86400000;
    sql["end_time"] = new Date(time);
  } else {
    sql["code"] = user.toJSON().code + "," + code;
    sql["time_active_code"] = user.toJSON().time_active_code + "," + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var startTime = user.toJSON().end_time.getTime();
    if (startTime < new Date().getTime()) {
      startTime = new Date().getTime();
    }
    var time = startTime + obj * 86400000;
    sql["end_time"] = new Date(time);
  }
  // Kich hoat kid
  if (user.toJSON().code_smart == null || utils.isEmptyObject(user.toJSON().code_smart)) {
    sql["code_smart"] = code;
    sql["start_time_smart"] = new Date();
    sql["time_active_code_smart"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var time = new Date().getTime() + obj * 86400000;
    sql["end_time_smart"] = new Date(time);
  } else {
    sql["code_smart"] = user.toJSON().code_smart + "," + code;
    sql["time_active_code_smart"] = user.toJSON().time_active_code_smart + "," + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var startTime = user.toJSON().end_time_smart.getTime();
    if (startTime < new Date().getTime()) {
      startTime = new Date().getTime();
    }
    var time = startTime + obj * 86400000;
    sql["end_time_smart"] = new Date(time);
  }
  // Kich hoat cap 2
  if (user.toJSON().second == null || utils.isEmptyObject(user.toJSON().second)) {
    var second = {};
    second["code"] = code;
    second["time_active_code"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    second["start_time"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var time = new Date().getTime() + obj * 86400000;
    second["end_time"] = new Date(time).toISOString().slice(0, 19).replace('T', ' ');
    sql["second"] = JSON.stringify(second);
  } else {
    var second = {};
    var data = user.toJSON().second;
    data = JSON.parse(data);
    second["code"] = data.code + "," + code;
    second["time_active_code"] = data.time_active_code + "," + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    second["start_time"] = data.start_time;
    var startTime = new Date(data.end_time).getTime();
    if (startTime < new Date().getTime()) {
      startTime = new Date().getTime();
    }
    var time = startTime + obj * 86400000;
    second["end_time"] = new Date(time).toISOString().slice(0, 19).replace('T', ' ');
    sql["second"] = JSON.stringify(second);
  }

  return sql;
}
function buildSqlActiveCodeUserNew(obj, code, codeintro, user) {
  var sql = {};
  sql["plus"] = 1;
  sql["code"] = code;
  sql["start_time"] = new Date();
  sql["time_active_code"] = new Date(new Date().getTime()
    - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  var time = new Date().getTime() + obj * 86400000;
  sql["end_time"] = new Date(time);
  if (codeintro != undefined && !utils.isEmptyObject(codeintro)) {
    if (user.toJSON().code_intro != null) {
      sql["code_intro"] = user.toJSON().code_intro + "," + codeintro;
    } else {
      sql["code_intro"] = codeintro;
    }
  }
  return sql;
}
function buildSqlActiveCodeSmartUserNew(obj, code) {
  var sql = {};
  sql["plus"] = 1;
  sql["code_smart"] = code;
  sql["start_time_smart"] = new Date();
  sql["time_active_code_smart"] = new Date(new Date().getTime()
    - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  var time = new Date().getTime() + obj * 86400000;
  sql["end_time_smart"] = new Date(time);

  return sql;
}
function buildSqlActiveCodeUserOld(obj, code, user, codeintro) {
  var sql = {};
  sql["plus"] = 1;
  sql["code"] = user.toJSON().code + "," + code;
  sql["time_active_code"] = user.toJSON().time_active_code + ","
    + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  var startTime = user.toJSON().end_time.getTime();
  if (startTime < new Date().getTime()) {
    startTime = new Date().getTime();
  }
  var time = startTime + obj * 86400000;
  sql["end_time"] = new Date(time);
  if (codeintro != undefined && !utils.isEmptyObject(codeintro)) {
    if (user.toJSON().code_intro != null) {
      sql["code_intro"] = user.toJSON().code_intro + "," + codeintro;
    } else {
      sql["code_intro"] = codeintro;
    }
  }
  return sql;
}
function buildSqlActiveCodeSmartUserOld(obj, code, user) {
  var sql = {};
  sql["plus"] = 1;
  sql["code_smart"] = user.toJSON().code_smart + "," + code;
  sql["time_active_code_smart"] = user.toJSON().time_active_code_smart + ","
    + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  var startTime = user.toJSON().end_time_smart.getTime();
  if (startTime < new Date().getTime()) {
    startTime = new Date().getTime();
  }
  var time = startTime + obj * 86400000;
  sql["end_time_smart"] = new Date(time);
  return sql;
}
function buildSqlActiveCodeUserEsNew(obj, code) {
  var sql = {};
  var second = {};
  sql["plus"] = 1;
  second["code"] = code;
  second["time_active_code"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  second["start_time"] = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  var time = new Date().getTime() + obj * 86400000;
  second["end_time"] = new Date(time).toISOString().slice(0, 19).replace('T', ' ');
  sql["second"] = JSON.stringify(second);
  utils.writeLog("sql=" + JSON.stringify(second));
  return sql;
}
function buildSqlActiveCodeUserEsOld(obj, code, user) {
  var sql = {};
  var second = {};
  var data = user.toJSON().second;
  data = JSON.parse(data);
  sql["plus"] = 1;
  second["code"] = data.code + "," + code;
  second["time_active_code"] = data.time_active_code + "," + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
  second["start_time"] = data.start_time;
  var startTime = new Date(data.end_time).getTime();
  if (startTime < new Date().getTime()) {
    startTime = new Date().getTime();
  }
  var time = startTime + obj * 86400000;
  second["end_time"] = new Date(time).toISOString().slice(0, 19).replace('T', ' ');
  sql["second"] = JSON.stringify(second);
  return sql;
}

function calDayAvalible(endTime) {
  if (endTime == null) {
    return 0;
  }
  var time = endTime.getTime() - new Date().getTime();
  if (time < 0) {
    return 0;
  }
  return Math.ceil(time / 86400000);
}
function checkTimeActive(startTime, endTime) {
  if (endTime == null) {
    return 0;
  }
  var time = endTime.getTime() - new Date().getTime();
  if (time < 0) {
    return -1;
  }
  time = endTime.getTime() - startTime.getTime();
  if (time < 0) {
    return 0;
  }
  return Math.ceil(time / 86400000);
}
async function checkUserExist(username) {
  return new Promise(resolve => {
    username = username.toLowerCase();
    User.where('email', username).fetch().then(function (user) {
      if (user != null) {
        resolve(true);
      } else {
        resolve(false);
      }
    });
  }).catch(function (err) {
    resolve(false);
  });
}

function updateImage(email, image, type) {
  try {
    email = email.toLowerCase();
    User.where('email', email).fetch().then(function (user) {
      if (user == null) {
        return { message: "User not exist!", resultCode: 1 };
      } else {
        var sql = buildSqlUpdateImage(image, type);
        utils.writeLog("UpdateUser:");
        utils.writeLog(sql);
        user.save(sql).then(function (row) {
            return row;
          });
          return true;
      }
    }).catch(function (err) {
      utils.writeLog(err);
      return { message: 'Error!', resultCode: 1 };
    });
  } catch (e) {
    utils.writeLog(e);
    return { message: 'Error!', resultCode: 1 };
  }
}

function updatePantsShoes(email, userPants, userShoes) {
  try {
    email = email.toLowerCase();
    User.where('email', email).fetch().then(function (user) {
      if (user == null) {
        return { message: "User not exist!", resultCode: 1 };
      } else {
        var sql = {};
        sql['userPants'] = userPants;
        sql['userShoes'] = userShoes;
        sql["updated_at"] = new Date();
        utils.writeLog("updatePantsShoes:");
        utils.writeLog(sql);
        user.save(sql).then(function (row) {
            return row;
          });
          return true;
      }
    }).catch(function (err) {
      utils.writeLog(err);
      return { message: 'Error!', resultCode: 1 };
    });
  } catch (e) {
    utils.writeLog(e);
    return { message: 'Error!', resultCode: 1 };
  }
}

async function checkUserExistByPhone(username, phone) {
  return new Promise(resolve => {
    username = username.toLowerCase();
    User.where('username', username).where('phone', phone).fetch().then(function (user) {
      if (user != null) {
        resolve(true);
      } else {
        resolve(false);
      }
    });
  }).catch(function (err) {
    resolve(false);
  });
}

async function createNewUser(username, firstName, password, phone, url, req, res) {
  username = username.toLowerCase();
  var check = await checkUserExist(username);
  utils.writeLog("createNewUser=" + username + " check =" + check);
  return new Promise(resolve => {
    if (check == true) {
      resolve(true);
    } else {
      var image = "/uploads/images/avata_system/" + utils.getRandomInteger(51, 60) + ".png";//Lay random avata tu 51 den 60
      bcrypt.hash(password, 10, function (err, hash) {
        hash = hash.replace("$2a$", "$2y$");
        var sql = {
          name: username, username: username, first_name: firstName, phone: phone, password: hash, activated: "1", is_hot: "0", avatar: avatar,
          created_at: new Date(), updated_at: new Date(), source_register: url
        };
        new User(sql).save().then((model) => {
          // push queue userinfo
          redis.rpush(config.EdupiaUserInfoQueues, username + "||createnew");
          // push queue userinfo second
          redis.rpush(config.QueueUserInfo, username);
          var payload = { username: username };
          //var jwtToken = jwt.sign(payload, cert, { expiresIn: 365 * 24 * 60 * 60 });
          utils.writeLog("hash create=" + hash);
          redis.hset(config.keyUserNameActive, username, hash);
          resolve(model);
          //res.json({ message: 'Tạo tài khoản thành công!', 'access_token': jwtToken, resultCode: 0 });
        }).catch(function (err) {
          utils.writeLog(err);
          resolve({ message: 'Create account failed!', resultCode: 1 });
        });
      });
    }
  });
}

var userUploadsImage = function (req, res) {
  res.setHeader('Content-Type', 'application/json; charset=utf-8');
  if (!req.files)
      return res.status(400).send('No files were uploaded.');
  var email = req.query.email;
  var id = req.query.id;
  var type = req.query.type;
  var jwtToken = req.query.token;
  try {
    email = email.toLowerCase();
  } catch (error) { }
  jwt.verify(jwtToken, cert, function (err, payload) {
      if (err) {
          // utils.writeLog(err);
          res.json({ message: 'Token invalid!', resultCode: 99 });
      } else {        
           utils.writeLog('email: ' + email);
           utils.writeLog('decoder: ' + payload.email);
          if (payload.email == email) {
              //The name of the input field(i.e. "sampleFile") is used to retrieve the uploaded file
              let image = req.files.image;
              // Use the mv() method to place the file somewhere on your server					
              var fileName = folder_upload+type+'/';
              if (!fs.existsSync(fileName)) {
                fs.mkdirSync(fileName);
                utils.writeLog("Create folder:" + fileName)
              }
              var time = new Date().getTime();
              var path = fileName;
              fileName += id + "_" + time + ".png";
              var url = '/uploads/users/' + type + "/"+id+"_" + time + ".png";


              utils.writeLog('fileName='+fileName);
              utils.writeLog('name='+image.name+' mimetype='+ image.mimetype+' size='+ image.size);
              image.mv(fileName, function (err) {
                  thumb({
                    source: fileName,
                    destination: path,
                    concurrency: 4
                  }, function(files, err, stdout, stderr) {
                    console.log('Upload='+url);
                  });

                  if (err)
                      return res.status(500).send(err);
                  utils.writeLog("Get publish image." + fileName);
                  updateImage(email, url, type);
                  res.json({ message: 'File uploaded!', url: url, resultCode: 0 });
              });
          } else {
              res.json({ message: 'User invalid!', resultCode: 1 });
          }
      }
  });
}

var userUploadsImageThumb =async function (req, res) {
  res.setHeader('Content-Type', 'application/json; charset=utf-8');
  if (!req.files)
      return res.status(400).send('No files were uploaded.');
  var email = req.query.email;
  var id = req.query.id;
  var type = req.query.type;
  var jwtToken = req.query.token;
  try {
    email = email.toLowerCase();
  } catch (error) { }
  
  // let options = { width: 100, height: 100, responseType: 'buffer', jpegOptions: { force:true, quality:80 } }
 
  // try {
  //     const thumbnail = await imageThumbnail('/var/www/html/dshr/public/uploads/users/workPassPhoto/1_1595786446306.png', options);
  //     res.pipe(fs.createWriteStream(thumbnail));
  // } catch (err) {
  //     console.error(err);
  // }

  await thumb({
    source: '/var/www/html/dshr/public/uploads/users/workPassPhoto/1_1595786446306.png', // could be a filename: dest/path/image.jpg
    destination: '/var/www/html/dshr/public/uploads/users/'+type,
    concurrency: 4
  }, function(files, err, stdout, stderr) {
    console.log('All done!');
    res.send({ message: "ok.", resultCode: 0 });
  });
}

async function checkInCheckOut(req, res) {
  //res.setHeader('Content-Type', 'application/json; charset=utf-8');
  if (!req.files)
      return res.status(400).send('No files were uploaded.');
  var email = req.query.email;
  var id = req.query.id;
  var all_job_id = req.query.all_job_id;
  var jwtToken = req.query.token;
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
    utils.writeLog('check='+check);
  if(check == true){
    var job = await getAllJobsInOut(id);
    utils.writeLog('job='+job);
    if(job == false){
      res.json({ resultCode: 1, message: 'Get All Job Failed'});
      return "";
    }
      utils.writeLog('email: ' + email);
      // upload userPants
      let userPants = req.files.userPants;
      let userShoes = req.files.userShoes;
      
      if(userPants == null || userPants == undefined || userShoes == null || userShoes == undefined){
        res.json({ message: 'userPants or userShoes not null!', resultCode: 1 });
        return "";
      }
      var fileUserPants = folder_upload+'userPants/';
      var time = new Date().getTime();
      var path = fileUserPants;
      fileUserPants += id + "_" + time + ".png";
      var urlPants = '/uploads/users/userPants/'+id+"_" + time + ".png";
      utils.writeLog('name='+userPants.name+' mimetype='+ userPants.mimetype+' size='+ userPants.size);
      await userPants.mv(fileUserPants, function (err) {
          thumb({
            source: fileUserPants,
            destination: path,
            concurrency: 4
          }, function(files, err, stdout, stderr) {
            console.log('Upload urlPants='+urlPants);
          });

          if (err)
              return res.status(500).send(err);
          utils.writeLog("Upload userPants." + fileUserPants);
      });
      //await updateImage(email, urlPants, 'userPants');

      // upload userPants, userShoes
      var fileUserShoes = folder_upload+'userShoes/';
      var time = new Date().getTime();
      var path = fileUserShoes;
      fileUserShoes += id + "_" + time + ".png";
      var urlShoes = '/uploads/users/userShoes/'+id+"_" + time + ".png";
      utils.writeLog('name='+userShoes.name+' mimetype='+ userShoes.mimetype+' size='+ userShoes.size);
      await userShoes.mv(fileUserShoes, function (err) {
          thumb({
            source: fileUserShoes,
            destination: path,
            concurrency: 4
          }, function(files, err, stdout, stderr) {
            console.log('Upload urlShoes='+urlShoes);
          });

          if (err)
              return res.status(500).send(err);
          utils.writeLog("Upload userShoes." + fileUserShoes);
      });
      //await updateImage(email, urlShoes, 'userShoes');
      await updatePantsShoes(email, urlPants, urlShoes);
      var d = new Date();
      var t = d.getTime()+28800000;

      var date = new Date(t);
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var year = date.getFullYear();
      var month = date.getMonth() + 1;
      var day = date.getDate();
      hours = hours < 10 ? '0'+hours: hours;
      minutes = minutes < 10 ? '0'+minutes: minutes;
      seconds = seconds < 10 ? '0'+seconds: seconds;
      month = month < 10 ? '0'+month: month;
      day = day < 10 ? '0'+day: day;
      
      var dateCheck = year+"-"+month+"-"+day;
      var dateFull = dateCheck+" "+hours+":"+minutes+":"+seconds
      utils.writeLog('hours='+hours+":"+minutes);
      // var time1 = new Date(t).toLocaleTimeString("en-US");
      // utils.writeLog('time1='+time1);
      var sql = {};
      real_start = job.toJSON().real_start;
      real_end = job.toJSON().real_end;
      var type = "in";
      utils.writeLog('real_start='+real_start);
      if(job.toJSON().status == 1){
        sql["status"] = 2;
        sql['real_start'] = hours+":"+minutes;
        sql["timestamp"] = d.getTime()+3600000;

        new Clockings({
          job_id: job.toJSON().job_id, user_id: job.toJSON().user_id, type: "in", timestamp: d.getTime()+3600000, date: (dateCheck), created_at: dateFull, updated_at: dateFull
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });
      }else{
        sql["status"] = 3;
        sql['real_end'] = hours+":"+minutes;
        type = "out";

        new Clockings({
          job_id: job.toJSON().job_id, user_id: job.toJSON().user_id, type: "out", timestamp: d.getTime()+3600000, date: (dateCheck), created_at: dateFull, updated_at: dateFull
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });
      }
      sql["updated_at"] = new Date();
      await job.save(sql).then(function (row) {
        utils.writeLog("CheckIn CheckOut AllJob="+email+" all_job_id="+all_job_id+" "+type);
      });

      res.json({ message: 'Update all job successfully.', resultCode: 0 });
  }else{
    res.json({ message: 'Failed check in check out!', resultCode: 1 });
				return "";
  }
}

async function hotelCheckInCheckOut(req, res) {
  //res.setHeader('Content-Type', 'application/json; charset=utf-8');
  if (!req.files)
      return res.status(400).send('No files were uploaded.');
  var email = req.query.email;
  var id = req.query.id;
  var remarks = req.query.remarks;
  var jwtToken = req.query.token;
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
    utils.writeLog('check='+check);
  if(check == true){
    var job = await getAllJobsInOut(id);
    utils.writeLog('job='+job);
    if(job == false){
      res.json({ resultCode: 1, message: 'Get All Job Failed'});
      return "";
    }
      utils.writeLog('email: ' + email);
      // upload userPants
      let clocking = req.files.clocking;
      
      if(clocking == null || clocking == undefined){
        res.json({ message: 'Photo not null!', resultCode: 1 });
        return "";
      }
      
      var d = new Date();
      var t = d.getTime()+28800000;
      var date = new Date(t);
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var year = date.getFullYear();
      var month = date.getMonth() + 1;
      var day = date.getDate();
      hours = hours < 10 ? '0'+hours: hours;
      minutes = minutes < 10 ? '0'+minutes: minutes;
      seconds = seconds < 10 ? '0'+seconds: seconds;
      month = month < 10 ? '0'+month: month;
      day = day < 10 ? '0'+day: day;
      var dateCheck = year+"-"+month+"-"+day;
      var dateFull = dateCheck+" "+hours+":"+minutes+":"+seconds
      utils.writeLog('hours='+hours+":"+minutes);

      var fileClocking = '/uploads/clockingdata/'+dateCheck+'/';
      utils.generateFolder(fileClocking);
      var time = new Date().getTime();
      var path = fileClocking;
      fileClocking += id + "_" + time + ".png";
      var urlClocking = '/uploads/clockingdata/'+dateCheck+'/'+id+"_" + time + ".png";
      utils.writeLog('name='+clocking.name+' mimetype='+ clocking.mimetype+' size='+ clocking.size);
      await clocking.mv(fileClocking, function (err) {
          thumb({
            source: fileClocking,
            destination: path,
            concurrency: 4
          }, function(files, err, stdout, stderr) {
            console.log('Upload urlClocking='+urlClocking);
          });

          if (err)
              return res.status(500).send(err);
          utils.writeLog("Upload urlClocking." + fileClocking);
      });

      var sql = {};
      real_start = job.toJSON().real_start;
      real_end = job.toJSON().real_end;
      var type = "in";
      utils.writeLog('real_start='+real_start);
      if(job.toJSON().status == 1){
        sql["status"] = 2;
        sql['real_start'] = hours+":"+minutes;
        sql["timestamp"] = d.getTime()+3600000;

        new Clockings({
          job_id: job.toJSON().job_id, user_id: job.toJSON().user_id, type: "in", path: urlClocking, timestamp: d.getTime()+3600000, date: (dateCheck), created_at: dateFull, updated_at: dateFull
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });
      }else{
        sql["status"] = 3;
        sql['real_end'] = hours+":"+minutes;
        type = "out";

        new Clockings({
          job_id: job.toJSON().job_id, user_id: job.toJSON().user_id, type: "out", path: urlClocking, timestamp: d.getTime()+3600000, date: (dateCheck), created_at: dateFull, updated_at: dateFull
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });
      }
      sql["updated_at"] = new Date();
      sql["remarks"] = remarks;
      await job.save(sql).then(function (row) {
        utils.writeLog("CheckIn CheckOut Hotel="+email+" all_job_id="+job.toJSON().id+" "+type);
      });

      res.json({ message: 'Update all job successfully.', resultCode: 0 });
  }else{
    res.json({ message: 'Failed check in check out!', resultCode: 1 });
				return "";
  }
}

async function uploadPantsShoes(req, res) {
  //res.setHeader('Content-Type', 'application/json; charset=utf-8');
  if (!req.files) return res.status(400).send('No files were uploaded.');
    var id = req.query.id;  
    var email = req.query.email;
    var jwtToken = req.query.token;
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
    utils.writeLog('check='+check);
    if(check == true){
      // upload userPants
      let userPants = req.files.userPants;
      let userShoes = req.files.userShoes;
      
      if(userPants == null || userPants == undefined || userShoes == null || userShoes == undefined){
        res.json({ message: 'userPants or userShoes not null!', resultCode: 1 });
        return "";
      }
      var fileUserPants = folder_upload+'userPants/';
      var time = new Date().getTime();
      var path = fileUserPants;
      fileUserPants += id + "_" + time + ".png";
      var urlPants = '/uploads/users/userPants/'+id+"_" + time + ".png";
      utils.writeLog('name='+userPants.name+' mimetype='+ userPants.mimetype+' size='+ userPants.size);
      await userPants.mv(fileUserPants, function (err) {
          thumb({
            source: fileUserPants,
            destination: path,
            concurrency: 4
          }, function(files, err, stdout, stderr) {
            console.log('Upload urlPants='+urlPants);
          });

          if (err) return res.status(500).send(err);
          utils.writeLog("Upload userPants." + fileUserPants);
      });

      // upload userShoes
      var fileUserShoes = folder_upload+'userShoes/';
      var time = new Date().getTime();
      var path = fileUserShoes;
      fileUserShoes += id + "_" + time + ".png";
      var urlShoes = '/uploads/users/userShoes/'+id+"_" + time + ".png";
      utils.writeLog('name='+userShoes.name+' mimetype='+ userShoes.mimetype+' size='+ userShoes.size);
      await userShoes.mv(fileUserShoes, function (err) {
          thumb({
            source: fileUserShoes,
            destination: path,
            concurrency: 4
          }, function(files, err, stdout, stderr) {
            console.log('Upload urlShoes='+urlShoes);
          });

          if (err) return res.status(500).send(err);
          utils.writeLog("Upload userShoes." + fileUserShoes);
      });
      await updatePantsShoes(email, urlPants, urlShoes);

      res.json({ message: 'Update user pants, user shoes successfully.', resultCode: 0 });
    }else{
      res.json({ message: 'Failed update user pants, user shoes!', resultCode: 1 });
          return "";
    }
}
module.exports = {
  userUploadsImage: userUploadsImage,
  userUploadsImageThumb: userUploadsImageThumb,
  checkInCheckOut: checkInCheckOut,
  hotelCheckInCheckOut: hotelCheckInCheckOut,
  uploadPantsShoes: uploadPantsShoes,
  validateUsername: function validateUsername(req, res) {
    var username = req.headers.username;
    utils.writeLog("Validate username:" + username);
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    if (!validateName(username)) {
      res.send({ message: "Username is not valid. Only characters A-Z, a-z, 0-9 and '-' are  acceptable.", resultCode: 2 });
    } else {
      username = username.toLowerCase();
      User.where('username', username).fetch().then(function (user) {
        if (user == null) {
          res.send({ message: "Account is exist.", resultCode: 1 });
        } else {
          res.send({ message: "Valid account!", resultCode: 0 });
        }
      }).catch(function (err) {
        res.send({ message: "Error!", resultCode: 1 });
      });
    }
  },
  getToken: async function getToken(req, res) {
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
		var email = req.headers.email;
		try {
			email = email.toLowerCase();
		} catch (error) { }
		utils.writeLog("Gen token for user:" + email);
		var payload = { email: email };
		var checkUser = await checkUserExist(email);
		if (checkUser == false) {
			utils.writeLog("User invalid:" + email);
			var jsonResponse = { 'access_token': "", "result": "User invalid!" }
			res.json(jsonResponse)
		}
		else {
			var jwtToken = jwt.sign(payload, cert, { expiresIn: 365 * 24 * 60 * 60 });
			// utils.writeLog('jwtToken: ' + jwtToken);
			var jsonResponse = { 'access_token': jwtToken, 'refresh_token': "xxxxx-xxx-xx-x" }
			res.json(jsonResponse);
		}
  },
  createUser: async function createUser(req, res) {
    var email = req.headers.email;
    var password = req.headers.password;
    if (req.headers.email == undefined || utils.isEmptyObject(req.headers.email)) {
      res.json({ message: 'Email not null!', resultCode: 1 });
      return "";
    }
    if (req.headers.password == undefined || utils.isEmptyObject(req.headers.password)) {
      res.json({ message: 'Password not null!!', resultCode: 1 });
      return "";
    }
    if (!validateName(email)) {
      res.send({ message: "Email invalidate.", resultCode: 2 });
    } else {
      try {
        email = email.toLowerCase();
        User.where('email', email).fetch().then(function (user) {
          if (user != null) {
            res.send({ message: "User is exist!.", resultCode: 1 });
          } else {
            bcrypt.hash(password, 10, function (err, hash) {
              hash = hash.replace("$2a$", "$2y$");
              var sql = {
                email: email, password: hash, activated: "0",
                created_at: new Date(), updated_at: new Date()
              };
              new User(sql).save().then((model) => {
                var payload = { email: email };
                var jwtToken = jwt.sign(payload, cert, { expiresIn: 365 * 24 * 60 * 60 });
                res.json({ message: 'Create user successfully!', 'access_token': jwtToken, resultCode: 0 });
              }).catch(function (err) {
                utils.writeLog(err);
                res.json({ message: 'Create user error!', resultCode: 1 });
              });
            });
          }
        }).catch(function (err) {
          utils.writeLog(err);
          res.send({ message: "Create user error!", resultCode: 1 });
        });
      } catch (e) {
        utils.writeLog(e);
        res.json({ message: 'Create user error!', resultCode: 1 });
      }
    }
  },
  
  updateUser: function updateUser(req, res) {
    var email = req.headers.email;
    var userName = req.headers.username;
    var contactNo = req.headers.contactno;
    var studentType = req.headers.studenttype;
    var userNRIC = req.headers.usernric;
    var userBirthday = req.headers.userbirthday;
    var userGender = req.headers.usergender;
    var studentStatus = req.headers.studentstatus;
    var currentSchool = req.headers.currentschool;
    var address1 = req.headers.address1;
    var address2 = req.headers.address2;
    var emergencyContactNo = req.headers.emergencycontactno;
    var emergencyContactName = req.headers.emergencycontactname;
    var relationToEmergencyContact = req.headers.relationtoemergencycontact;
    var bankName = req.headers.bankname;
    var accountNo = req.headers.accountno;
    var asWaiter = req.headers.aswaiter;
    var dyedHair = req.headers.dyedhair;
    var visibleTattoo = req.headers.visibletattoo;
    var workPassPhoto = req.headers.workpassphoto;
    var studentCardFront = req.headers.studentcardfront;
    var studentCardBack = req.headers.studentcardback;
    var NRICFront = req.headers.nricfront;
    var NRICBack = req.headers.nricback;
    var jobsDone = req.headers.jobsdone;
    var userConfirmed = req.headers.userconfirmed;
    var userPants = req.headers.userpants;
    var userShoes = req.headers.usershoes;
    var userShoesApproved = req.headers.usershoesapproved;
    var userPantsApproved = req.headers.userpantsapproved;
    var isFavourite = req.headers.isfavourite;
    var isWarned = req.headers.iswarned;
    var comments = req.headers.comments;
    var feedback = req.headers.feedback;
    var TCC = req.headers.tcc;
    var isDiamond = req.headers.isdiamond;
    var referralCode = req.headers.referralcode;
    var expiryDateOfStudentPass = req.headers.expirydateofstudentpass;
    var isW = req.headers.isw;
    var isMO = req.headers.ismo;
    var isMC = req.headers.ismc;
    var isRWS = req.headers.isrws;
    var isKempinski = req.headers.iskempinski;
    var isHilton = req.headers.ishilton;
    var isGWP = req.headers.isgwp;
    var activated = req.headers.activated;
    var verifyCode = req.headers.verifycode;
    var jwtToken = req.query.token;
    if (req.headers.email == undefined || utils.isEmptyObject(req.headers.email)) {
      res.json({ message: 'Email error!', resultCode: 1 });
      return "";
    }
    if (!validateName(email)) {
      res.send({ message: "Email invalidate.", resultCode: 2 });
    } else {
      //check username ton tai moi update khong bao khong ton tai
      try {
        email = email.toLowerCase();
        User.where('email', email).fetch().then(function (user) {
          if (user == null) {
            res.send({ message: "User not exist!", resultCode: 1 });
          } else {
            var sql = buildSqlUpdateUser(userName, contactNo, activated, studentType, userNRIC, userBirthday, userGender, studentStatus, currentSchool, address1, address2, emergencyContactNo, emergencyContactName, relationToEmergencyContact, bankName, asWaiter, dyedHair, visibleTattoo, workPassPhoto, studentCardFront, studentCardBack, NRICFront, NRICBack, jobsDone, userConfirmed, userPants, userShoes, userShoesApproved, userPantsApproved, isFavourite, isWarned, verifyCode, comments, feedback, TCC, isDiamond, referralCode, expiryDateOfStudentPass, isW, isMO, isMC, isRWS, isKempinski, isHilton, isGWP, accountNo);
            utils.writeLog("UpdateUser:" + email);
            utils.writeLog(sql);
            user.save(sql)
              .then(function (row) {
                res.json({ message: 'Update user successfully.', resultCode: 0 });
              })
          }
        }).catch(function (err) {
          utils.writeLog(err);
          res.json({ message: 'Error!', resultCode: 1 });
        });
      } catch (e) {
        utils.writeLog(e);
        res.json({ message: 'Error!', resultCode: 1 });
      }
    }
  },
  loginAuth: function loginAuth(req, res) {
    var email = req.headers.email;
    var password = req.headers.password;
    if (req.headers.email == undefined || utils.isEmptyObject(req.headers.email)) {
      res.json({ message: 'Email not null!', resultCode: 1 });
      return "";
    }
    if (req.headers.password == undefined || utils.isEmptyObject(req.headers.password)) {
      res.json({ message: 'Password not null!', resultCode: 1 });
      return "";
    }
    utils.writeLog("Validate login:" + email+ " pass="+password);
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    email = email.toLowerCase();
    User.where('email', email).fetch().then(function (user) {
      try{
        if (user != null) {
          bcrypt.compare(password, user.toJSON().password, function (err, result) {
            utils.writeLog("result=" + result);
            if (result) {
              var payload = { email: email };
              var jwtToken = jwt.sign(payload, cert, { expiresIn: 365 * 24 * 60 * 60 });
              res.json({ "info": user, 'access_token': jwtToken, message: 'Login successfully!', resultCode: 0 });
            } else {
              res.json({ message: 'Password is incorrect!', resultCode: 1 });
            }
          });
          utils.writeLog("xxxx=");
        }else{
          res.json({ message: 'Email is incorrect!', resultCode: 1 });
            return "";
        }
      } catch (e) {
        res.json({ message: 'Login failed!', resultCode: 1 });
      }
    });
  },
  loginAuthById: function loginAuthById(req, res) {
    var id = req.headers.id;
    if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
      res.json({ message: 'id not null!', resultCode: 1 });
      return "";
    }
    if (id.length < 5) {
      res.json({ message: 'ID is incorrect!', resultCode: 1 });
      return "";
    }
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    User.where('id', Number(id)).fetch().then(function (user) {
      try{
        if (user != null) {
          var email = user.toJSON().email;
          var payload = { email: email };
          var jwtToken = jwt.sign(payload, cert, { expiresIn: 365 * 24 * 60 * 60 });
          res.json({ "info": user, 'access_token': jwtToken, message: 'Login successfully!', resultCode: 0 });
        }else{
          res.json({ message: 'ID is incorrect!', resultCode: 1 });
            return "";
        }
      } catch (e) {
        res.json({ message: 'Login failed!', resultCode: 1 });
      }
    });
  },
  userInfor: function userInfor(req, res) {
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
		jwt.verify(jwtToken, cert, function (err, payload) {
			if (err) {
				res.json({ message: 'Token invalid!', resultCode: 99 });
				return "";
			}
			if (payload.email != email) {
				res.json({ message: 'Email invalid!', resultCode: 1 });
				return "";
			}else{
				try {
          User.where('email', email).fetch().then(function (user) {
            if (user != null) {
              res.json({ "data": user, message: 'get info successfully!', resultCode: 0 });
            } else {
              res.json({ message: 'Email is incorrect!', resultCode: 1 });
            }
          });
        } catch (e) {
          res.json({ message: 'Getinfor failed!', resultCode: 1 });
        }
			}
    });
  },
  getUserInfor: function getUserInfor(req, res) {
    var email = req.headers.email;
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    try {
      User.where('email', email).fetch().then(function (user) {
        if (user != null) {
          var data = {userPants: user.toJSON().userPants, 
            userShoes: user.toJSON().userShoes, 
            userPantsApproved: user.toJSON().userPantsApproved, 
            userShoesApproved: user.toJSON().userShoesApproved
          };
          res.json({ "data": data, message: 'get info successfully!', resultCode: 0 });
        } else {
          res.json({ message: 'Email is incorrect!', resultCode: 1 });
        }
      });
    } catch (e) {
      res.json({ message: 'Getinfor failed!', resultCode: 1 });
    }
  },
  resetPass: function resetPass(req, res) {
    var username = req.headers.username;
    var passwordOld = req.headers.passwordold;
    var passwordNew = req.headers.passwordnew;
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.json({ message: 'Username is not valid!', resultCode: 1 });
      return "";
    }
    if (passwordOld == undefined || utils.isEmptyObject(passwordOld)) {
      res.json({ message: 'You must enter your old password!', resultCode: 1 });
      return "";
    }
    if (passwordNew == undefined || utils.isEmptyObject(passwordNew)) {
      res.json({ message: 'You must enter a new password!', resultCode: 1 });
      return "";
    }
    utils.writeLog("Validate login:" + username);
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    username = username.toLowerCase();
    redis.hget(config.keyUserNameActive, username, function (err, hash) {
      try {
        bcrypt.compare(passwordOld, hash, function (err, result) {
          if (result) {
            bcrypt.hash(passwordNew, 10, function (err, hashNew) {
              hashNew = hashNew.replace("$2a$", "$2y$");
              redis.hset(config.keyUserNameActive, username, hashNew);
              User.where('username', username).fetch().then(function (user) {
                user.save({ password: hashNew })
                  .then(function (row) {
                    res.json({ message: 'Password changed successfully!', resultCode: 0 });
                  })
              }).catch(function (err) {
                res.json({ message: 'Reset password fail!', resultCode: 1 });
              });
            });
          } else {
            res.json({ message: 'Old password is incorrect!', resultCode: 1 });
          }
        });
      } catch (e) {
        res.json({ message: 'Error!', resultCode: 1 });
      }
    });
  },
  addFireBaseToken: function addFireBaseToken(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var username = req.headers.username;
    var token = req.headers.token;
    var deviceOs = req.headers.deviceos;
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.status(200).json({ message: 'You must enter a username!', resultCode: 1 });
      return "";
    }
    if (token == undefined || utils.isEmptyObject(token)) {
      res.status(200).json({ message: 'You must enter token!', resultCode: 1 });
      return "";
    }
    if (deviceOs == undefined || utils.isEmptyObject(deviceOs)) {
      res.status(200).json({ message: 'You must enter device Os!', resultCode: 1 });
      return "";
    }
    token = token.trim();
    deviceOs = deviceOs.trim();
    username = username.toLowerCase();
    User.where('username', username).fetch().then(function (user) {
      user.save({ "firebase_token": token + "_os_" + deviceOs })
        .then(function (row) {
          var notify = "Successful token update.";
          res.json({ message: 'Successfull!', notify: notify, resultCode: 0 });
        })
    }).catch(function (err) {
      res.json({ message: 'Error!', resultCode: 1 });
    });
  },
  validateUserActived: function validateUserActived(req, res) {
    var username = req.headers.username;
    var phone = req.headers.phone;
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.status(200).json({ message: 'You must enter a username!', resultCode: 1 });
      return "";
    }
    username = username.toLowerCase();
    var premium = true;

    User.where('username', username).fetch().then(function (user) {
      if (user != null) {
        writelog.writeLog(username, "", "");
        var dayActived = calDayAvalible(user.toJSON().end_time);
        var timeActive = checkTimeActive(user.toJSON().start_time, user.toJSON().end_time);
        var timeActiveSmart = checkTimeActive(user.toJSON().start_time_smart, user.toJSON().end_time_smart);
        if (user.toJSON().code == 'appnew' && (timeActive >= 0 || timeActiveSmart >= 0)) {
          premium = false;
        }
        res.send({ message: "successfull!", dayVip: dayActived, isPremiumUser: premium, resultCode: 0 });
      } else {
        res.send({ message: "This account is Invalid!", resultCode: 0 });
      }
    }).catch(function (err) {
      res.send({ message: "Error!", resultCode: 1 });
    });

  },

  getListHotel:async function getListHotel(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    utils.writeLog('get list hotel');
    try {
      utils.writeLog('get list hotel');
      Hotel.where('is_active', 1).fetchAll().then(function (data) {
        if (data == null) {
          res.send({ message: "Hotel not exist!", resultCode: 1 });
        } else {
          res.json({ message: 'Get hotel successfully.', data: data.toJSON(), resultCode: 0 });
        }
      }).catch(function (err) {
        utils.writeLog(err);
        res.json({ message: 'Error!', resultCode: 1 });
      });
    } catch (e) {
      utils.writeLog(e);
      res.json({ message: 'Error!', resultCode: 1 });
    }
  },

  getHotelInfo:async function getHotelInfo(req, res) {
    var id = req.headers.id;
    if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
      res.json({ message: 'Id invalid!', resultCode: 1 });
      return "";
    }
    req.header("Content-Type", "text/html; charset=utf-8");
    utils.writeLog("get hotel info "+ id);
    try {
      Hotel.where('id', id).where('is_active', 1).fetch().then(function (data) {
        if (data == null) {
          res.send({ message: "Hotel not exist!", resultCode: 1 });
        } else {
          res.json({ message: 'Get hotel successfully.', data: data.toJSON(), resultCode: 0 });
        }
      }).catch(function (err) {
        utils.writeLog(err);
        res.json({ message: 'Error!', resultCode: 1 });
      });
    } catch (e) {
      utils.writeLog(e);
      res.json({ message: 'Error!', resultCode: 1 });
    }
  },

  getListJobByHotelId:async function getListJobByHotelId(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    utils.writeLog('get list hotel');
    try {
      utils.writeLog('get list by hotel id');
      Job.where(qb => {
        qb.leftJoin('ds_job_type', function () {
            this.on('ds_job.job_type_id', 'ds_job_type.id');
        });
      }).where('hotel_id', 4)
      .fetchAll().then(function (data) {
        if (data == null) {
          res.send({ message: "Hotel not exist!", resultCode: 1 });
        } else {
          res.json({ message: 'Get hotel successfully.', data: data.toJSON(), resultCode: 0 });
        }
      }).catch(function (err) {
        utils.writeLog(err);
        res.json({ message: 'Error!', resultCode: 1 });
      });
    } catch (e) {
      utils.writeLog(e);
      res.json({ message: 'Error!', resultCode: 1 });
    }
  },

  jobBooking: async function jobBooking(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var job_id = req.headers.job_id;
    var user_id = req.headers.user_id;
    var email = req.headers.email;
    utils.writeLog("Job booking: user_id= "+user_id + ", job_id="+job_id);
    if (job_id == undefined || utils.isEmptyObject(job_id)) {
      res.json({ message: 'Please select job!', resultCode: 1 });
      return "";
    }
    if (user_id == undefined || utils.isEmptyObject(user_id)) {
      res.json({ message: 'User not null!', resultCode: 1 });
      return "";
    }

    try {
      var user = await getUserInfo(email);
      if(user == null){
        res.json({ message: 'User not valiable!', resultCode: 1 });
      }else if(user.toJSON().userPantsApproved == 1 && user.toJSON().userShoesApproved == 1){// check userPantsApproved, userShoesApproved = 1 to book job
        var job = await getJobValiable(job_id);
        if(job == null){
          res.json({ message: 'Job not valiable!', resultCode: 1 });
        }
        if(job.toJSON().current_slot >= job.toJSON().slot){
          res.json({ message: 'Job full slot!', resultCode: 1 });
          return "";
        }
        var allJob = await getAllJobsByUserId(job_id, user_id);
        utils.writeLog('allJob='+allJob);
        if(allJob != null){
          res.json({ message: 'Job booked!', resultCode: 1 });
          return "";
        }
        // Insert AllJobs
        new AllJobs({
          job_id: job_id, user_id: user_id, status: 0, workTime_confirmed: 0, created_at: new Date(), updated_at: new Date()
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });

        res.json({ message: 'Book job successfull!', resultCode: 0 });
      }else{
        res.json({ message: 'User not confirm userPantsApproved, userShoesApproved!', resultCode: 1 });
      }    
    } catch (e) {
      res.json({ resultCode: 1, message: 'Fail'});
      utils.writeLog("Error book job");
      return "";
    }
  },

  hotelBooking: async function hotelBooking(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var job_id = req.headers.job_id;
    var email = req.headers.email;
    utils.writeLog("Job booking: email= "+email + ", job_id="+job_id);
    if (job_id == undefined || utils.isEmptyObject(job_id)) {
      res.json({ message: 'Please select job!', resultCode: 1 });
      return "";
    }

    try {
      var user = await getUserInfo(email);
      
      if(user == null){
        res.json({ message: 'User not valiable!', resultCode: 1 });
      }else{
        var user_id = user.toJSON().id;
        var job = await getJobValiable(job_id);
        if(job == null){
          res.json({ message: 'Job not valiable!', resultCode: 1 });
        }
        if(job.toJSON().current_slot >= job.toJSON().slot){
          res.json({ message: 'Job full slot!', resultCode: 1 });
          return "";
        }
        var allJob = await getAllJobsByUserId(job_id, user_id);
        utils.writeLog('allJob='+allJob);
        if(allJob != null){
          res.json({ message: 'Job booked!', resultCode: 1 });
          return "";
        }
        // Insert AllJobs
        new AllJobs({
          job_id: job_id, user_id: user_id, status: 0, workTime_confirmed: 0, created_at: new Date(), updated_at: new Date()
        }).save().then((model) => {
        }).catch(function (err) {
          res.json({ message: 'Error!', resultCode: 1 });
        });

        res.json({ message: 'Book job successfull!', resultCode: 0 });
      }    
    } catch (e) {
      res.json({ resultCode: 1, message: 'Fail'});
      utils.writeLog("Error book job");
      return "";
    }
  },

  jobCancel: async function jobCancel(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var id = req.headers.id;
    if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
      res.json({ message: 'Id not null!', resultCode: 1 });
      return "";
    }

    try {
      var job_id = await cancelAllJobs(id);
      utils.writeLog("job_id cancel2="+job_id);

      if(job_id != false && Number(job_id) > 0){
        // var job = await getJobValiable(job_id);
        // // Update current_slot on ds_job
        // var sql = {};
        // sql['current_slot'] = job.toJSON().current_slot - 1;
        // sql["updated_at"] = new Date();
        // await job.save(sql).then(function (row) {
        //   utils.writeLog("Update Cancel Job="+job_id +", current_slot="+sql['current_slot']);
        // });

        res.json({ message: 'Cancel job successfully.', resultCode: 0 });
      }else{
        res.json({ resultCode: 1, message: 'Fail cancel'});
      }
    } catch (e) {
      res.json({ resultCode: 1, message: 'Fail'});
      utils.writeLog("Error cancel job");
    }
  },

  myJob: async function myJob(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var user_id = req.headers.user_id;
    var status = req.headers.status;
    if (req.headers.user_id == undefined || utils.isEmptyObject(req.headers.user_id)) {
      res.json({ message: 'UserId not null!', resultCode: 1 });
      return "";
    }
    if (status == undefined || utils.isEmptyObject(status)) {
      status = null;
    }

    try {
      var data = await getMyJob(user_id, status);

      res.json({ resultCode: 0, message: 'Fail cancel', data: data});

    } catch (e) {
      res.json({ resultCode: 1, message: 'Fail'});
      utils.writeLog("Error cancel job");
    }
  },

  updateStatusAllJob: async function updateStatusAllJob(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var id = req.headers.id;
    var status = req.headers.status;

    if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
      res.json({ message: 'Job Id not null!', resultCode: 1 });
      return "";
    }
    if (req.headers.status == undefined || utils.isEmptyObject(req.headers.status)) {
      res.json({ message: 'Status not null!', resultCode: 1 });
      return "";
    }

    try {
      var job = await getAllJobsById(id);
      if(job != false){
        // Update status
        var sql = {};
        sql['status'] = status;
        sql["updated_at"] = new Date();
        await job.save(sql).then(function (row) {
          utils.writeLog("Update Status AllJob="+id +", status="+status);
        });

        res.json({ message: 'Update all job successfully.', resultCode: 0 });
      }else{
        res.json({ resultCode: 1, message: 'Get All Job Failed'});
      }
    } catch (e) {
      res.json({ resultCode: 1, message: 'Fail'});
      utils.writeLog("Error cancel job");
    }
  },
};

