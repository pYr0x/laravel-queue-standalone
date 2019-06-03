<?php
file_put_contents("worker", getmypid());

require 'vendor/autoload.php';

use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

$queue = require __DIR__ . '/queue.php';

$container     = $capsule->getContainer();
$dispatcher = new Dispatcher($container);

$worker = new Worker($queue->getQueueManager(), $dispatcher);
$connection = 'default';
$queue = NULL;
$delay = 0;
$memory = 128;
$timeout = 60;
$sleep = 1;
$maxTries = 3;
// $worker->daemon($connection);
$options = new WorkerOptions($delay, $memory, $timeout, $sleep, $maxTries);
while (TRUE) {
  try {

    $worker->daemon($connection, 'default', $options);
  } catch (Exception $e) {
    echo $e->getTraceAsString();
  } catch (Throwable $e) {
    echo $e->getTraceAsString();
  }
}
