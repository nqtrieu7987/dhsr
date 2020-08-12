const fs = require('fs');
var utils = require("./Utils.js");
var dateFormat = require('dateformat');
var config = require('./config.json');
var RedisClustr = require('redis-clustr');
var ip = require("ip");
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);
var sysdata;
var folderLog = "/var/www/log/web/";
var folderLogFB = "/var/www/log/connectgmailfb/";
var edupiaLink = "https://edupia.vn/app/";
module.exports = {
    writeLog: function writeLog(username, url, point) {
        var d = new Date();
        var year = d.getFullYear();
        var month = d.getMonth() + 1;
        var date = d.getDate();
        var dir = folderLog + month + "/";
        var fileLog = dir + year;
        fileLog += month;
        fileLog += date;
        
        fileLog += ".app.log";
        if (sysdata == undefined) {
            redis.hget(config.keyEduca, config.keyEduca, function (err, obj) {
                try {
                    sysdata = JSON.parse(obj);
                    if (sysdata.exerciseId2Lesson[url] != undefined) {
                        url = edupiaLink + "lesson/" + sysdata.exerciseId2Lesson[url];
                    } else {
                        url = edupiaLink;
                    }

                    if(point != ""){
                        var exerciseid = url;
                        utils.writeLog("exerciseid="+exerciseid);
                        var line = { "id": username, "url": url, "exerciseid": exerciseid, "point": point, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
                    }else{
                        var line = { "id": username, "url": url, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
                    }
                    // add a line to a lyric file, using appendFile
                    
                    line = JSON.stringify(line) + "\n";
                    //utils.writeLog("Write log get system data.");
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
                } catch (e) {
                    utils.writeLog("Error get sys");
                }
            });
        } else {
            var exerciseid;
            if (sysdata.exerciseId2Lesson[url] != undefined) {
                exerciseid = url;
                url = edupiaLink + "lesson/" + sysdata.exerciseId2Lesson[url];
            } else {
                url = edupiaLink;
            }

            if(point != ""){
                var line = { "id": username, "url": url, "exerciseid": exerciseid, "point": point, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
            }else{
                var line = { "id": username, "url": url, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
            }
            // add a line to a lyric file, using appendFile
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
    },
    writeLogVideo: function writeLogVideo(username, url) {
        var d = new Date();
        var year = d.getFullYear();
        var month = d.getMonth() + 1;
        var date = d.getDate();
        var fileLog = folderLog;
        fileLog += month;
        fileLog += "/";
        fileLog += year;
        fileLog += month;
        fileLog += date;
        fileLog += ".log";
        if (sysdata == undefined) {
            redis.hget(config.keyEduca, config.keyEduca, function (err, obj) {
                try {
                    sysdata = JSON.parse(obj);
                    if (sysdata.videoId2Lesson[url] != undefined) {
                        url = edupiaLink + "lesson/" + sysdata.videoId2Lesson[url];
                    } else {
                        url = edupiaLink;
                    }
                    // add a line to a lyric file, using appendFile
                    var line = { "id": username, "url": url, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
                    line = JSON.stringify(line) + "\n";
                    utils.writeLog("Write log get system data.")
                    fs.appendFile(fileLog, line, (err) => {
                        if (err) throw err;
                        //utils.writeLog('Write log success!');
                    });
                } catch (e) {
                    utils.writeLog("Error get sys");
                }
            });
        } else {
            if (sysdata.videoId2Lesson[url] != undefined) {
                url = edupiaLink + "lesson/" + sysdata.videoId2Lesson[url];
            } else {
                url = edupiaLink;
            }
            // add a line to a lyric file, using appendFile
            var line = { "id": username, "url": url, "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
            line = JSON.stringify(line) + "\n";
            fs.appendFile(fileLog, line, (err) => {
                if (err) throw err;
                //utils.writeLog('Write log success!');
            });
        }

    },

    writeLogConnFacebook: function writeLogConnFacebook(username) {
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

        var line = { "userid": username, "type": "fb", "datetime": dateFormat(d, "yyyy-mm-dd HH:MM:ss") };
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
            utils.writeLog('Write log connect Facebook success!');
        });
        } else {
            fs.appendFile(fileLog, line, (err) => {
                if (err) {
                    utils.writeLog('Write log error!');
                }
            });
        }

    }
};
