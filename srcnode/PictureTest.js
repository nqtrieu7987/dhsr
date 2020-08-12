var config = require('./config.json');
const utf8 = require('utf8');
var utils = require("./Utils");
var FtpTools = require('./controllers/FtpTools');
var ip = require("ip");
var fs = require('fs');
var RedisClustr = require('redis-clustr');
var jwt = require('jsonwebtoken');
var cert = fs.readFileSync('/etc/httpd/ssl/educa.vn.key');  // get private key
var _ = require('underscore');
var request = require('request');
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
    isServerLab = false;
}
if (ip.address().includes("103.216.121")) {
    redisAddress = config.redisPro;
    isServerLab = false;
}
var redis = new RedisClustr(redisAddress);

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

async function countLesson(object) {
    var count = 0;
    for(var i =0; i< object.length; i ++){
        if(object[i].startsWith('L') && object[i].length >= 6){
            count ++;
        }
    }
    return count;
}

// user picture profile: username -> list month(topic) -> list lesson -> iteminfo, imageurl
// user picture profile: username -> list month(topic) -> video seminar (url)
module.exports = {
    getItemInfo: async function getItemInfo(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    var lesson = req.headers.lesson;
    var month = req.headers.month;
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (lesson == undefined || utils.isEmptyObject(lesson)) {
        res.status(200).json({ message: 'Bạn phải nhập lesson source!', resultCode: 1 });
        return "";
    }
    lesson = lesson.trim();
    username = username.toLowerCase();
    // get picture profile
    var obj = await getRedisHash(config.keyUserPicturePorfile, username);
    try {
        if (obj == null) {
            obj = "{}";
        }
        var json = JSON.parse(obj);
        var monthInfo = json[month];
        if (monthInfo == null) {
            monthInfo = {};
            json[month] = monthInfo;
        }
        var itemInfo = monthInfo[lesson];
        // neu la lesson moi trong thang
        if (itemInfo == null) {
            itemInfo = {};
            // lesson thu 13 tro len thi ko ra itemid
            var count = await countLesson(Object.keys(monthInfo));
            if (count == 4) {
                res.json({ errorCode: 0, message: 'User have over 4 iamges!' });
            } else {
                itemInfo.id = count + 1;
                monthInfo[lesson] = itemInfo;
                var itemObject = await getRedisHash(config.keyUserItemInfo, itemInfo.id);
                var jsonItemObject = JSON.parse(itemObject);
                delete jsonItemObject.info.createdAt;
                delete jsonItemObject.info.updatedAt;
                itemInfo.info = jsonItemObject.info;
                // push data 2 redis
                //redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
                res.json(JSON.parse(itemObject));
            }
        } else {
            // lesson da hoan thanh va lay lai item object
            var itemObject = await getRedisHash(config.keyUserItemInfo, itemInfo.id);
            res.json(JSON.parse(itemObject));
        }
    } catch (e) {
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog(e)
        utils.writeLog("Error get Item info");
    }
},
updateImageProfile: async function updateImageProfile(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    /*var lesson = req.headers.lesson;
    var itemId = req.headers.itemid;
    var imageUrl = req.headers.imageurl;*/
    var month = req.headers.month;
    var answer = req.headers.answer;
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    if (answer == undefined || utils.isEmptyObject(answer)) {
        answer = false;
    }
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    /*if (lesson == undefined || utils.isEmptyObject(lesson)) {
        res.status(200).json({ message: 'Bạn phải nhập lesson source!', resultCode: 1 });
        return "";
    }
    if (itemId == undefined || utils.isEmptyObject(itemId)) {
        res.status(200).json({ message: 'Bạn phải nhập item id!', resultCode: 1 });
        return "";
    }
    if (imageUrl == undefined || utils.isEmptyObject(imageUrl)) {
        res.status(200).json({ message: 'Bạn phải nhập Image Url!', resultCode: 1 });
        return "";
    }
    lesson = lesson.trim();*/
    username = username.toLowerCase();

    // get picture profile
    var obj = await getRedisHash(config.keyUserPicturePorfile, username);
    try {
        if (obj == null) {
            obj = "{}";
        }
        var json = JSON.parse(obj);
        var monthInfo = json[month];
        if (monthInfo == null) {
            monthInfo = {};
            json[month] = monthInfo;
        }
        /*var itemInfo = monthInfo[lesson];
        if (itemInfo == null) {
            itemInfo = {};
            // lesson thu 13 tro len thi ko ra itemid
            var count = await countLesson(Object.keys(monthInfo));
            if (count == 4) {
                res.json({ errorCode: 0, message: 'User have over 4 iamges!' });
            } else {
                itemInfo.id = itemId;
                monthInfo[lesson] = itemInfo;
                var itemObject = await getRedisHash(config.keyUserItemInfo, itemInfo.id);
                var jsonItemObject = JSON.parse(itemObject);
                delete jsonItemObject.info.createdAt;
                delete jsonItemObject.info.updatedAt;
                itemInfo.info = jsonItemObject.info;
                // push data 2 redis
                redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
            }
        }
        json[month][lesson].image = imageUrl;*/
        // push data 2 redis
        json[month].answer = answer;
        console.log('answer='+answer);
        redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
        res.json({ errorCode: 1, message: 'Sucess!' });
    } catch (e) {
        utils.writeLog("Error get Item info");
        res.json({ errorCode: 0, message: 'Fail' });
    }
},
updateLikeShare: async function updateLikeShare(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    var month = req.headers.month;
    var like = req.headers.like;
    var share = req.headers.share;
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (like == undefined || utils.isEmptyObject(like)) {
        // res.status(200).json({ message: 'Bạn phải nhập like!', resultCode: 1 });
        // return "";
    }
    if (share == undefined || utils.isEmptyObject(share)) {
        // res.status(200).json({ message: 'Bạn phải nhập share!', resultCode: 1 });
        // return "";
    }
    username = username.toLowerCase();
    // get picture profile
    var obj = await getRedisHash(config.keyUserPicturePorfile, username);
    try {
        var json = JSON.parse(obj);
        json[month]["like"] = like;
        json[month]["share"] = share;
        var point = Number(like) + Number(share) * 3;
        json[month]["score"] = point;

        var user = await getRedisHash(config.keyProfileInfo, username);
        json[month]["firstName"] = JSON.parse(user).firstName;
        json[month]["address"] = JSON.parse(user).address;
        json[month]["avatar"] = JSON.parse(user).avatar;

        // push data 2 redis
        redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
        if(point >= 0 && month == '201909'){
            var key = config.keyUserPicturePorfileRanked;
            key = key + "." + '201909';
            redis.zadd(key, -point, username);
        }
        //redis.zrem(key, username);
        res.json({ errorCode: 1, message: 'Sucess!' });
    } catch (e) {
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog("Error get Item info");
    }
},
updateSubmitTime: async function updateSubmitTime(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    var month = req.headers.month;
    var time = req.headers.time;
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (time == undefined || utils.isEmptyObject(time)) {
        res.status(200).json({ message: 'Bạn phải nhập time!', resultCode: 1 });
        return "";
    }
    username = username.toLowerCase();
    // get picture profile
    var obj = await getRedisHash(config.keyUserPicturePorfile, username);
    try {
        // push data 2 redis
        var key = config.keyUserPicturePorfileRankedTime;
        if (month == undefined || utils.isEmptyObject(month)) {
            key = key + "." + utils.getCurrentMonth();
        }else{
            key = key + "." + month;
        }
        redis.zadd(key, -time, username);
        res.json({ errorCode: 1, message: 'Sucess!' });
    } catch (e) {
        utils.writeLog("Error update time submit");
        res.json({ errorCode: 0, message: 'Fail' });
    }
},
getUserPictureProfile: function getUserPictureProfile(req, res) {
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    redis.hget(config.keyUserPicturePorfile, username, function (err, obj) {
        try {
            var data = obj;
            if (data == null) {
                data = "{}";
            }
            res.send(JSON.parse(data));
        } catch (e) {
            utils.writeLog("Error get picture porfile");
            res.json({ errorCode: 0, message: 'Fail', data: "{}" });
        }
    });
},

getUserPictureRankedByTime: function getUserPictureRankedByTime(req, res) {
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var key = config.keyUserPicturePorfileRankedTime;
    var month = req.headers.month;
    if (month == undefined || utils.isEmptyObject(month)) {
        key = key + "." + utils.getCurrentMonth();
    }else{
        key = key + "." + month;
    }
    redis.zrange(key, 0, -1, 'withscores', function (err2, members) {
        try {
            var lists = _.groupBy(members, function (a, b) {
                return Math.floor(b / 2);
            });
            lists = _.toArray(lists);
            var result = [];
            for (var i = 0; i < lists.length; i++) {
                result.push(lists[i][0]);
            }
            res.json({ errorCode: 1, message: 'Success!', data: result });
        } catch (e) {
            utils.writeLog("Error get picture porfile ranked by time");
            res.json({ errorCode: 0, message: 'Fail', data: "{}" });
        }
    });
},

getUserPictureRanked: function getUserPictureRanked(req, res) {
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var key = config.keyUserPicturePorfileRanked;
    var month = req.headers.month;
    if (month == undefined || utils.isEmptyObject(month)) {
        key = key + "." + utils.getCurrentMonth();
    }else{
        key = key + "." + month;
    }
    redis.zrange(key, 0, -1, 'withscores', function (err2, members) {
        try {
            var lists = _.groupBy(members, function (a, b) {
                return Math.floor(b / 2);
            });
            lists = _.toArray(lists);
            var result = [];
            for (var i = 0; i < lists.length; i++) {
                result.push(lists[i][0]);
            }
            res.json({ errorCode: 1, message: 'Success!', data: result });
        } catch (e) {
            utils.writeLog("Error get picture porfile ranked");
            res.json({ errorCode: 0, message: 'Fail', data: "{}" });
        }
    });
},
uploadPictureSeminar: async function uploadPictureSeminar(req, res) {
    utils.writeLog("Upload seminar picture mp4");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    if (!req.files)
        return res.status(400).send('No files were uploaded.');
    var username = req.query.username;
    var jwtToken = req.query.token;
    var month = req.query.month;
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (jwtToken == undefined || utils.isEmptyObject(jwtToken)) {
        res.status(200).json({ message: 'Bạn phải nhập token!', resultCode: 1 });
        return "";
    }
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    try {
        username = username.toLowerCase();
    } catch (error) { }
    var payload = await extractPayloadFromToken(jwtToken);
    if (payload.username == username) {
        var time = new Date().getTime();
        var date = new Date().getDate();
        //The name of the input field(i.e. "\service\test\uploadVideoSeminar.html") is used to retrieve the uploaded file

        let mp4File = req.files.mp4File;
        var path = config.mp4UploadPresentation + username;
        path = utils.generateFolder(path, username);
        var fileName = path + "/" + username + ".mp4";
        utils.writeLog(fileName);
        if(mp4File !== undefined){
            mp4File.mv(fileName, function (err) {
                if (err){
                    return res.status(500).send(err);
                }
                redis.hget(config.keyUserPicturePorfile, username, function (err, obj) {
                    try {
                        var mp4File = fileName.replace("/srv/store/", "https://static.edupia.vn/");
                        var json = JSON.parse(obj);
                        json[month]["video"] = mp4File;
                        redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
                        res.json({ message: mp4File, resultCode: 0 });
                    } catch (e) {
                        utils.writeLog(e)
                        utils.writeLog("Error during push data 2 redis");
                        res.json({ errorCode: 1, message: 'Fail' });
                    }
                });
            });
        }else{
            let mp3File = req.files.mp3File;
            var fileName = path + "/" + username + ".mp3";
            mp3File.mv(fileName, function (err) {
                if (err){
                    return res.status(500).send(err);
                }
                redis.hget(config.keyUserPicturePorfile, username, function (err, obj) {
                    try {
                        var mp3File = fileName.replace("/srv/store/", "https://static.edupia.vn/");
                        var json = JSON.parse(obj);
                        json[month]["video"] = mp3File;
                        redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
                        res.json({ message: mp3File, resultCode: 0 });
                    } catch (e) {
                        utils.writeLog(e)
                        utils.writeLog("Error during push data 2 redis");
                        res.json({ errorCode: 1, message: 'Fail' });
                    }
                });
            });
        }
    } else {
        res.json({ message: 'User invalid!', resultCode: 0 });
    }
},

uploadPicturePresent: async function uploadPicturePresent(req, res) {
    utils.writeLog("Upload present picture mp4");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    if (!req.files)
        return res.status(400).send('No files were uploaded.');
    var username = req.query.username;
    var jwtToken = req.query.token;
    var month = req.query.month;
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (jwtToken == undefined || utils.isEmptyObject(jwtToken)) {
        res.status(200).json({ message: 'Bạn phải nhập token!', resultCode: 1 });
        return "";
    }
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    try {
        username = username.toLowerCase();
    } catch (error) { }
    var payload = await extractPayloadFromToken(jwtToken);
    if (payload.username == username) {
        var time = new Date().getTime();
        var date = new Date().getDate();
        //The name of the input field(i.e. "\service\test\uploadVideoSeminar.html") is used to retrieve the uploaded file
        let mp4File = req.files.mp4File;
        utils.writeLog("mp4File="+mp4File);
        if(mp4File !== undefined){// Use the mv() method to place the file somewhere on your server
            var fileName = "pictureseminar/" + username + "_" + month + "_"+ date +"_" + time + ".mp4";
            mp4File.mv(fileName, function (err) {
                if (err)
                    return res.status(500).send(err);
                //upload to cdn and update to user picture porfile
                FtpTools.uploadMp4(fileName);
                var urlMp4 = "https://static.edupia.vn/uploadmp4/" + fileName;

                redis.hget(config.keyUserPicturePorfile, username, function (err, obj) {
                    try {
                        var json = JSON.parse(obj);
                        json[month]["video"] = urlMp4;
                        redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
                        res.json({ message: urlMp4, resultCode: 1 });
                    } catch (e) {
                        utils.writeLog(e)
                        res.json({ errorCode: 0, message: 'Fail' });
                        utils.writeLog("Error during push data 2 redis");
                    }
                });

            });
        }else{
            res.json({ message: 'File Mp4 invalid!', resultCode: 0 });
        }
    } else {
        res.json({ message: 'User invalid!', resultCode: 0 });
    }
},

uploadImageCover: async function uploadImageCover(req, res) {
    utils.writeLog("Upload Image Cover");
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    if (!req.files)
        return res.status(400).send('No files were uploaded.');
    var username = req.query.username;
    var jwtToken = req.query.token;
    var month = req.query.month;
    if (username == undefined || utils.isEmptyObject(username)) {
        res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
        return "";
    }
    if (jwtToken == undefined || utils.isEmptyObject(jwtToken)) {
        res.status(200).json({ message: 'Bạn phải nhập token!', resultCode: 1 });
        return "";
    }
    if (month == undefined || utils.isEmptyObject(month)) {
        res.status(200).json({ message: 'Bạn phải nhập month format yyyyMM!', resultCode: 1 });
        return "";
    }
    try {
        username = username.toLowerCase();
    } catch (error) { }
    var payload = await extractPayloadFromToken(jwtToken);
    if (payload.username == username) {
        //The name of the input field(i.e. "sampleFile") is used to retrieve the uploaded file
        let imageCover = req.files.imageCover;
        var path = config.mp4UploadPresentation + username;
        path = utils.generateFolder(path, username);
        var fileName = path + "/" + username + ".png";
        utils.writeLog(fileName);
        imageCover.mv(fileName, function (err) {
            if (err){
                return res.status(500).send(err);
            }
            redis.hget(config.keyUserPicturePorfile, username, function (err, obj) {
                try {
                    var image = fileName.replace("/srv/store/", "https://static.edupia.vn/");
                    var json = JSON.parse(obj);
                    json[month]["cover"] = image;
                    redis.hset(config.keyUserPicturePorfile, username, JSON.stringify(json));
                    res.json({ message: image, resultCode: 0 });
                } catch (e) {
                    utils.writeLog(e)
                    utils.writeLog("Error during push data 2 redis");
                    res.json({ errorCode: 1, message: 'Fail' });
                }
            });
        });
    } else {
        res.json({ message: 'User invalid!', resultCode: 0 });
    }
},

deleteUserFromPictureRank: function deleteUserFromPictureRank(req, res) {
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    var username = req.headers.username;
    var month = req.headers.month;
    try {
        var key = config.keyUserPicturePorfileRanked;
        key = key + "." + month;
        redis.zrem(key, username);
        var key = config.keyUserPicturePorfileRankedTime;
        key = key + "." + month;
        redis.zrem(key, username);
        utils.writeLog("Xoa bai thuyet trinh: "+username);
        res.json({ message: "Xóa thành công!", resultCode: 1 });
    } catch (e) {
        utils.writeLog(e)
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog("Error delete");
    }
    
},
};

