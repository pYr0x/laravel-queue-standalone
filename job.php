<?php
require 'vendor/autoload.php';

require_once "init.php";

//\app\jobs\WriteFile::dispatch(date('d.m.Y H:i:s'))->delay(new \DateInterval('PT1M'))->onQueue('processing');
\app\jobs\WriteFile::dispatch(date('d.m.Y H:i:s'))->delay(new \DateInterval('PT1M'))->onQueue('processing');
