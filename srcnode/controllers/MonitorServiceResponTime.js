var request = require('request');
var moment = require('moment');
var sleep = require('system-sleep');
async function postRequest() {
    return new Promise(resolve => {
        request({
            url: "https://edupia.vn/service/userinfo",
            method: "POST",
            json: true,   // <--Very important!!!
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'username': "ducbot271"
            }
        }, function (error, response, body) {
            resolve(response);
        });
    });
}
async function asyncCall() {
    var startTime = new Date().getTime();
    await postRequest();
    writeLog("Time respon: " + (new Date().getTime() - startTime));
}
function writeLog(msg) {
    var current_time = new moment().format("YYYY-MM-DD HH:mm:ss");
    var wr = current_time + ' ' + msg;
    console.log(wr);
}
while (true) {
    try {
        asyncCall();
    } catch (err) {
        console.error(err)
    }
    sleep(60 * 1000);
}

