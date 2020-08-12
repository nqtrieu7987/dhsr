var mongoTools = require("../Mongo.js");
var utils = require("../Utils.js");
exports.formData = function (req, res) {
    utils.writeLog("Access api!");
    var url = req.body.url_parameter;
    if (typeof (req.body.url_parameter) != "undefined") {
        req.body.url_parameter = JSON.parse(req.body.url_parameter);
    }
    mongoTools.addFormData(req.body);
    return res.json(req.body);
};