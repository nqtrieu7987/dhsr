var token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6Ik1pbkhkdWMxMjM0NSIsImlhdCI6MTU0MTA0MzY3OSwiZXhwIjoxNTcyNTc5Njc5fQ.RXDPvB9gjd_06ohq3YYO2lFjXLdPRbB1RbIIxGwpqio";
var jwt = require('jsonwebtoken');
var fs = require('fs');
var cert = fs.readFileSync('certs/educa.vn.key');  // get private key
// jwt.verify(token, cert, function (err, payload) {
//     utils.writeLog(err)
//     utils.writeLog(payload)
// });
var codeFiles = {};
var dirCodeTH = "E:/educa/source/EducaWebApplication/Crawler/bitbucket/datamodel/usercode/"
fs.readdir(dirCodeTH, function (err, items) {
    for (var i = 0; i < items.length; i++) {
        codeFiles[items[i]] = fs.readFileSync(dirCodeTH + items[i]).toString();
    }
    Object.keys(codeFiles).forEach(function (key) {
        var val = codeFiles[key];
        if (val.includes("hungtd")) {
            utils.writeLog(key);
        }
    });
});

// var FtpTools = require('./FtpTools');
// var fileName = 'pictureseminar/ducbot271_201812_1543031093867.mp4';
// FtpTools.uploadMp4(fileName);

// const { MessengerClient } = require('messaging-api-messenger');
// var ACCESS_TOKEN = "EAAZAigzNyzxYBANqtoDBjUvc02v7Kubx6do6vATv1r2grWlZCSGJ91YeOggamlhZAkGZBRYSj59QQKy8N9nZBjcI0YsXIZAvdZBApAGBmUgsUhPn5K7dIKK3oMFJICsJ6t6oTP0ypcoADvDNj73VhmABKZBSv0zzQHyQh8gAklAuOCNujywozYox";
// const client = MessengerClient.connect({
//     accessToken: ACCESS_TOKEN,
//     version: '3.0',
// });

// client.sendRawBody({
//     recipient: {
//         // id: 2022492787814954,
//         id: 1898161780270589,
//     },
//     message: {
//         "text": `Edupia ho tro duoc gi cho quy khach: Đánh giá kết quả tuần từ 09/09 -> 15/09 của em như sau:
//         Tuần vừa rồi em đã học được 5 buổi, với tổng số điểm đạt được là 1798 điểm.
//         - Trong phần từ vựng đã học là 8 từ mới, số điểm đạt được là 799/800 điểm. 
//         - Trong phần mẫu câu đã học là 4 mẫu câu mới, số điểm đạt được là 312/400 điểm. 
//         - Trong phần phát âm đã học là 2 âm mới, số điểm đạt được là 400/400 điểm. 
//         - Trong phần Luyện giao tiếp I–Speak đã học 1 chủ đề mới, số điểm đạt được là 287/400 điểm.
//         Em đã học tốt và nắm chắc yêu cầu nội dung bài học. Em tiếp tục phát huy các kỹ năng học tập và áp dụng linh hoạt các kỹ năng vào thực tế. Bố mẹ hãy đồng hành cùng con để con tiếp tục có những kết quả tốt hơn trong các bài học tới.`
//     },
// }).catch(error => {
//     utils.writeLog(error);
// });
// client.getGreeting().then(greeting => {
//     utils.writeLog(greeting);
//     // [
//     //   {
//     //     locale: 'default',
//     //     text: 'Hello!',
//     //   },
//     // ]
// });
// client.setGreeting([
//     {
//         locale: 'default',
//         text: 'Hello!',
//     },
// ]);