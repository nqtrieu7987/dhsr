const fs = require('fs');
var config = require('../config.json');
var RedisClustr = require('redis-clustr');
var _ = require('underscore');
var dateFormat = require('dateformat');
var redisAddress = config.redisLab;
var redis = new RedisClustr(redisAddress);
var utils = require("../Utils");

async function getRedisHash(key, value) {
    return new Promise(resolve => {
        redis.hget(key, value, function (err, obj) {
        resolve(obj);
    });
});
}
async function extractPayloadFromToken(jwtToken) {
    return new Promise(resolve => {
        jwt.verify(jwtToken, cert, function (err, payload) {
        resolve(payload);
    });
});
}

function countLesson(object) {
    var count = 0;
    for(var i =0; i< object.length; i ++){
        if(object[i].startsWith('L') && object[i].length >= 6){
            count ++;
        }
    }
    return count;
}
async function getAllKeyInSortedSet(key) {
    return new Promise(resolve => {
        redis.zrange(key, 0, -1, 'withscores', function (err2, members) {
        var lists = _.groupBy(members, function (a, b) {
            return Math.floor(b / 2);
        });
        lists = _.toArray(lists);
        var result = [];
        for (var i = 0; i < lists.length; i++) {
            result.push(lists[i][0]);
        }
        resolve(result);
    });
});
}


async function asyncCall() {
    var folderLogFB = 'E:/code/log/';
    var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var date = d.getDate();
    month = month < 10 ? '0'+month: month;
    date = date < 10 ? '0'+date: date;
    var dir = folderLogFB + month + "/";
    var fileLog = dir + year;
    fileLog += month;
    fileLog += date;
    fileLog += ".log";
    utils.writeLog(fileLog);

    var line = { "userid": "ducbot271", "type": "fb", "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
    line = JSON.stringify(line) + "\n";
    if (!fs.existsSync(fileLog)) {
        // check neu chua co folder tao folder
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir);
            utils.writeLog("Create folder:" + dir)
        }
       fs.appendFile(fileLog, line, (err) => {
            // change quyen cho file
           fs.chmodSync(fileLog, 0777);
           utils.writeLog("Create and change permisson file:" + fileLog)
       });
    } else {
        fs.appendFile(fileLog, line, (err) => {
            if (err) {
                utils.writeLog('Write log error!');
            }
            //utils.writeLog('Write log success!');
        });
    }
}
asyncCall();