async function asyncCall() {
    var obj = await getAllKeyInSortedSet(config.keyUserPicturePorfileRanked);
    utils.writeLog(obj);
    // var username = "chuyennd";
    // var lesson = "123";
    // if (username == undefined || utils.isEmptyObject(username)) {
    //   res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
    //   return "";
    // }
    // if (lesson == undefined || utils.isEmptyObject(lesson)) {
    //   res.status(200).json({ message: 'Bạn phải nhập lesson source!', resultCode: 1 });
    //   return "";
    // }
    // lesson = lesson.trim();
    // username = username.toLowerCase();
    // // get picture profile
    // var obj = await getRedisHash(config.keyUserPicturePorfile, username);
    // try {
    //   if (obj == null) {
    //     obj = "{}";
    //   }
    //   var json = JSON.parse(obj);
    //   var keyMonth = utils.getCurrentMonth();
    //   var monthInfo = json[keyMonth];
    //   if (monthInfo == null) {
    //     monthInfo = {};
    //     json[keyMonth] = monthInfo;
    //   }
    //   var itemInfo = monthInfo[lesson];
    //   if (itemInfo == null) {
    //     // lesson thu 13 tro len thi ko ra itemid
    //     if (Object.keys(monthInfo).length == 12) {
    //       utils.writeLog({ errorCode: 0, message: 'User have over 12 iamges!' });
    //     } else {
    //       itemInfo = {};
    //       itemInfo.id = Object.keys(monthInfo).length + 1;
    //       monthInfo[lesson] = itemInfo;
    //       var itemObject = await getRedisHash(config.keyUserItemInfo, itemInfo.id);
    //       itemInfo.info = JSON.parse(itemObject).info;
    //       utils.writeLog({ errorCode: 1, message: 'Sucess!', iteminfo: itemObject });
    //     }
    //   }
    // } catch (e) {
    //   utils.writeLog(e);
    //   utils.writeLog("Error get Item info!");
    // }
}
// asyncCall();