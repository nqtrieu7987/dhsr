var gcm = require('node-gcm');
// Replace these with your own values.
var apiKey = "AAAAppj3gGI:APA91bE0__0PMRvxVgdal588YOp1Jx3uv_V_RGTUY1erMSxCU7HHaT2A1vFTi3gFRjv8kBSyC2_ss2AaC46CKqtQv2NIzg119ALoRUPTyXMW3WS00e_ng4-MyaFqTKy-rKIiuxPyLc73XrixCOal8SOEECH8kSlEKQ";
var deviceID = "ctpOZl4cBnY:APA91bGr4oxCF5dGeVfULcN-KA6mXShwa3NbU6oFp1kZrfbHDsHJV6isv5MAABmS6X2UEDbu1-7XbA9B5a91kJLJyFN1wmq8-oi5Ctujn9adNDMwVSn1NkP7XI2W9WxJx2xEdlvei8Ym";
var service = new gcm.Sender(apiKey);
var message = new gcm.Message();
message.addData('title', 'Test Title');
message.addData('message', 'Test message.');
service.send(message, { registrationTokens: [deviceID] }, function (err, response) {
    if (err) console.error(err);
    else utils.writeLog(response);
});