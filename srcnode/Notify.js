var config = require('./config.json');
const utf8 = require('utf8');
var utils = require("./Utils");
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

async function getAccessToken() {
  return new Promise(function (resolve, reject) {
    var key = require('./notifyapp-83b77-firebase-adminsdk-93rit-33604a6937.json');
    var { google } = require("googleapis");

    var SCOPES = [
      "https://www.googleapis.com/auth/firebase",
      "https://www.googleapis.com/auth/cloud-platform",
      "https://www.googleapis.com/auth/firebase.readonly"
    ];

    var jwtClient = new google.auth.JWT(
      key.client_email,
      null,
      key.private_key,
      SCOPES,
      null
    );
    jwtClient.authorize(function (err, tokens) {
      if (err) {
        utils.writeLog("Error making request to generate access token:", error);
        reject(err);
        return;
      } else if (tokens.access_token === null) {
        utils.writeLog("Provided service account does not have permission to generate access tokens");
        reject(err);
        return;
      } else {
        utils.writeLog("token=" + tokens.access_token);
        resolve(tokens.access_token);
      }
    });
  });
}

module.exports = {
  addFireBaseToken: function addFireBaseToken(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var username = req.headers.username;
    var token = req.headers.token;
    var deviceOs = req.headers.deviceos;
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
      return "";
    }
    if (token == undefined || utils.isEmptyObject(token)) {
      res.status(200).json({ message: 'Bạn phải nhập token!', resultCode: 1 });
      return "";
    }
    if (deviceOs == undefined || utils.isEmptyObject(deviceOs)) {
      res.status(200).json({ message: 'Bạn phải nhập device Os!', resultCode: 1 });
      return "";
    }
    token = token.trim();
    deviceOs = deviceOs.trim();
    username = username.toLowerCase();
    // utils.writeLog("Add firebase token: " + token + "\t" + deviceOs + "\t" + username);
    var keyNotifyInfo = username + "-info";
    redis.hget(config.keyUserNotifies, keyNotifyInfo, function (err, obj) {
      try {
        if (obj == null) {
          obj = "{}";
        }
        var json = JSON.parse(obj);
        json[deviceOs] = token;
        redis.hset(config.keyUserNotifies, keyNotifyInfo, JSON.stringify(json));
        res.json({ errorCode: 1, message: 'Sucess!' });
      } catch (e) {
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog("Error push notify token");
      }
    });
  },
  removeFireBaseToken: function removeFireBaseToken(req, res) {
    req.header("Content-Type", "text/html; charset=utf-8");
    var username = req.headers.username;
    var token = req.headers.token;
    var deviceOs = req.headers.deviceos;
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.status(200).json({ message: 'Bạn phải nhập username!', resultCode: 1 });
      return "";
    }
    if (token == undefined || utils.isEmptyObject(token)) {
      res.status(200).json({ message: 'Bạn phải nhập token!', resultCode: 1 });
      return "";
    }
    if (deviceOs == undefined || utils.isEmptyObject(deviceOs)) {
      res.status(200).json({ message: 'Bạn phải nhập device Os!', resultCode: 1 });
      return "";
    }
    token = token.trim();
    deviceOs = deviceOs.trim();
    username = username.toLowerCase();
    // utils.writeLog("Add firebase token: " + token + "\t" + deviceOs + "\t" + username);
    var keyNotifyInfo = username + "-info";
    redis.hget(config.keyUserNotifies, keyNotifyInfo, function (err, obj) {
      try {
        if (obj == null) {
          obj = "{}";
        }
        var json = JSON.parse(obj);
        listToken = json[deviceOs];
        var lists = listToken.split(",");
        utils.writeLog("lists="+lists);
        //redis.hset(config.keyUserNotifies, keyNotifyInfo, JSON.stringify(json));
        res.json({ errorCode: 1, message: 'Sucess!' });
      } catch (e) {
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog("Error push notify token");
      }
    });
  },
  readAllNotify: function readAllNotify(req, res) {
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.json({ message: 'Input username error!', resultCode: 0 });
      return "";
    }
    var userName = req.headers.username;
    utils.writeLog("Read all notify:" + userName);
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    redis.hget(config.keyUserNotifies, userName, function (err, obj) {
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
        utils.writeLog("Error get notify");
      }
    });
  },
  totalNotifyUnread: function totalNotifyUnread(req, res) {
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.json({ message: 'Input username error!', resultCode: 0 });
      return "";
    }
    var userName = req.headers.username;
    utils.writeLog("Read all notify unread:" + userName);
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    redis.hget(config.keyUserNotifies, userName, function (err, obj) {
      try {
        var data = obj;
        if (data == null) {
          data = "{}";
        }
        data = JSON.parse(data);
        var result = 0;
        var values = Object.values(data);
        for (var i = 0; i < values.length; i++) {
          if (values[i].unread == true) {
            result++;
          }
        }
        res.send({ errorCode: 1, message: 'Success', data: result });
      } catch (e) {
        res.json({ errorCode: 0, message: 'Fail', data: "{}" });
        utils.writeLog("Error get notify");
      }
    });
  },
  updateNotify: function updateNotify(req, res) {
    if (req.headers.username == undefined || utils.isEmptyObject(req.headers.username)) {
      res.json({ message: 'Input username error!', resultCode: 0 });
      return "";
    }
    if (req.headers.id == undefined || utils.isEmptyObject(req.headers.id)) {
      res.json({ message: 'Input notify id error!', resultCode: 0 });
      return "";
    }
    var userName = req.headers.username;
    var id = req.headers.id;
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    redis.hget(config.keyUserNotifies, userName, function (err, obj) {
      try {
        var json = JSON.parse(obj);
        if (json[id] != undefined) {
          json[id].unread = "false";
          redis.hset(config.keyUserNotifies, userName, JSON.stringify(json));
        }
        res.send({ errorCode: 1, message: 'Successfull' });
      } catch (e) {
        res.json({ errorCode: 0, message: 'Fail' });
        utils.writeLog("Error get notify");
      }
    });
  },
  genFireBaseToken: async function genFireBaseToken(req, res) {
    var access_token = await getAccessToken();
    res.setHeader('Content-Type', 'application/json; charset=utf-8');
    res.send({ errorCode: 1, message: 'Successfull', token: access_token });
  }
};

