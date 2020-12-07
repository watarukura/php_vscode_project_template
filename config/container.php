<?php

use Aws\DynamoDb\DynamoDbClient;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use App\Factory\LoggerFactory;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    LoggerFactory::class => function (ContainerInterface $container) {
        return new LoggerFactory($container->get('settings')['logger']);
    },

    // Database connection
    Connection::class => function (ContainerInterface $container) {
        $config = new Configuration();
        $settings = $container->get('settings')['db'];
        /** @phpstan-ignore-next-line */
        return DriverManager::getConnection($settings, $config);
    },

    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getWrappedConnection();
    },

    // DynamoDB connection
    DynamoDbClient::class => function (ContainerInterface $container) {
        $setting = $container->get('settings')['ddb'];
        $sdk = new Aws\Sdk($setting);
        return $sdk->createDynamoDb();
    },
];
