var mongo = require('mongodb');
const fs = require('fs');
var utils = require("./Utils.js");
var MongoClient = mongo.MongoClient;
var dbo;
var db;
var config = require('./config.json');
var ip = require("ip");
var ipGenCodePlusValids = ["113.190.234.17"];

var RedisClustr = require('redis-clustr');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
if (ip.address().includes("103.216.121")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);

var mongoAddress = config.mongoLab;
if (ip.address().includes("172.25.80")) {
    mongoAddress = config.mongoPro;
}
if (ip.address().includes("103.216.121")) {
    mongoAddress = config.mongoPro;
}
MongoClient.connect('mongodb://' + 'educa' + ':' + 'Educa_2018**' + '@' + mongoAddress + ':27017' + '/' + 'educa', { useNewUrlParser: true }, function (err, dbresult) {
    // var mongoAddress = "172.25.80.83";
    // MongoClient.connect('mongodb://' + 'user_mongo' + ':' + 'Educa_2018**' + '@' + mongoAddress + ':27017' + '/' + 'educa', function(err, dbresult) {
    if (err)
        utils.writeLog(err);
    else {
        utils.writeLog('Mongo Connected!');
        db = dbresult;
        dbo = db.db("educa");
    }
});
function closeConnection() {
    db.close();
}

async function checkLiveclassExist(userid, liveclassid) {
    return new Promise(resolve => {
        var collection = dbo.collection('liveclass');
        var query = { userid: userid, liveclassid: liveclassid };
        utils.writeLog("check live class exist");
        collection.find(query).toArray((err, result) => {
            if (err) throw err;
            if (result != null || !utils.isEmptyObject(result)) {
                resolve(result);
            } else {
                resolve(null);
            }
        });
    });
}

async function checkPhoneSMSMarketingExist(phone) {
    return new Promise(resolve => {
        var collection = dbo.collection('sms_marketing');
        var query = { $or: [{ phone: phone }, { code: phone }] };
        collection.find(query).toArray((err, result) => {
            if (err) throw err;
            if (result != null || !utils.isEmptyObject(result)) {
                resolve(result);
            } else {
                resolve(null);
            }
        });
    });
}

// Edupia Plus
var checkGenPlusCodeUser = async function (username, password) {
    return new Promise(resolve => {
        redis.hget("redis.key.educa.gen.plus.code.user", username, function (err, obj) {
            try {
                if (password == obj) {
                    resolve(true);
                }
                resolve(false);
            } catch (e) {
                resolve(false);
                utils.writeLog("Error check gen plus code user");
            }
        });
    });
}

var checkCodeRedis = async function (code) {
    return new Promise(resolve => {
        redis.hget("redis.key.edupiakid.makichhoat", code, function (err, obj) {
            try {
                if (obj == null) {
                    redis.hget("key.user.code.all", code, function (err, obj) {
                        try {
                            if (obj == null) {
                                redis.hget("redis.key.educa.es.makichhoat", code, function (err, obj) {
                                    try {
                                        if (obj == null) {
                                            resolve(true);
                                        } else  {
                                            resolve(false);
                                        }
                                    } catch (e) {
                                        resolve(false);
                                        utils.writeLog("Error check gen plus code user");
                                    }
                                });
                            } else {
                                resolve(false);
                            }
                        } catch (e) {
                            resolve(false);
                            utils.writeLog("Error check gen plus code user");
                        }
                    });
                } else {
                    resolve(false);
                }
            } catch (e) {
                resolve(false);
                utils.writeLog("Error check gen plus code user");
            }
        });
    });
}

var incrPlusSerial = async function () {
    return new Promise(resolve => {
        redis.incr("key.vrtgo.code.serial.number", function (err, obj) {
            resolve(obj);
        });
    });
}

async function checkCodeEdupiaPlus(code) {
    return new Promise(resolve => {
        var collection = dbo.collection('code_edupia_plus');
        var query = { code: code };
        collection.find(query).toArray((err, result) => {
            if (err) throw err;
            if (result != null || !utils.isEmptyObject(result)) {
                resolve(result);
            } else {
                resolve(null);
            }
        });
    });
}

