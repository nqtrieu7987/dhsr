const request = require('supertest');
const express = require('express');
const app = express();
// const app = require('../bin/www');

describe('GET /', () => {

	test('respond success', () => {
      request(app).get('/getgames').expect(200)
      request(app).post('/getgames').expect(200)
	});

});