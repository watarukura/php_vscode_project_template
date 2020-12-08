<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database
$settings['db']['user'] = 'user';
$settings['db']['password'] = 'pass';
$settings['db']['dbname'] = 'test';
$settings['db']['host'] = 'db:3306';

// DynamoDB
//$settings['ddb']['profile'] = 'local';
$settings['ddb']['endpoint'] = 'http://ddb:8000';
$settings['ddb']['region'] = 'ap-northeast-1';
