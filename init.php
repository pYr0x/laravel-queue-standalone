<?php
require "vendor/autoload.php";

use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use Illuminate\Bus\Dispatcher;
use Illuminate\Queue\Connectors\RedisConnector;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Facade;
use twentytwo\BaseContainer;
use DI\ContainerBuilder;
use Illuminate\Queue\Capsule\Manager as QueueCapsule;
use Psr\Container\ContainerInterface;
use twentytwo\facades\AliasLoader;


$laravelDI = new BaseContainer();
BaseContainer::setInstance($laravelDI);



$phpDI = new DI\Container();
$acclimator = new ContainerAcclimator;
$laravelDIContainer = $acclimator->acclimate($laravelDI);

$laravelDI->singleton('laravel', function($container){
  return $container;
});
$laravelDI->singleton('redis.queue.capsule', function($container){
  $queueCapsule   = new QueueCapsule($container);
  $queueCapsule->addConnection([
    'driver'     => 'redis',
    'connection' => 'default',
    'queue'      => 'default',
    'expire'     => 60,
  ]);
//  $queueCapsule->addConnection([
//    'driver'     => 'redis',
//    'connection' => 'default',
//    'queue'      => 'processing',
//    'expire'     => 60,
//  ]);
  return $queueCapsule;
});
$laravelDI->singleton('redis', function ($container) {
  $redis = new RedisManager('predis', [
    'client' => 'predis',
    'default' => [
      'host' => '127.0.0.1',
      //      'password' => env('REDIS_PASSWORD', null),
      'port' => 6379,
      'database' => 0,
    ]
  ]);

  $container['redis.queue.capsule']->getQueueManager()->addConnector('redis',  function () use ($redis) {
    return new RedisConnector($redis);
  });
  return $redis;
});
$laravelDI->singleton('redis.queue', function ($container) {
  return new \Illuminate\Queue\RedisQueue($container['redis']);
});
$laravelDI->singleton(Dispatcher::class, function ($container) {
  return new Dispatcher($container, function ($connection = null) use ($container) {
    return $container["redis.queue"];
  });
});
$laravelDI->alias('Illuminate\Bus\Dispatcher','Illuminate\Contracts\Bus\Dispatcher');


$container = new CompositeContainer();


// Configure PHP-DI container
$builder = new ContainerBuilder();
$builder->wrapContainer($container);
//$builder->useAnnotations(true);
$builder->useAutowiring(true);


$builder->addDefinitions([
  'di' => function(ContainerInterface $c) {
    return $c;
  }
//  'redis' => function(ContainerInterface $c) use ($queueCapsule) {
//
//    $redis = new RedisManager('predis', [
//      'client' => 'predis',
//      'default' => [
//        'host' => '127.0.0.1',
//        //      'password' => env('REDIS_PASSWORD', null),
//        'port' => 6379,
//        'database' => 0,
//      ]
//    ]);
//
//    $queueCapsule->getQueueManager()->addConnector('redis',  function () use ($redis) {
//      return new RedisConnector($redis);
//    });
//
//    return $redis;
//
//  },
//  'redus.queue' => function(ContainerInterface $c) use ($queueCapsule) {
//    return new \Illuminate\Queue\RedisQueue($c['redis']);
//  },
//  \Illuminate\Bus\Dispatcher::class => function (ContainerInterface $c) use ($laravelDI) {
//    return new \Illuminate\Bus\Dispatcher($laravelDI, function ($connection = NULL) use ($c) {
//      return $c["redis.queue"];
//    });
//  }
]);





$phpDIContainer = $builder->build();

$container->addContainer($laravelDIContainer);
$container->addContainer($phpDIContainer);

$bridge = new \twentytwo\Bridge($container);

/** FACADES */
$aliases = [];
Facade::setFacadeApplication($bridge);
AliasLoader::getInstance($aliases)->register();


if (! function_exists('app')) {
  function app($abstract = null)
  {
    global $container;
    if (is_null($abstract)) {
      return $container;
    }
    return $container->get($abstract);
  }
}
