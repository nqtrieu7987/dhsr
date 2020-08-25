const cron = require("node-cron");
const https = require('https');
const jobDb = require('./GetData.js');
const Utils = require("../Utils.js");
let app_id = "4a50e4fd-467c-41e9-bda4-4715ec1b833c";

function sendToAllUser() {

    var message = {
        app_id: app_id,
        contents: { "en": "Hi DSHR team, here Bui Chung is!" },
        included_segments: ["All"]
    };

    sendNotification(message);
}

function createMessage(title, subtitle, content) {
    var message = {
        app_id: app_id,
        contents: { "en": content },
        headings: { "en": title },
        included_segments: ["All"]
    };

    if (subtitle !== null) {
        message.subtitle = { "en": subtitle };
    }

    return message;
}

function isAfternoonTime() {
    let nowDate = new Date(Date.now());
    let nowHours = nowDate.getHours();
    if (nowHours > 12) {
        return false;
    }

    return true;
}

function getMessageCheckYourBooking() {
    var headingCont = "Good morning";
    if (isAfternoonTime()) {
        headingCont = "Good afternoon";
    }

    var message = createMessage(headingCont, null, "Please check Your Booking 📋");
    return message;
}

function getMessageForNewJob(job) {
    var headingCont = "Good morning";
    if (isAfternoonTime()) {
        headingCont = "Good afternoon";
    }
    headingCont += ", please check the new job"

    const date = job.start_date;
    var day = date.getDate(),
        month = date.getMonth(),
        year = date.getFullYear();

    var content =
        `${job.slot} slot in ${job.hotel_name} @ ${job.start_time} ${day}/${month}/${year}.\n${job.name}`;

    var message = createMessage(headingCont, null, content);

    sendNotification(message);
}

async function getNewJob() {
    const newJobs = await jobDb.getNewJobs();
    return newJobs
}

async function sendToAllDevices() {
    const newJob = await getNewJob();
    var message;
    if (newJob.length === 0) {
        message = getMessageCheckYourBooking();
    } else {
        message = getMessageForNewJob(newJob[0]);
    }

    sendNotification(message);
}

function sendNotification(data) {
    var headers = {
        "Content-Type": "application/json; charset=utf-8",
        "Authorization": "Basic NGY4ODZjNzMtYjIxYy00ODVhLTk1NDgtNjU2NTgwNzA5Mzli"
    };

    var options = {
        host: "onesignal.com",
        port: 443,
        path: "/api/v1/notifications",
        method: "POST",
        headers: headers
    };

    var req = https.request(options, function (res) {
        res.on('data', function (data) {
            console.log("Response:");
            console.log(JSON.parse(data));
        });
    });

    req.on('error', function (e) {
        console.log("ERROR:");
        console.log(e);
    });

    req.write(JSON.stringify(data));
    req.end();
};

function startSchedule() {
    // schedule tasks to be run on the server
    cron.schedule("0 9,15 * * *", function () {
        console.log("running a task every minute");
        sendToAllDevices();
    }, {
        timezone: 'Asia/Kuala_Lumpur'
    });
}

module.exports = {
    startSchedule: startSchedule
};