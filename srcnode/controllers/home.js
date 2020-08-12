class HomeController {

    index(req, res) {
        mysqlTool.validateUsername(req, res);
    }

}

module.exports = new HomeController();

