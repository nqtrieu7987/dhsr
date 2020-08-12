var utils = require("./Utils.js");
var dbo;
var db;
var config = require('./config.json');
var ip = require("ip");
var mysqlTool = require("./MysqlTool.js");

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
if (ip.address().includes("172.25.80")) {
  db_config = config.educadbPro;
}
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
  tableName: 'edu_users'
});
var UserAI = bookshelf.Model.extend({
  tableName: 'edu_users_ai'
});

var UserPackage = bookshelf.Model.extend({
  tableName: 'edu_users_package'
});
var EduPartner = bookshelf.Model.extend({
  tableName: 'edu_partner'
});
// crm db config 
var db_config_crm = config.crmDbLab;
if (ip.address().includes("172.25.80")) {
  db_config_crm = config.crmDbPro;
}
var knexCrm = require('knex')({
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
  connection: db_config_crm
});
var bookshelfCrm = require('bookshelf')(knexCrm);
var CrmOrder = bookshelfCrm.Model.extend({
  tableName: 'crm_order'
});
var CrmPackage = bookshelfCrm.Model.extend({
  tableName: 'crm_package'
});
// end crm db config 
var mapPackageDay = {};
async function getPackage(packageId) {
  utils.writeLog("Get packageId: " + packageId);
  return new Promise(resolve => {
    CrmPackage.where('id', packageId).fetch().then(function (pack) {
      console.log("pack="+pack);
      if (pack != null) {
        resolve({ day: pack.toJSON().day, type: pack.toJSON().type });
      }else{
        resolve(null);
      }
    });
  });
}
async function getOder(code) {
  return new Promise(resolve => {
    CrmOrder.where('code', code).fetch().then(function (oder) {
      resolve(oder);
    });
  });
}
async function getAttachInfo(code) {
  var oder = await getOder(code);
  var result = {};
  if (oder != null) {
    var item = oder.toJSON().package_item;
    if (item != null) {
      result = mapPackageDay[item];
      if (result == null) {
        result = await getPackage(item);
        if(result != null){
          mapPackageDay[item] = result;
        }else{
          return null;
        }
      }
    }
  }
  return result;
}
async function getUserInfo(username) {
    return new Promise(resolve => {
      User.where('username', username).fetch().then(function (user) {
        resolve(user);
      });
    });
  }
  async function updateUserTangKem(user, sql) {
    return new Promise(resolve => {
        try{
        user.save(sql)
          .then(function (row) {
            utils.writeLog("tangkem thanh cong");
          });
        } catch (e) {
          utils.writeLog("error tangkem="+ e);
        }
        resolve(user);
    });
  }
  function buildSqlActiveCodeUserNew(obj, code, codeintro, user) {
    var sql = {};
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


async function activeTangkem(code, username) {
    var tangkem = await getAttachInfo(code);
    var codeTangkem = "tangkem";
    
    if (tangkem == null || tangkem.day == null || tangkem.type == null || tangkem.day < 0) {
      return;
    }
    var user = await getUserInfo(username);
    var sql = {};
    sql["code_smart"] = user.toJSON().code_smart + "," + codeTangkem;
    sql["time_active_code_smart"] = user.toJSON().time_active_code_smart + ","
      + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
    var startTime = user.toJSON().end_time_smart.getTime();
    if (startTime < new Date().getTime()) {
      startTime = new Date().getTime();
    }
    var time = startTime + tangkem.day * 86400000;
    sql["end_time_smart"] = new Date(time);


    // sql = {"code_smart":"tangkem","start_time_smart":"2019-09-13T07:16:28.002Z","time_active_code_smart":"2019-09-13 14:16:28","end_time_smart":"2020-12-06T07:16:28.002Z"};
    // sql = JSON.parse(sql);
    // switch (tangkem.type) {
    //   case 1:// tang kem tieu hoc
    //     var sql;
    //     if (user.toJSON().code == null || utils.isEmptyObject(user.toJSON().code)) {
    //       sql = buildSqlActiveCodeUserNew(tangkem.day, codeTangkem, "", user);
    //     } else {
    //       sql = buildSqlActiveCodeUserOld(tangkem.day, codeTangkem, user, "");
    //     }
    //     break;
    //   case 3: // tang kem kid
    //     if (user.toJSON().code_smart == null || utils.isEmptyObject(user.toJSON().code_smart)) {
    //       sql = buildSqlActiveCodeSmartUserNew(tangkem.day, codeTangkem);
    //     } else {
    //       sql = buildSqlActiveCodeSmartUserOld(tangkem.day, codeTangkem, user);
    //     }
    //     break;
    //   case 4: // tang kem cap 2
    //     if (user.toJSON().second == null || utils.isEmptyObject(user.toJSON().second)) {
    //       sql = buildSqlActiveCodeUserEsNew(tangkem.day, codeTangkem);
    //     } else {
    //       sql = buildSqlActiveCodeUserEsOld(tangkem.day, codeTangkem, user);
    //     }
    //     break;
    //   default:
    //     break;
    // }
    if (sql != null) {
      console.log("username="+username+" tangkem day="+tangkem.day+ " tangkem type="+tangkem.type+ " sql="+JSON.stringify(sql));
      var active = await updateUserTangKem(user, sql);
    }
    utils.writeLog("Active tang kem: " + code + " -> " + sql)
  }

  async function test() {
    await activeTangkem('9zs5sv', 'lovanson111');
  }

  test();