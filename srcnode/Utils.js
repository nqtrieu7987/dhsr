var moment = require('moment');
var config = require('./config.json');
var ip = require("ip");
var randomstring = require("randomstring");
const fs = require('fs');
var folderCode = "/var/www/log/educa/codes/autogen/";
var dateFormat = require('dateformat');
const isNumber = require('is-number');
var ipGenCodeAvalids = [];
ipGenCodeAvalids.push("113.190.234.17");
ipGenCodeAvalids.push("172.25.80.119");
var shard_number = 256;
const utf8 = require('utf8');
function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}
function replaceAll(str, find, replace) {
    return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
var RedisClustr = require('redis-clustr');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);
function genANewCode() {
    return randomstring.generate({
        length: 6,
        charset: '123456789abcdefghijklmnpqrstuvwxyz'
    });
}
function hashCode(str) {
    return str.split('').reduce((prevHash, currVal) =>
        (((prevHash << 5) - prevHash) + currVal.charCodeAt(0)) | 0, 0);
}
async function getRedisHash(key, value) {
    return new Promise(resolve => {
        redis.hget(key, value, function (err, obj) {
            resolve(obj);
        });
    });
}
async function genSerial() {
    var serialNumber = await getRedisIncreSerialNumber();
    var serial = "" + serialNumber;
    while (serial.length < 8) {
        serial = "0" + serial;
    }
    return "EDU-" + serial;
}
async function getRedisIncreSerialNumber() {
    return new Promise(resolve => {
        redis.incr(config.keySerialCounter, function (err, obj) {
            resolve(obj);
        });
    });
}
function isEmptyObject(obj) {
    if (Number.isInteger(obj)) {
        return false;
    }
    if (obj == undefined || obj == null || obj.trim() == '') {
        return true;
    }
    return !Object.keys(obj.trim()).length;
}
function writeLog(msg) {
    var current_time = new moment().format("YYYY-MM-DD HH:mm:ss");
    var wr = current_time + ' ' + msg;
    console.log(wr);
}

function getFilePath (exerciseid, username) {
    if (exerciseid !== undefined) {
        return "/srv/store/user/uploads/exercise/" + exerciseid + '/' + Math.abs(hashCode(username) % 256) + '/' + username + '.mp4';
    }
    return "/srv/store/user/uploads/presentation/" + username + "/" + username + ".mp4";
}

