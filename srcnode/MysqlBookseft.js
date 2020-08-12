var config = require('./config.json');
var utils = require("./Utils.js");
var ip = require("ip");
var db_config = config.educadbLab;
if (ip.address().includes("172.25.80")) {
  db_config = config.educadbPro;
}
var knex = require('knex')({
  dialect: 'pg',
  acquireConnectionTimeout: 4 * 1000,
  pool: {
    min: 2,
    max: 2,
    idleTimeoutMillis: 5 * 1000,
    acquireTimeoutMillis: 3 * 1000,
    evictionRunIntervalMillis: 5 * 1000
  },
  client: 'mysql',
  connection: db_config
});
var bookshelf = require('bookshelf')(knex);


// crm db config 
var db_config_crm = config.crmDbLab;
if (ip.address().includes("172.25.80")) {
  db_config_crm = config.crmDbPro;
}
var knexCrm = require('knex')({
  dialect: 'pg',
  acquireConnectionTimeout: 4 * 1000,
  pool: {
    min: 2,
    max: 2,
    idleTimeoutMillis: 5 * 1000,
    acquireTimeoutMillis: 3 * 1000,
    evictionRunIntervalMillis: 5 * 1000
  },
  client: 'mysql',
  connection: db_config_crm
});
var bookshelfCrm = require('bookshelf')(knexCrm);
var crmTelesaleRealtime = bookshelfCrm.Model.extend({
  tableName: 'crm_telesale_realtime'
});

async function getContact(phone) {
  return new Promise(resolve => {
    crmTelesaleRealtime.where('id', phone).fetch().then(function (contact) {
      resolve(contact);
    });
  });
}
async function updateContact(contact, sql) {
  return new Promise(resolve => {
    try {
      contact.save(sql)
        .then(function (row) {
          resolve(true);
        });
    } catch (e) {
      resolve(false);
    }
  });
}

async function asyncCall() {
  redis.zrange(config.contactSorted, 0, 10, function (err, list) {
    if (err) throw err;
    console.log("list:", list);
    for (var i = 0; i < list.length; i++) {
      var contact = await getContact(list[i]);
      if (contact != null) {
        console.log(contact.toJSON().id)
      }
      // redis.zrem(config.contactSorted, element);
    }
  });
}
asyncCall();