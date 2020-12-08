<?php

// Configure defaults for the whole application.

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');

// Timezone
date_default_timezone_set('Asia/Tokyo');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

// Error handler
$settings['error'] = [
    // Should be set to false in production
    'display_error_details' => true,
    // Should be set to false for unit tests
    'log_errors'            => true,
    // Display error details in error log
    'log_error_details'     => true,
];

// Logger settings
$settings['logger'] = [
    'name'            => 'app',
    'path'            => __DIR__ . '/../logs',
    'filename'        => 'app.log',
    'level'           => \Monolog\Logger::DEBUG,
    'file_permission' => 0775,
];

// Database settings
$settings['db'] = [
    'driver'           => 'pdo_mysql',
    'encoding'         => 'utf8mb4',
    'charset'          => 'utf8mb4',
    'collation'        => 'utf8mb4_bin',
    // Enable identifier quoting
    'quoteIdentifiers' => true,
    // Set to null to use MySQL servers timezone
    'timezone'         => 'Asia/Tokyo',
    // Disable meta data cache
    'cacheMetadata'    => false,
    // Disable query logging
    'log'              => false,
    // PDO options
    'driverOptions'    => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT         => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES   => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];

// Database settings
$settings['ddb'] = [
    'version' => '2012-08-10',
];

return $settings;
