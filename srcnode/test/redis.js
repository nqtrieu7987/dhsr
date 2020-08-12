var config = require('.././config.json');
var utils = require('.././Utils.js');
var RedisClustr = require('redis-clustr');
// var redisAddress = config.redisPro;
var redisAddress = config.redisLab;
var redis = new RedisClustr(redisAddress);
var counter = 0;
// redis.blpop(config.ChannelFacebookSender,0,handleJob);
// console.log(redisAddress)
async function asyncCall() {
    // redis.hget(config.keyExerciseInfo, "297", function (err, obj) {
    //     if (err) {
    //         utils.writeLog(err)
    //     } else {
    //         var json = JSON.parse(obj);
    //         utils.writeLog(json);
    //     }
    // });

    // var message = {};
    // message.mailReceive = "chuyen2t@gmail.com";
    // message.content = "test spam mail ";
    // message.subject = "subject spam mail";
    // utils.writeLog(redis.rpush(config.queueMailSender, JSON.stringify(message)));
    // var result = await utils.genCode(2, 360);
    // utils.writeLog(result);
    var counter = await inrcCache("chuyennd");
    console.log(counter);
    redis.zrem(config.contactSorted, "967632234", function (err, list) {
        if (err) throw err;        
    });

}
asyncCall();
// redis.incr(config.keySerialCounter, function (err, obj) {
//     utils.writeLog(obj);
// });
// function handleJob(err,job) {
//     var json = JSON.parse(job[1]);   
//     if (json.text.length > 40) {
//       utils.writeLog("Send msg to " + json.psid + " Text:" + json.text.substring(0, 40))
//     } else {
//       utils.writeLog("Send msg to " + json.psid + " Text:" + json.text)
//     }
//     redis.blpop(config.ChannelFacebookSender,0,handleJob);
// }
async function inrcCache(key) {
    return new Promise((resv, rej) => {
        redis.incr(key, (err, res) => {
            resv(res);
        });
    })
}

// var message = {};
// message.name = "duc bot";
// for (var i = 0; i < 10; i++) {
//     message.text = "spam msg: " + i + " " + Date.now();
//     message.newLine = "true";
//     redis.rpush("redis.key.educa.skype.queues", JSON.stringify(message));
// }
// message.text = "Finish: " + Date.now();
// message.newLine = "false";
// redis.rpush("redis.key.educa.skype.queues", JSON.stringify(message));

// redis.hgetall(config.ChannelPsid2Username, function (err, results) {
//   if (err) {
//     // do something like callback(err) or whatever
//   } else {
//     var counter = 0;
//     // do something with results
//     Object.keys(results).forEach(function (key) {
//       counter++;
//       var val = results[key];
//       var message = {};
//       message.psid = "1898161780270589";
//       message.text = key + " -> " + val;
//       redis.rpush(config.ChannelFacebookSender, JSON.stringify(message));
//       if (counter >= 10) {
//         return;
//       }
//     });
//   }
// });
// var text = "Nguyen Dinh <br> <br> Chuyen 123"
// utils.writeLog(text)
// text = text.replace(new RegExp('<br>', 'g'),"\n");
// utils.writeLog(text)

// var test = "100000"
// if(test.length > 10){
//     utils.writeLog(test.substring(0,10))
// }else{
//     utils.writeLog(test)
// }
// var receivedText = "testsvd sdgdfg 0989144 dfgdgdf";
// var customerPhone = "";
// if (utils.isEmptyObject(receivedText)) {
//     return "";
// }
// // chi xu ly 2 case
// if (receivedText.toLowerCase().startsWith("capnhat") && (receivedText.split(" ").length > 1)) {
//     customerPhone = "";
// }
// if (!utils.isEmptyObject(receivedText.replace(/\D/g, ''))) {
//     customerPhone = receivedText.replace(/\D/g, '');
// }
// if (!utils.isEmptyObject(customerPhone) && customerPhone.length > 7) {
//     utils.writeLog("SDT-------------" + customerPhone);
// } else {
//     customerPhone = "";
// }
// if (receivedText.toLowerCase().startsWith("dt ") && (receivedText.split(" ").length > 1)) {
//     customerPhone = receivedText.split(" ")[1];
// }
// if ((receivedText.toLowerCase().startsWith("taikhoan ") || receivedText.toLowerCase().startsWith("dk ")) && (receivedText.split(" ").length > 1)) {
//     customerPhone = "";
// }
// utils.writeLog(customerPhone)