async function genCodePlus() {
    var codeNew;
    var serial;
    var codeDocs;
    while (true) {
        codeNew = utils.genANewCode();
        utils.writeLog("Generate a new code: " + codeNew);
        // Kiem tra xem code co ton tai trong Mongo chua
        var checkMongo = await checkCodeEdupiaPlus(codeNew);
        utils.writeLog("checkMongo: " + checkMongo);
        if (checkMongo == null || checkMongo == '') {
            // Neu chua ton tai kiem tra tiep trong redis
            var checkRedis = await checkCodeRedis(codeNew);
            utils.writeLog("checkRedis: " + checkRedis);
            if (checkRedis) {
                serial = await genSerial();
                codeDocs = {code: codeNew, serial: serial, days: 30, plus: 1, source: 'online'};
                break;
            }
        }
        utils.writeLog("Code is conflict: " + codeNew);
    }
    // Insert code to mongo
    insertPlusCode(codeDocs);
    utils.writeLog("Generate code success: " + codeNew);
    return {code: codeNew, serial: serial};
}

async function genSerial() {
    var incrValue = await incrPlusSerial();
    var serial = "" + incrValue;
    while (serial.length < 8) {
        serial = "0" + serial;
    }
    return "VRTGO" + serial;
}

async function insertPlusCode(obj) {
    var collection = dbo.collection('code_edupia_plus');  // get reference to the collection
    collection.insertOne(obj, function (err, res) {
        if (err) throw err;
        utils.writeLog("Insert code edupia plus: " + JSON.stringify(obj));
    });
}

