const cron = require("node-cron");
const https = require('https');
const jobDb = require('./GetData.js');
const Utils = require("../Utils.js");
let app_id = "4a50e4fd-467c-41e9-bda4-4715ec1b833c";

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

function createMessageWithEmail(title, subtitle, content, email, data = null) {
    var message = {
        app_id: app_id,
        contents: { "en": content },
        headings: { "en": title },
        filters: [
            { "field": "tag", "key": "email", "relation": "=", "value": email },
        ],
        data: data
    };

    if (subtitle !== null) {
        message.subtitle = { "en": subtitle };
    }

    return message;
}

function isAfternoonTime() {
    let nowDate = new Date(Date.now());
    let nowHours = nowDate.getHours();
    if (nowHours < 12) {
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

function getMessageForAttire(email, forPant = true, approved = true) {
    var headingCont = "Attire Verification";
    var itemName = (forPant == true) ? "pants" : "shoes";
    var statusStr = (approved == true) ? "approved" : "cancel";
    var content =
        `Your ${itemName} photo was ${statusStr}.\nPlease check it!`;
    var data = {
        push_type: 0,
        data: {
            forPant: forPant,
            approved: approved
        }
    };
    var message = createMessageWithEmail(headingCont, null, content, email, data);
    return message;
}

function getContentWithJobStatus(status) {
    switch (status) {
        case 0:
            return "booked"

        case 1:
            return "confirmed"

        case 4:
            return "canceled"

        case 5:
            return "failed"

        default:
            return "verified"

    }
}

function getMessageForJobStatus(email, status, jobName, hotelName) {
    var headingCont = "Job Confirmation";
    var statusStr = getContentWithJobStatus(status);
    var content =
        `Your job - ${jobName} at ${hotelName} has been ${statusStr}`;
    var data = {
        push_type: 1,
        data: {
            status: status,
            jobName: jobName,
            hotelName: hotelName
        }
    };
    var message = createMessageWithEmail(headingCont, null, content, email, data);
    return message;
}

function sendMessageForAttire(email, forPant = true, approved = true) {
    var message = getMessageForAttire(email, forPant, approved);

    sendNotification(message);
}

function sendMessageForJobStatus(email, status, jobName, hotelName) {
    var message = getMessageForJobStatus(email, status, jobName, hotelName);

    sendNotification(message);
}

function sendToAllUser() {

    var message = {
        app_id: app_id,
        contents: { "en": "Hi DSHR team, here Bui Chung is!" },
        included_segments: ["All"]
    };

    sendNotification(message);
}


function testForSendingApproved(email) {
    sendMessageForAttire(email, true, true);
    sendMessageForAttire(email, false, true);
}


function testForSendingCancel(email) {
    sendMessageForAttire(email, true, false);
    sendMessageForAttire(email, false, false);
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
    startSchedule: startSchedule,
    sendMessageForAttire: sendMessageForAttire,
    sendMessageForJobStatus: sendMessageForJobStatus
};