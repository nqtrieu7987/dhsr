var mongo = require('mongodb');
var MongoClient = mongo.MongoClient;
var dbo;
var db;
// var mongoAddress = "103.216.120.126";
var mongoAddress = "172.25.80.83";
MongoClient.connect('mongodb://' + 'educa' + ':' + 'Educa_2018**' + '@' + mongoAddress + ':27017' + '/' + 'educa', function (err, dbresult) {
    // var mongoAddress = "172.25.80.83";
    // MongoClient.connect('mongodb://' + 'user_mongo' + ':' + 'Educa_2018**' + '@' + mongoAddress + ':27017' + '/' + 'educa', function(err, dbresult) {
    if (err)
        utils.writeLog(err);
    else {
        utils.writeLog('Mongo Connected!');
        db = dbresult;
        dbo = db.db("educa");
        var obj = { userid: "chuyennd", video_id: "22412", point: "67", result: "[]", time: new Date().getTime() }
        var collection = dbo.collection('customers');  // get reference to the collection  
        collection.insertOne(obj, function (err, res) {
            if (err) throw err;
            utils.writeLog("Insert customer coin:")
            utils.writeLog(obj);
        });
    }
});
function closeConnection() {
    // close db connection
    db.close();
}