module.exports = {
    deleteUserHistory: function deleteUserHistory(userName) {
        var collection = dbo.collection('customers');  // get reference to the collection
        var queryDelete = { userid: userName };
        collection.deleteMany(queryDelete, function (err, res) {
            if (err) throw err;
        });
    },
    addCoinForUser: function addCoinForUser(obj) {
        var collection = dbo.collection('customers');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            // utils.writeLog("Insert customer coin:")
            // utils.writeLog(obj);
        });
    },
    insertSentence: function insertSentence(obj) {
        var collection = dbo.collection('sentence');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            // utils.writeLog("Insert sentence mp3:")
            // utils.writeLog(obj);
        });
    },
    insertSeminarMedia: function insertSeminarMedia(obj) {
        var collection = dbo.collection('seminar');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            // utils.writeLog("Insert seminar mp3:")
            // utils.writeLog(obj);
        });
    },
    insertCustomerPoint: function insertCustomerPoint(obj) {
        var collection = dbo.collection('customers');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            // utils.writeLog("Insert customer point:")
            // utils.writeLog(obj);
        });
    },
    addFormData: function (data) {
        // utils.writeLog("Add data new:" + JSON.stringify(data));
        var collection = dbo.collection('form_results');
        collection.insertOne(data, function (err, res) {
            if (err) throw err;
            utils.writeLog('Thêm thành công');
        });
    },
    insertLiveclass: function insertLiveclass(obj) {
        var collection = dbo.collection('liveclass');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            utils.writeLog("Insert liveclass:")
            utils.writeLog(obj);
        });
    },
    getDataByTime: function getDataByTime(startTime, endTime, callback) {
        try {
            var s_time = parseInt(startTime);
            var e_time = parseInt(endTime);
            var collection = dbo.collection('customers');  // get reference to the collection
            collection.distinct("userid", { "time": { $gte: s_time, $lte: e_time } }, function (err, docs) {
                callback(docs);
            });
        } catch (e) {

        }
    },
    getDataKidByTime: function getDataKidByTime(startTime, endTime, callback) {
        try {
            var s_time = parseInt(startTime);
            var e_time = parseInt(endTime);
            var collection = dbo.collection('edupiakid');  // get reference to the collection
            collection.distinct("userid", { "time": { $gte: s_time, $lte: e_time } }, function (err, docs) {
                callback(docs);
            });
        } catch (e) {

        }
    },
    getVideoVerifyByPath: function getVideoVerifyByPath(videoPath, callback) {
        try {
            var collection = dbo.collection('videoverify');  // get reference to the collection
            collection.find({ "video_path": videoPath }).toArray(function (err, result) {
                if (err) throw err;
                callback(result);
            });
        } catch (e) {

        }
    },
    getVideoVerifyByPathVerify: function getVideoVerifyByPathVerify(videoPath, callback) {
        try {
            var collection = dbo.collection('videoverify');  // get reference to the collection
            collection.find({ "video_path": videoPath, verify: "1" }).toArray(function (err, result) {
                if (err) throw err;
                callback(result);
            });
        } catch (e) {

        }
    },
    getDataVideoVerify: function getDataVideoVerify(callback) {
        try {
            var collection = dbo.collection('videoverify');  // get reference to the collection
            collection.find({}).toArray(function (err, result) {
                if (err) throw err;
                callback(result);
            });
        } catch (e) {

        }
    },
    insertVideoVerify: function insertVideoVerify(obj) {
        var collection = dbo.collection('videoverify');  // get reference to the collection
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            utils.writeLog("Insert videoverify:");
            utils.writeLog(obj);
        });
    },
    updateVideoVerify: function insertVideoVerify(video_path, verify) {
        var myquery = { video_path: video_path };
        var newvalues = { $set: { verify: verify, time: new Date().getTime() } };
        var collection = dbo.collection('videoverify');  // get reference to the collection
        collection.updateOne(myquery, newvalues, function (err, res) {
            if (err) throw err;
            utils.writeLog("Update videoverify");
        });
    },
    getLiveclassInfo: async function getLiveclassInfo(req, res) {
        var username = req.headers.username;
        var liveclassid = req.headers.liveclassid;
        var data = await checkLiveclassExist(username, liveclassid);
        res.json({ message: 'Update liveclass successfull!', resultCode: 1, data: data });
    },
    insertPointGameAiTestting: function insertPointGameAiTestting(obj) {
        var collection = dbo.collection('gameaitestting');
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            utils.writeLog("Insert point gameaitestting:")
            utils.writeLog(obj);
        });
    },
    checkSMSMaketing: async function checkSMSMaketing(req, res) {
        var phone = req.headers.phone;
        var data = await checkPhoneSMSMarketingExist(phone);
        if (data == null || data == '') {
            res.json({ message: 'Phone not exist!', resultCode: 1, data: false });
        } else {
            if (data[0]['send'] == 0) {
                res.json({ message: 'Phone exist!', resultCode: 0, data: data });
            } else {
                res.json({ message: 'Phone send exist!', resultCode: 1, data: data });
            }
        }
    },
    updateSMSMaketing: async function updateSMSMaketing(phone, login) {
        var data = await checkPhoneSMSMarketingExist(phone);
        if (data == null || data == '') {
            return false;
        } else {
            var myquery = { phone: phone };
            var newvalues = { $set: { send: 1, login: login, updated_at: new Date().getTime() } };
            var collection = dbo.collection('sms_marketing');  // get reference to the collection
            collection.updateOne(myquery, newvalues, function (err, res) {
                if (err) throw err;
                utils.writeLog("Update sms marketing");
            });
            return true;
        }
    },
    activeCodeEdupiaPlus: async function activeCodeEdupiaPlus(req, res) {
        var code = req.headers.code;
        code = code.toLowerCase();
        var data = await checkCodeEdupiaPlus(code);
        if (data == null || data == '') {
            return false;
        } else {
            if (data[0]['days'] > 0) {
                return data;
            } else {
                return false;
            }
        }
    },
    updateCodeEdupiaPlus: async function updateCodeEdupiaPlus(code, phone, username) {
        phone = utils.nomalizePhoneNumber(phone);
        code = code.toLowerCase();
        username = username.toLowerCase();
        var myquery = { code: code };
        var newvalues = { $set: { days: 0, phone: phone, username: username, updated_at: new Date().getTime() } };
        var collection = dbo.collection('code_edupia_plus');  // get reference to the collection
        collection.updateOne(myquery, newvalues, function (err, res) {
            if (err) throw err;
            utils.writeLog("Update edupia plus success " + code);
        });
    },
    exportCodePlusPdf: async function exportCodePlusPdf(fromPage, numberPage, callback) {
        var collection = dbo.collection('code_edupia_plus');

        var realPage = fromPage - 500;
        var startSeri = 100000;
        var from = ((realPage - 1) * 100 + 1) + startSeri;
        var to = (((realPage - 1) + numberPage) * 100) + startSeri;

        // Serial from
        var serialFrom = "" + from;
        while (serialFrom.length < 8) {
            serialFrom = "0" + serialFrom;
        }
        serialFrom = "VRTG" + serialFrom;
        utils.writeLog("Serial from: " + serialFrom);

        // Serial to
        var serialTo = "" + to;
        while (serialTo.length < 8) {
            serialTo = "0" + serialTo;
        }
        serialTo = "VRTG" + serialTo;
        utils.writeLog("Serial to: " + serialTo);

        var query = {serial: {$gte: serialFrom, $lte: serialTo}, source: {$ne: 'online'}};
        collection.find(query).sort({serial: 1}).toArray((err, result) => {
            if (err) throw err;
            if (result != null || !utils.isEmptyObject(result)) {
                callback(result);
            } else {
                callback(null);
            }
        });
    },
    getPlusCode: async function getPlusCode(code, callback) {
        var collection = dbo.collection('code_edupia_plus');
        var query = { code: code };
        collection.find(query).toArray((err, result) => {
            if (err) throw err;
            if (result != null || !utils.isEmptyObject(result)) {
                callback(result);
            } else {
                callback(null);
            }
        });
    },
    genPlusCodeForPartner: async function genPlusCodeForPartner(req, res) {
        var ip;
        if (req.headers['x-forwarded-for']) {
            ip = req.headers['x-forwarded-for'].split(",")[0];
        } else if (req.connection && req.connection.remoteAddress) {
            ip = req.connection.remoteAddress;
        } else {
            ip = req.ip;
        }
        utils.writeLog("IP........: " + ip);
        // limit ip call service
        if (!ipGenCodePlusValids.includes(ip)) {
            res.json({message: 'Client not available!', resultCode: 0});
            return "";
        }
        var username = req.headers.username;
        var password = req.headers.password;
        if (username == undefined || utils.isEmptyObject(username)) {
            res.json({message: 'Username input invalid!', resultCode: 0});
            return "";
        }
        if (password == undefined || utils.isEmptyObject(password)) {
            res.json({message: 'Password input invalid!', resultCode: 0});
            return "";
        }
        try {
            // Kiem tra thong tin user
            let checkUser = await checkGenPlusCodeUser(username, password);
            if (!checkUser) {
                res.json({message: 'Username or password invalid!', resultCode: 0});
                return "";
            }
            // Gen code
            var codeObj = await genCodePlus();
            try {
                var line = 'VRTGO' + "\t" + codeObj.serial + "\t" + codeObj.code + "\t" + 30 + "\t" + username + "\t" + new Date() + "\n";
                var d = new Date();
                var year = d.getFullYear();
                var month = d.getMonth() + 1;
                var fileCode = utils.folderCode + month + year + ".txt";
                fs.appendFile(fileCode, line, (err) => {
                    if (err) {
                        utils.writeLog('Write code autogen error!');
                    }
                    utils.writeLog('Write code autogen success!');
                });

            } catch (e) {
                utils.writeLog("Error write code");
            }
            res.json({code: codeObj.code, serial: codeObj.serial, message: "successful!", resultCode: 1});
            return '';
        } catch (error) {
            console.error(error);
        }
        res.send({message: "Error!", resultCode: 0});
        return '';
    }
};