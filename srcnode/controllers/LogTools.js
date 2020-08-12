var utils = require("./Utils.js");
var fs = require('fs');
var config = require('./config.json');
var ip = require("ip");

var RedisClustr = require('redis-clustr');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
if (ip.address().includes("103.216.121")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);
var FOLDER_LOG = '/var/www/log/statics/';
function hashCode(s) {
    for (var i = 0, h = 0; i < s.length; i++)
        h = Math.imul(31, h) + s.charCodeAt(i) | 0;
    return h;
}
function genLogFoler(s) {
    return FOLDER_LOG + Math.abs(hashCode(s) % 1024) + '/'
}
// var redis = require('redis');
// var redisClient = redis.createClient({ host: '172.25.80.82', port: 6379 });
// redisClient.on('ready', function () {
//     utils.writeLog("Redis is ready");
// });
// redisClient.on('error', function () {
//     utils.writeLog("Error in Redis");
// });


function readFile(filename, processFile) {
    fs.readFile(filename, 'utf-8', function (err, content) {
        if (err) {
            utils.writeLog(err);
            return;
        }
        var lines = content.split("\n");
        var allLines = [];
        let totalMsg = 0;
        let totalSms = 0;
        for (var i = 0; i < lines.length; i++) {
            var line = lines[i];
            if (!utils.isEmptyObject(line)) {
                allLines.push(line);
                var json = JSON.parse(line);
                if (json.result === "0") {
                    totalMsg++;
                    totalSms += Number(json.length);
                }
            }
        }
        processFile(totalMsg, totalSms, allLines);
    });
}
function readFolder(folderName, processFolder) {
    fs.readdir(folderName, (err, files) => {
        var fileList = [];
        var fileNames = [];
        files.forEach(function (filename) {
            fileList.push(folderName + "/" + filename);
            fileNames.push(filename);
        });
        processFolder(fileList, fileNames);
    })
}
module.exports = {
    getLogByUser: function getLogByUser(req, res) {
        var username = req.headers.username;
        res.setHeader('Content-Type', 'application/json');
        //utils.writeLog("Get log for user:" + username);
        if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
            res.json({ message: 'Input username error!', resultCode: 0 });
            return "";
        }
        redis.hget(config.keyPhone2User, username, function (err, obj) {
            try {
                var data = obj;
                if (data != null && !utils.isEmptyObject(data)) {
                    username = obj;
                }
            } catch (e) {
            }
            var folderName = genLogFoler(username) + username;
            try {
                fs.readdir(folderName, (err, files) => {
                    res.json(files);
                })
            } catch (e) {
                res.json({ errorCode: 1, message: 'Fail', data: "{}" });
                utils.writeLog("Error get setting");
            }
        });

    },
    getAllUserLog: function getAllUserLog(req, res) {
        var username = req.headers.username;
        var date = req.headers.datetime;
        //utils.writeLog("Get log for user:" + username + " date: " + date);
        res.setHeader('Content-Type', 'application/json');
        if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
            res.json({ message: 'Input username error!', resultCode: 0 });
            return "";
        }
        if (req.headers.datetime == undefined || utils.isEmptyObject(req.headers.datetime)) {
            res.json({ message: 'Input datetime error!', resultCode: 0 });
            return "";
        }
        redis.hget(config.keyPhone2User, username, function (err, obj) {
            try {
                var data = obj;
                if (data != null && !utils.isEmptyObject(data)) {
                    username = obj;
                }
            } catch (e) {
            }
            var fileName = genLogFoler(username) + username + "/" + date;
            try {
                var file = fs.readFileSync(fileName, "utf8");
                var array = file.split("\n");
                res.json(array);
            } catch (e) {
                res.json({ errorCode: 1, message: 'Fail', data: "{}" });
                utils.writeLog("Error get setting");
            }
        });
    },
    getAllLogProfile: function getAllLogProfile(req, res) {
        //utils.writeLog("Get log profile:");
        res.setHeader('Content-Type', 'application/json');
        var file = fs.readFileSync(FOLDER_LOG + 'profile.info', "utf8");
        res.send(file);
    },
    findProfile: function findProfile(req, res) {
        var username = req.headers.username;
        utils.writeLog("Find profile:" + username);
        res.setHeader('Content-Type', 'application/json');
        var file = fs.readFileSync(FOLDER_LOG + 'profile.info', "utf8");
        file = JSON.parse(file);
        var array = [];
        for (var j = 0; j < file.length; j++) {
            if ((file[j].phone != null && file[j].phone.includes(username)) || file[j].username.includes(username)) {
                array.push(file[j]);
            }
        }
        res.send(array);
    },
    getSmsInMonth: function getSmsInMonth(req, res) {
        var month = req.headers.month;
        //utils.writeLog("Get sms in month:" + month);
        res.setHeader('Content-Type', 'application/json');
        var folderName = '/var/www/log/sms/' + month;
        try {
            readFolder(folderName, (files, fileNames) => {
                var totalMsgSent = 0;
                var totalSmsSent = 0;
                var counter = 0;
                for (var i = 0; i < files.length; i++) {
                    readFile(files[i], (totalMsg, totalSms, allLines) => {
                        totalMsgSent += totalMsg;
                        totalSmsSent += totalSms;
                        counter++;
                        if (counter === files.length) {
                            res.json({ "totalMsg": totalMsgSent, "totalSms": totalSmsSent, "files": fileNames });
                        }
                    });
                }
            });
        } catch (e) {
            res.json({ errorCode: 1, message: 'Fail', data: "{}" });
            utils.writeLog("Error get sms log");
        }
    },
    getSmsWithPhone: function getSmsWithPhone(req, res) {
        var month = req.headers.month;
        var phone = req.headers.phone;
        //utils.writeLog("Get sms in month with phone:" + phone);
        res.setHeader('Content-Type', 'application/json');
        var folderName = '/var/www/log/sms/' + month;
        try {
            readFolder(folderName, (files, fileNames) => {
                var counter = 0;
                var array = [];
                for (var i = 0; i < files.length; i++) {
                    readFile(files[i], (totalMsg, totalSms, allLines) => {
                        counter++;
                        for (var i = 0; i < allLines.length; i++) {
                            var line = allLines[i];
                            var json = JSON.parse(line);
                            if (json.phone === phone) {
                                array.push(line);
                            }
                        }
                        if (counter === files.length) {
                            res.json({ "totalMsg": array.length, "details": array });
                        }
                    });
                }
            });
        } catch (e) {
            res.json({ errorCode: 1, message: 'Fail', data: "{}" });
            utils.writeLog("Error get sms log");
        }
    },
    getSmsInDay: function getSmsInDay(req, res) {
        var day = req.headers.day;
        //utils.writeLog("Get sms in day:" + day);
        res.setHeader('Content-Type', 'application/json');
        var month = day.substring(0, 6);
        var fileName = '/var/www/log/sms/' + month + "/" + day + ".log";
        try {
            readFile(fileName, (totalMsg, totalSms, allLines) => {
                res.json({ "totalMsg": totalMsg, "totalSms": totalSms, "lines": allLines });
            });
        } catch (e) {
            res.json({ errorCode: 1, message: 'Fail', data: "{}" });
            utils.writeLog("Error get sms log");
        }
    }

};