var http = require('http');
var request = require('request');
var utils = require("../Utils.js");
var ip = require("ip");
var config = require('../config.json');
var RedisClustr = require('redis-clustr');
var redisAddress = config.redisLab;
// var redisAddress = config.redisPro;
var isServerLab = true;
var jwt = require('jsonwebtoken');
var writelog = require("../WriteLog.js");
var words = require('../words.json');
var mysqlTool = require("../MysqlTool.js");
var avatar_upload = '/var/www/log/avatar/';

// if (ip.address().includes("172.25.80")) {
//     redisAddress = config.redisPro;
//     isServerLab = false;
// }
// if (ip.address().includes("103.216.121")) {
//     redisAddress = config.redisPro;
//     isServerLab = false;
// }

var redis = new RedisClustr(redisAddress);
async function getRedisHash(key, value) {
    return new Promise(resolve => {
        redis.hget(key, value, function (err, obj) {
            resolve(obj);
        });
    });
}
async function test() {
    // var phone = "989335466";
    // phone = utils.nomalizePhoneNumber(phone);
    // var key = config.phone2extention + "." + Math.abs(utils.hashCode(phone) % 16)
    // var ext = await getRedisHash(key, phone);
    // if (ext == null) {
    //     ext = 0;
    // }
    // console.log(ext);
    // redis.hset(key, phone, "201");
    // var ext = await getRedisHash(key, phone);
    // console.log(ext);
    // redis.rpush(config.EdupiaUserInfoQueues, "tiennx");
    // redis.rpush(config.EdupiaUserInfoQueues, "chuyennd");
    // console.log("Done");
    redis.hget(config.keyEduca, config.keyCourseApp, function (err, obj) {
        try {
            var data = obj;
            if (data == null) {
                data = "{}";
            }
            var json = JSON.parse(data);
            if (json.length > 3) {
                json.splice(3,3)
            }
            console.log(json);
        } catch (e) {
            res.json({ errorCode: 0, message: 'Fail', data: "{}" });
            utils.writeLog("Error get courses");
        }
    });
}

test();


// var token = "dR1145fvFTg:APA91bEwI07FqwF6JPyACPxKBYhvY4V53j8ux9gFs05JbHDC9vsYri8oysCBvF9h8_ADhJo6K6Uj61ic-xtLfNr0Hx00TMJ6v3jiKSJaUjj9Ojw6mCGH17YlMSirW6Kh9t9SRmO_4AFf";
// var auth = "key=AIzaSyBaiP_7RmO3M0FZYZZ5kjYpwFa3MsT7alw";
// var url = "https://iid.googleapis.com/iid/info/" + token;
// request.get({
//     url: url,
//     headers: {
//         "Authorization": auth
//     }
// }, function (error, response, body) {
//     utils.writeLog('body : ', body);
// });
// var username = "webtest015"
// push queue userinfo
// redis.rpush(config.EdupiaUserInfoQueues, username);
// redis.rpush("redis.key.educa.es.userinfo.queues", username);
// redis.hget(config.keyEduca, config.keyProductData, function (err, obj) {
//     try {
//         var obj = obj;
//         if (obj == null) {
//             obj = "{}";
//         }
//         obj = JSON.parse(obj);
//         var data = obj.data;
//         var values = Object.values(data);
//         redis.hgetall(config.keyLessonData, function (err, lessons) {
//             values[0].unit[0].info.lessonFree = ["L3U01B01", "L3U01B02"];
//             for (var i = 0; i < values.length; i++) {
//                 for (var j = 0; j < values[i].unit.length; j++) {
//                     values[i].unit[j].info.name = values[i].unit[j].info.name.split(":")[1].trim();
//                     values[i].unit[j].info.nameEn = values[i].unit[j].info.nameEn.split(":")[1].trim();
//                     if (values[i].unit[j].voca.length > 0 && values[i].unit[j].voca[0].source == "L3U01B01") {
//                         values[i].unit[j].voca[0].isFree = true;
//                         console.log(values[i].unit[j].voca[0]);
//                     }
//                     if (values[i].unit[j].lecture.length > 0 && values[i].unit[j].lecture[0].source == "L3U01B02") {
//                         values[i].unit[j].lecture[0].isFree = true;
//                         console.log(values[i].unit[j].lecture[0]);
//                     }
//                 }

//                 for (var rv = 0; rv < values[i].unitreview.length; rv++) {
//                     var datarv = JSON.parse(lessons[values[i].unitreview[rv].id]);
//                     //utils.writeLog(datarv.data.lesson);
//                     values[i].unitreview[rv] = datarv.data.lesson;
//                 }
//             }

//             obj.data = data;
//             // console.log(obj);

//         });
//     } catch (e) {
//         console.log({ errorCode: 0, message: 'Fail', data: "{}" });
//         utils.writeLog("Error get product", e);
//     }
// });