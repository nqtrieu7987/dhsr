var utils = require("./Utils.js");
module.exports = {
    uploadCdnResource: function uploadCdnResource(req, res) {
        var localPath = req.headers.localpath;
        var cdnFolder = req.headers.cdnfolder;
        if (localPath == undefined || utils.isEmptyObject(localPath)) {
            res.json({ message: 'Input local path error!', resultCode: 0 });
            return "";
        }
        if (cdnFolder == undefined || utils.isEmptyObject(cdnFolder)) {
            res.json({ message: 'Input cdn folder error!', resultCode: 0 });
            return "";
        }
        let mp3File = req.files.mp3File;
        // Use the mv() method to place the file somewhere on your server					
        var fileName = 'upload/avatar';
        var time = new Date().getTime();
        fileName += time + ".png";
        mp3File.mv(fileName, function (err) {
            if (err)
                return res.status(500).send(err);            
            res.json({ message: 'File uploaded!', resultCode: 1 });
        });
    }
};