module.exports = {
    isEmptyObject: function isEmptyObject(obj) {
        if (Number.isInteger(obj)) {
            return false;
        }
        if (obj == undefined || obj == null || obj.trim() == '') {
            return true;
        }
        return !Object.keys(obj.trim()).length;
    },
    writeLog: writeLog,
    hashCode: hashCode,
    getFilePath: getFilePath,
    buildButtomTemplate: function buildButtomTemplate(userEdupias) {
        var results = [];
        for (i in userEdupias) {
            if (results.length >= 3) {
                break;
            }
            var template = {};
            template.title = userEdupias[i].username;
            if (userEdupias[i].end_time > new Date().getTime() || userEdupias[i].end_time_smart > new Date().getTime()) {
                template.type = 'postback';
                template.payload = 'REGISTER_ACCOUNT';
                results.push(template);
            }
        }
        return results;
    },
    buildRedisUserKey: function buildRedisUserKey(key, username) {
        return key + "." + Math.abs(hashCode(username) % shard_number);
    },
    getCurrentMonth: function getCurrentMonth() {
        var now = new Date();
        // Basic usage
        return dateFormat(now, "yyyymm");
    },
    getCurrentHour: function getCurrentHour() {
        var now = new Date();
        return now.getHours();
    },
    getCurrentMinute: function getCurrentMinute() {
        var now = new Date();
        return now.getMinutes();
    },
    nomalizePhoneNumber: function nomalizePhoneNumber(phone) {
        if (phone == null) {
            return "";
        }
        phone = phone.replace('0+84', '0');
        phone = phone.replace('+84', '0');
        phone = replaceAll(phone, '.', '');
        if (phone.substring(0, 2) == "84") {
            phone = phone.substring(2, phone.length);
        }
        if (phone.substring(0, 1) != "0") {
            phone = "0" + phone;
        }

        // phone = phone.replace('0084', '0');
        // phone = phone.replace('084', '0');
        // phone = phone.replace('84', '0');
        phone = phone.replace(/ /g, '');
        if (phone != '') {
            var firstNumber = phone.substring(0, 2);
            if ((firstNumber == '09' || firstNumber == '08' || firstNumber == '07' || firstNumber == '01' || firstNumber == '03' || firstNumber == '05') && phone.length == 10) {
                if (phone.match(/^\d{10}/)) {
                    return phone.substring(1, phone.length);
                }
            } else if (firstNumber == '01' && phone.length == 11) {
                if (phone.match(/^\d{11}/)) {
                    return phone.substring(1, phone.length);
                }
            }
        }
        return "";
    },
    extractEvent: function extractEvent(event) {
        var textInSpeech = "";
        writeLog("Length: " + event.length);
        for (var i = 0; i < event.length; i++) {
            writeLog("isFinal: " + event[i][0].isFinal);
            if (!event[i][0].isFinal) {
                textInSpeech += event[i][0].transcript;
            } else {
                if (event.length == 1) {
                    textInSpeech = event[i][0].transcript;
                }
                else {
                    textInSpeech += event[i][0].transcript;
                }
            }

        }
        var result = compareString(textInSpeech, texts[rd]);
        return result;
    },
    compareString: function compareString(_input, source) {
        var _result = [];
        var input = "";
        if (_input[0] == " ") {
            for (var i = 1; i < _input.length; i++) {
                input += _input[i];
            }
        } else {
            input = _input;
        }
        input = stopWord(input);
        source = stopWord(source);
        input = input.toUpperCase();
        source = source.toUpperCase();
        var inputSplit = input.split(" ");
        var sourceSplit = source.split(" ");
        //writeLog(inputSplit,sourceSplit);
        for (var i = 0; i < sourceSplit.length; i++) {
            if (sourceSplit[i] == inputSplit[i]) {
                _result.push(1);
            } else {
                _result.push(0);
            }
        }
        return _result;
    },

    stopWord: function stopWord(str) {
        str = str.replace(".", "");
        str = str.replace(",", "");
        str = str.replace("!", "");
        str = str.replace("?", "");
        str = str.replace(":", "");
        str = str.replace("\"", "");
        str = str.replace("-", "");
        str = str.replace("+", "");
        return str;
    },
    genCode: async function genCode(req, res) {
        var ip;
        if (req.headers['x-forwarded-for']) {
            ip = req.headers['x-forwarded-for'].split(",")[0];
        } else if (req.connection && req.connection.remoteAddress) {
            ip = req.connection.remoteAddress;
        } else {
            ip = req.ip;
        }
        writeLog("IP........: " + ip);
        // limit ip call service 
        if (!ipGenCodeAvalids.includes(ip)) {
            res.json({ message: 'Client not Avalible!', resultCode: 0 });
            return "";
        }
        var type = req.headers.type;
        var day = req.headers.day;
        var source = req.headers.source;
        if (type == undefined || isEmptyObject(type)) {
            res.json({ message: 'Type input invalid!', resultCode: 0 });
            return "";
        }
        if (day == undefined || isEmptyObject(day) || !isNumber(day)) {
            res.json({ message: 'Day input invalid!', resultCode: 0 });
            return "";
        }
        if (source == undefined || isEmptyObject(source)) {
            res.json({ message: 'Source input invalid!', resultCode: 0 });
            return "";
        }
        try {
            // check code type (kid or th) and cal day             
            var key = config.keyCodeEs;
            if (type == "1") {
                key = config.keyCode;
            }else if(type == "2"){
                key = config.keyCodeKid;
            }
            // gen code new
            var code = genANewCode();
            while (true) {
                var dayInRedis = await getRedisHash(key, code);
                if (dayInRedis == null) {
                    break;
                }
                code = genANewCode();
            }
            // write code to redis and return
            writeLog("Gen code with  type " + type + " => key:" + key + " code:" + code + " day:" + day);
            redis.hset(key, code, day);
            var dayInredis = await getRedisHash(key, code);
            if (dayInredis == null) {
                return '';
            }
            var serial = await genSerial();
            try {
                var line = type + "\t" + serial + "\t" + code + "\t" + day + "\t" + source + "\t" + new Date() + "\n";
                var d = new Date();
                var year = d.getFullYear();
                var month = d.getMonth() + 1;
                var fileCode = folderCode + month + year + ".txt";
                fs.appendFile(fileCode, line, (err) => {
                    if (err) {
                        writeLog('Write code autogen error!');
                    }
                    writeLog('Write code autogen success!');
                });

            } catch (e) {
                writeLog("Error write code");
            }
            res.json({ code: code, serial: serial, message: "successful!", resultCode: 1 });
            return '';
        } catch (error) {
            console.error(error);
        }
        res.send({ message: "Error!", resultCode: 0 });
        return '';
    },
    decodeUtf8: function decodeUtf8(input) {
        var result = "";
        try {
            result = utf8.decode(input);
        } catch (e) {
            return "";
            writeLog("Error during decode utf8:" + input);
        }
        return result;
    },
    validateEmail: function validateEmail(email){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(mailformat.test(email))
        {
            return true;
        }else{
            return false;
        }
    },
    generateFolder: function generateFolder(path, username) {
        var folder ="";
        var array = path.split("/");
        array.forEach(function (item, index) {
            folder +="/"+item;
            if (!fs.existsSync(folder)) {
                fs.mkdirSync(folder);
            }
        });
        return folder;
    },
    getRandomInteger: function getRandomInteger(min, max) {
        /*random int*/
        return Math.floor(Math.random() * (max - min)) + min;
    },
    genANewCode: genANewCode
};