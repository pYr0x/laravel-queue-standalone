<?php
require 'vendor/autoload.php';

include "Container.php";

//use Illuminate\Container\Container;
use Illuminate\Bus\Dispatcher;
//use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Queue\Factory as QueueFactory;
use Illuminate\Encryption\Encrypter;
use Illuminate\Queue\Capsule\Manager as Capsule;
use Illuminate\Queue\Connectors\RedisConnector;
use Illuminate\Queue\Failed\NullFailedJobProvider;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;


$laravelDI = new Container();
//$dispatcher = new Dispatcher($laravelDI);


$capsule   = new Capsule($laravelDI);
$container     = $capsule->getContainer();
$capsule->addConnection([
  'driver'     => 'redis',
  'connection' => 'default',
  'queue'      => 'default',
  'expire'     => 60,
]);

$manager = $capsule->getQueueManager();

$container->singleton('redis', function ($app) {
  return new RedisManager('predis', [
    'client' => 'predis',
    'default' => [
      'host' => '127.0.0.1',
//      'password' => env('REDIS_PASSWORD', null),
      'port' => 6379,
      'database' => 0,
    ]
  ]);
});

$container->singleton('redis.queue', function ($app) {
  return new \Illuminate\Queue\RedisQueue($app['redis']);
});


$container->singleton(ExceptionHandler::class, function(){
  return new NullFailedJobProvider();
});

$manager->addConnector('redis', function () use ($container) {
  return new RedisConnector(
    $container['redis']
  );
});

$container->singleton('encrypter', function () {
  return new Encrypter('blahfkso;lkfoles');
});

$container->singleton(Dispatcher::class, function ($container) {
  return new Dispatcher($container, function ($connection = null) use ($container) {
    return $container["redis.queue"];
  });
});
$container->alias(
  'Illuminate\Bus\Dispatcher',
  'Illuminate\Contracts\Bus\Dispatcher'
);
$container->alias(
  'Illuminate\Bus\Dispatcher',
  'Illuminate\Contracts\Bus\QueueingDispatcher'
);
// resolve Container to instance
// $container->instance(Container::class, $container);
$capsule->setAsGlobal();
return $capsule;
