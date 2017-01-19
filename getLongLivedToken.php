<?php

require_once __DIR__ .'/bootstrap.php';

$token = '';

$longLivedToken = $container['fb_writer']->getLongLivedToken($token);

var_dump($longLivedToken);