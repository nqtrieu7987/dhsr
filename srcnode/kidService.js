var config = require('./config_kid.json');
const utf8 = require('utf8');
var utils = require("./Utils");
var FtpTools = require('./controllers/FtpTools');
var ip = require("ip");
var fs = require('fs');
var RedisClustr = require('redis-clustr');
var jwt = require('jsonwebtoken');
var cert = fs.readFileSync('./educa.vn.key');  // get private key
var _ = require('underscore');
var request = require('request');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);

async function getRedisHash(key, value) {
    return new Promise(resolve => {
        redis.hget(key, value, function (err, obj) {
            resolve(obj);
        });
    });
}

module.exports = {
    getMarketingPackage: async function getMarketingPackage(req, res) {
        req.header("Content-Type", "text/html; charset=utf-8");
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        var packId = req.headers.packid;
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        if (packId == undefined || utils.isEmptyObject(packId)) {
            redis.hget(config.keyEduca, config.keyMarketingPackge, function (err, obj) {
                try {
                    var array = JSON.parse(obj);
                    var result = [];
                    for (var i = 0; i < array.length; i++) {
                        var json = array[i];
                        if (json.type == 2) {
                            var content = JSON.parse(json.content);
                            let arrayOfObjects = Object.keys(content).map(key => {
                                let ar = content[key]
                                // Apppend key if one exists (optional)
                                ar.key = key
                                return ar
                            })
                            json.content = arrayOfObjects;
                            result.push(json);
                        }
                    }
                    res.send(result);
                } catch (e) {
                    res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                    utils.writeLog("Error get marketingpackage");
                }
            });
        } else {
            redis.hget(config.keyMarketingPackge, packId, function (err, obj) {
                try {
                    var json = JSON.parse(obj);
                    var content = JSON.parse(json.content);
                    let arrayOfObjects = Object.keys(content).map(key => {
                        let ar = content[key]
                        // Apppend key if one exists (optional)
                        ar.key = key
                        return ar
                    })
                    json.content = arrayOfObjects;
                    res.send(json);
                } catch (e) {
                    res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                    utils.writeLog("Error get marketingpackage");
                }
            });
        }
    },


    getListCourseSmart: async function getListCourseSmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        utils.writeLog("Get courses smart data.");
        redis.hget(config.keyEduca, config.keyCourseSmartApp, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                }
                data = JSON.parse(data);
                var result = [];
                var values = Object.values(data);
                for (var i = 0; i < values.length; i++) {
                    result.push(values[i]);
                }
                res.send(result);
            } catch (e) {
                res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                utils.writeLog("Error get courses");
            }
        });
    },

    getListProductSmart: async function getListProductSmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        utils.writeLog("Get product data.");
        redis.hget(config.keyEduca, config.keyProductSmartPhonic, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                }
                res.send(data);
            } catch (e) {
                res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                utils.writeLog("Error get product smartphonic");
            }
        });
    },

    getLessonSmart: async function getLessonSmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
            res.json({ message: 'Id invalid!', resultCode: 0 });
            return "";
        }
        var id = req.headers.id;
        utils.writeLog("Get lesson data: " + id);

        var isSource = false;
        if (id.trim().length > 4) {
            isSource = true;
        }
        redis.hget(config.keyLessonSmartPhonic, id, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                    res.send({ errorCode: 2, message: 'Fail', data: "{}" });
                } else {
                    if (isSource) {
                        redis.hget(config.keyLessonSmartPhonic, data, function (err, obj) {
                            try {
                                var data = obj;
                                if (data == null) {
                                    data = "{}";
                                    res.send({ errorCode: 2, message: 'Fail', data: "{}" });
                                } else {
                                    var result = JSON.parse(data);
                                    var games = result.data.game;
                                    if (games != undefined) {
                                        var values = Object.values(games);
                                        for (var i = 0; i < values.length; i++) {
                                            if (isServerLab) {
                                                values[i].appPathFile = values[i].appPathFile.replace("smartphonic_app", "smartphonic_app_attt");
                                            }
                                        }
                                        result.data.game = games;
                                    }
                                    res.send(result);
                                }
                            } catch (e) {
                                res.send({ errorCode: 0, message: 'Fail', data: "{}" });
                                utils.writeLog("Error get lesson");
                            }
                        });
                    } else {
                        res.send(data);
                    }
                }
            } catch (e) {
                res.send({ errorCode: 0, message: 'Fail', data: "{}" });
                utils.writeLog("Error get lesson");
            }
        });
    },

    getLessonSmart: async function getLessonSmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        redis.hget(config.keyEduca, config.keySmartSysData, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                }
                res.send(data);
            } catch (e) {
                res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                utils.writeLog("Error get smart sys");
            }
        });
    },

    sysdatasmart: async function sysdatasmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        redis.hget(config.keyEduca, config.keySmartSysData, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                }
                res.send(data);
            } catch (e) {
                res.json({ errorCode: 0, message: 'Fail', data: "{}" });
                utils.writeLog("Error get smart sys");
            }
        });
    },

    userInfoSmart: async function userInfoSmart(req, res) {
        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        utils.writeLog(new Date() + " Get info for user smart: " + req.headers.username + " token: " + req.headers.token);
        var username = req.headers.username;
        if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
            res.json({ message: 'Input username error!', resultCode: 0 });
            return "";
        }
        try {
            username = username.toLowerCase();
        } catch (error) { }
        redis.hget(config.keyUserSmartInfo, username, function (err, obj) {
            try {
                var data = obj;
                if (data == null) {
                    data = "{}";
                    // push queue userinfo
                    redis.rpush(config.EdupiaUserInfoQueues, username);
                }
                var json = JSON.parse(data);
                delete json.exerciseHistory;
                res.send(json);
            } catch (e) {
                utils.writeLog("Error get info smart for user: " + username);
                res.json("{}");
            }
        });
    },
};
