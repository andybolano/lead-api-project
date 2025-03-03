<?php

use App\Domain\Repositories\LeadRepositoryInterface;
use App\Infrastructure\Persistence\LeadRepository;
use App\Infrastructure\Persistence\LeadSender;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use GuzzleHttp\Client;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $container) {
            return new PDO(
                'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8mb4',
                getenv('DB_USER'),
                getenv('DB_PASS'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        },

        LoggerInterface::class => function () {
            $logger = new Logger('slim-app');
            $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));
            return $logger;
        },

        LeadRepositoryInterface::class => function (ContainerInterface $container) {
            return new LeadRepository($container->get(PDO::class));
        },

        LeadSender::class => function (ContainerInterface $container) {
            return new LeadSender(
                $container->get(Client::class),
                $container->get(LoggerInterface::class),
                $container->get(SettingsInterface::class)->get('api')['marketing_webhook_url']
            );
        },

        Client::class => function () {
            return new Client([
                'timeout' => 10,
                'connect_timeout' => 5,
                'verify' => false,
            ]);
        },
    ]);
};
