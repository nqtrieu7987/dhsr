var config = require('.././config.json');
var utils = require(".././Utils.js");
var RedisClustr = require('redis-clustr');
var redisAddress = config.redisPro;
var redis = new RedisClustr(redisAddress);
var db_config = config["mysql.10.25.80.*"];
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
var date = new Date();
var current_hour = date.getHours();
// utils.writeLog(current_hour)
var code = "hungtd";
var username = "lovanson8";
// utils.writeLog(redis.hget(config.keyCode, code));
async function getRedisHash(key, value) {
    return new Promise(resolve => {
        redis.hget(key, value, function (err, obj) {
            resolve(obj);
        });
    });
}
async function asyncCall() {    
    var result = await getRedisHash(config.keyCode, code);
    utils.writeLog(result);
    // expected output: 'resolved'
}
asyncCall();
// function buildSqlActiveCodeSmartUserOld(obj, code, user) {
//     var sql = {};
//     sql["code_smart"] = user.toJSON().code_smart + "," + code;
//     sql["time_active_code_smart"] = user.toJSON().time_active_code_smart + ","
//       + new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 19).replace('T', ' ');
//     var startTime = user.toJSON().end_time_smart.getTime();
//     if (startTime < new Date().getTime()) {
//       startTime = new Date().getTime();
//     }
//     var time = startTime + obj * 86400000;
//     sql["end_time_smart"] = new Date(time);
//     return sql;
//   }
// var code = "chuyennd";
// var obj = "365";
// User.where('username', username).fetch().then(function (user) {
//     utils.writeLog(user.toJSON().code_smart);
//     var sql = buildSqlActiveCodeSmartUserOld(obj, code, user);
//     user.save(sql, { patch: true })
//         .then(function (row) {
//             utils.writeLog(row.toJSON().code_smart);
//         });
// });