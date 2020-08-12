var words = require('./words.json');
var bai1 = require('./json/bai1.json');
utils.writeLog(Object.keys(bai1).length);
// utils.writeLog(Object.keys(words).length);
for (var i = 0; i < Object.keys(words).length; i++) {
    var key = Object.keys(words)[i];
    bai1[key] = words[key];
}
utils.writeLog(Object.keys(bai1).length);
// xoa ""
var utils = require("./Utils.js");
var wordsNew = {};
var counter = 0;
for (var i = 0; i < Object.keys(bai1).length; i++) {
    counter++;
    utils.writeLog("Process " + counter + "/" + Object.keys(bai1).length + " " + i);
    var key = Object.keys(bai1)[i];
    wordsNew[key] = {
        "true": [], "false": []
    };
    for (var j = 0; j < bai1[key]["false"].length; j++) {
        if (!utils.isEmptyObject(bai1[key]["false"][j])) {
            wordsNew[key]["false"].push(bai1[key]["false"][j]);
        }
    }
    for (var k = 0; k < bai1[key]["true"].length; k++) {
        if (!utils.isEmptyObject(bai1[key]["true"][k])) {
            wordsNew[key]["true"].push(bai1[key]["true"][k]);
        }
    }
}
var jsonfile = require('jsonfile')
jsonfile.writeFile('wordnew.json', wordsNew, function (err) {
    if (err) throw err;
    utils.writeLog('Saved!');
});
