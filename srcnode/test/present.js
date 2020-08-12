var config = require('.././config.json');
var utils = require(".././Utils.js");
var RedisClustr = require('redis-clustr');
var ip = require("ip");
var redisAddress = config.redisLab;
if (ip.address().includes("172.25.80")) {
    redisAddress = config.redisPro;
}
var redis = new RedisClustr(redisAddress);

var exerciseid = "120";
var username = "chuyennd";
utils.writeLog("push paragrap present for user: " + username);
var paragrap = "test tst [test] fasfd fasfds";

redis.hget(config.keyEduca, config.keyMarketingPackge, function (err, obj) {
    try {
        var array = JSON.parse(obj);
        var listRemove = [];
        for (var i = 0; i < array.length; i++) {
            var json = array[i];
            var content = JSON.parse(json.content);
            if(json.type == 2){
                listRemove.push(i);
            }
            let arrayOfObjects = Object.keys(content).map(key => {
                let ar = content[key]
                // Apppend key if one exists (optional)
                ar.key = key
                return ar
            })
            json.content = arrayOfObjects;
            array[i] = json;
        }
        utils.writeLog(listRemove);
        for(var i = 0; i < listRemove.length; i ++){
            array.splice(listRemove[i],1);
        }
        utils.writeLog(array);
    } catch (e) {
        utils.writeLog({ errorCode: 0, message: 'Fail', data: "{}" });
        utils.writeLog("Error get marketingpackage");
    }
});