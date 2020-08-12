// var ftpClient = require('ftp-client');

var ftp_config = {
    host: '118.68.218.34',
    port: 21,
    user: 'sg-resource',
    password: 'yJP).7=v',
    keepalive: 10000
}

// var ftp_config = {
//     host: '181d5ecac-vorigin.vws.vegacdn.vn',
//     port: 21,
//     user: 'user_475f668342179bc1181',
//     password: '1907ff508d2891dc9b12',
//     keepalive: 10000
// }
// var options = {
//     logging: 'basic'
// }
// var client = new ftpClient(ftp_config, options);
// client.connect(function () {
//     // client.upload(["pictureseminar/chuyennd.mp4"], '/uploadmp4/pictureseminar', {
//     //     baseDir: 'pictureseminar',
//     //     overwrite: 'older'
//     // }, function (result) {
//     //     utils.writeLog(result);
//     // });
// });

// var uploadMp4 = function (fileLocal) {
//     client.upload(fileLocal, '/uploadmp4/pictureseminar', {
//         baseDir: 'pictureseminar',
//         overwrite: 'older'
//     }, function (result) {
//         utils.writeLog(result);
//     });
// }

// module.exports = {
//     uploadMp4: uploadMp4
// }

var ftpClient = require('ftp');

var client = new ftpClient();

function checkConnect () {
    utils.writeLog("FTP connected:" + client.connected);
    if ( !client.connected ) {
        client.connect(ftp_config);
        utils.writeLog("FTP connected againt:" + client.connected);
    }
}

var uploadMp4 = function (fileLocal) {

    checkConnect();

    client.on('ready', function () {
        client.put(fileLocal, 'uploadmp4/' + fileLocal, function (err) {
            utils.writeLog("FtpTool:" + err);
            client.end();
        });
    });

    // client.connect(ftp_config);
}

var uploadMp3Seminar = function (fileLocal) {

    checkConnect();

    client.on('ready', function () {
        client.put(fileLocal, 'uploadmp3seminar/' + fileLocal, function (err) {
            utils.writeLog("FtpTool MP3:" + err);
            client.end()
        });
    });

    // client.connect(ftp_config);
}

var uploadImageCover = function (fileLocal) {

    checkConnect();

    client.on('ready', function () {
        client.put(fileLocal, 'seminarpicture/' + fileLocal, function (err) {
            utils.writeLog("FtpTool image cover:" + err);
            client.end();
        });
    });

    // client.connect(ftp_config);
}

var uploadFile = function (fileLocal) {

    checkConnect();

    client.on('ready', function () {
        client.put(fileLocal, 'files/' + fileLocal, function (err) {
            utils.writeLog("FtpTool file:" + err);
            if (err) throw err;
            client.end();
        });
    });

    // client.connect(ftp_config);
}

// utils.writeLog("FTP connected:" + client.connected);

//loop check connection and reconnect when connect not avalible
// client.connect(ftp_config);

module.exports = {
    uploadMp4: uploadMp4,
    uploadMp3Seminar: uploadMp3Seminar,
    uploadImageCover: uploadImageCover
}