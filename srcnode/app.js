'use strict';
var logTools = require("./LogTools");
var utils = require("./Utils.js");
var nofity = require("./Notify.js");
var mysqlTool = require("./MysqlTool.js");
var express = require('express');
var cors = require('cors')
var fs = require('fs');
var jwt = require('jsonwebtoken');
var cert = fs.readFileSync('./educa.vn.key');  // get private key
const fileUpload = require('express-fileupload');
var bodyParser = require('body-parser');
var config = require('./config.json');
var avatar_upload = '/var/www/log/avatar/';
var ip = require("ip");
var HomeController = require('./controllers/home');
var writelog = require("./WriteLog.js");
var getData = require("./controllers/GetData.js");

module.exports.create = function (server, host, port, publicDir) {
	var app = express();
	app.use(cors());
	app.use(fileUpload());
	app.use(bodyParser.json({ limit: '5mb' }));
	app.use(bodyParser.json());
	app.use(bodyParser.urlencoded({ extended: false }));

	app.get('/service/check/avalible', function (req, res) {
		res.setHeader('Content-Type', 'application/json; charset=utf-8');
		res.send({ message: "Successfull!", resultCode: 0 });
	});

	// 5. Gen token
	app.post('/service/gentoken', function (req, res) {
		mysqlTool.getToken(req, res);
	});

	// 6. Verify token
	app.post('/service/verifytoken', function (req, res) {
		utils.writeLog("Verify token for user:" + req.headers.email);
		var email = req.headers.email;
		var jwtToken = req.headers.token;
		try {
			email = email.toLowerCase();
		} catch (error) { }
		jwt.verify(jwtToken, cert, function (err, payload) {
			if (err) {
				// utils.writeLog(err);
				res.json({ message: 'Token invalid!', resultCode: 0 });
			} else {
				// utils.writeLog('decoder: ' + payload.username);
				if (payload.email == email) {
					res.json({ message: 'Authorized successfull!', resultCode: 1 });
				} else {
					res.json({ message: 'User invalid!', resultCode: 0 });
				}
			}
		});
	});

	// 38. user manager
	app.post('/service/user/create', function (req, res) {
		mysqlTool.createUser(req, res);
	});

	app.post('/service/user/update', function (req, res) {
		mysqlTool.updateUser(req, res);
	});

	app.post('/service/user/validate', function (req, res) {
		mysqlTool.validateUsername(req, res);
	});
	app.post('/service/user/login', function (req, res) {
		mysqlTool.loginAuth(req, res);
	});
	app.post('/service/user/resetpass', function (req, res) {
		mysqlTool.resetPass(req, res);
	});
	app.post('/service/users', function (req, res) {
		mysqlTool.getUserInfor(req, res);
	});
	// dang ki mua goi
	app.post('/service/user/register', function (req, res) {
		mysqlTool.register(req, res);
	});
	app.post('/service/user/register_package', function (req, res) {
		mysqlTool.registerPackage(req, res);
	});

	// 53. cac api notify
	app.post('/service/notify/getall', function (req, res) {
		nofity.readAllNotify(req, res);
	});
	app.post('/service/notify/totalunread', function (req, res) {
		nofity.totalNotifyUnread(req, res);
	});
	app.post('/service/notify/markread', function (req, res) {
		nofity.updateNotify(req, res);
	});
	app.post('/service/user/addfirebasetoken', function (req, res) {
		nofity.addFireBaseToken(req, res);
	});
	app.post('/service/user/removefirebasetoken', function (req, res) {
		nofity.removeFireBaseToken(req, res);
	});
	app.post('/service/gentokenfirebase', function (req, res) {
		nofity.genFireBaseToken(req, res);
	});
	app.post('/service/user/forgetpass', function (req, res) {
		mysqlTool.forgetPass(req, res);
	});
	app.post('/service/user/verify_otp_phone', function (req, res) {
		mysqlTool.verifyOtpPhone(req, res);
	});
	app.post('/service/user/upload_image', function (req, res) {
		mysqlTool.userUploadsImage(req, res);
	});
	app.post('/service/hotel', function (req, res) {
		mysqlTool.getListHotel(req, res);
	});
	app.post('/service/hotel/info', function (req, res) {
		mysqlTool.getHotelInfo(req, res);
	});

	app.post('/service/hotel/job', async function (req, res) {
		var datas = await getData.getListJobOnGoing(req, res);
		res.json({ message: 'Success', resultCode: 0, data: datas });
		// res.send(datas);
		return "";
	});

	app.post('/service/job/booking', function (req, res) {
		mysqlTool.jobBooking(req, res);
	});

	app.post('/service/job/cancel', function (req, res) {
		mysqlTool.jobCancel(req, res);
	});

	app.post('/service/job/myjob', async function (req, res) {
		var datas = await getData.getMyJob(req, res);
		res.json({ message: 'Success', resultCode: 0, data: datas });
		// res.send(datas);
		return "";
	});

	app.post('/service/job/update_status', async function (req, res) {
		mysqlTool.updateStatusAllJob(req, res);
	});

	app.post('/service/user/upload_image_thumb', function (req, res) {
		mysqlTool.userUploadsImageThumb(req, res);
	});

	app.post('/service/job/check_in_check_out', function (req, res) {
		mysqlTool.userUploadsImageThumb(req, res);
	});
	return app;
};
