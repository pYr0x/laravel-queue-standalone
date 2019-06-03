<?php
$task_list = [];
exec('tasklist /fi "PID eq 2316"', $task_list);


if(count($task_list) <= 1){
  echo "läuft nicht";
}else{
  echo "läuft";
}
