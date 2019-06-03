<?php
$queue = require __DIR__ . '/queue.php';

require 'vendor/autoload.php';

use \Jobs\FirstJob;


FirstJob::dispatch("\ndas ist ein test\nmit umbruch");

//use Predis\Client;


//$queue = require __DIR__ . '/queue.php';
//
//$queue->push('SendEmail', ['message' => 'Hello, world!']);

//$client = new Predis\Client();
////$client->set('foo', 'bar');
////
////
////$list = 'foo:result';
//
////$queue->push(new \Jobs\FirstJob($list, [
////  'format' => 'The job is done! Task No. %d',
////  'task'   => 1,
////]));
//
//try {
//  $foo = $client->eval(
//    "-- Pop the first job off of the queue...
//local job = redis.call('lpop', KEYS[1])
//local reserved = false
//
//if(job ~= false) then
//    -- Increment the attempt count and place job on the reserved queue...
//    reserved = cjson.decode(job)
//    reserved['attempts'] = reserved['attempts'] + 1
//    reserved = cjson.encode(reserved)
//    redis.call('zadd', KEYS[2], ARGV[1], reserved)
//end
//
//return {job, reserved}", 2, "queues:default",'queues:default:reserved');
//
//} catch (Exception $e){
//  echo $e;
//}
//echo $foo;
