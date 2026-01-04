<?php

use App\Infrastructure\Container\Container;
use Dotenv\Dotenv;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Database\Capsule\Manager as Capsule;

$dotEnv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotEnv->load();

return (function (Container $container) {

    $container->set(Translator::class, function () {
        return new Translator(
            new ArrayLoader(),
            'en'
        );
    });

    $container->set(Capsule::class, function () {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    });

    $container->set(Factory::class, function (Container $c) {
        /** @var Translator $translator */
        $translator = $c->get(Translator::class);
        $factory = new Factory($translator);

        /** @var Capsule $capsule */
        $capsule = $c->get(Capsule::class);

        $factory->setPresenceVerifier(
            new DatabasePresenceVerifier(
                $capsule->getDatabaseManager()
            )
        );

        return $factory;
    });

    $container->set(PDO::class, function (Container $c) {
        /** @var Capsule $capsule */
        $capsule = $c->get(Capsule::class);
        $connection = $capsule->getConnection();
        return $connection->getPdo();
    });

})($container);
