<?php

require 'vendor/autoload.php';

require_once __DIR__ .'/iTunesRSS.php';
require_once __DIR__ .'/ChartUpdater.php';
require_once __DIR__ .'/FacebookWriter.php';

$container = new \Pimple\Container();

$container['db_params'] = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/itunes.db'
];

$container['em'] = function ($c) {
    $configuration = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities']);
    $configuration->setProxyDir(__DIR__ . "/proxies");
    return \Doctrine\ORM\EntityManager::create($c['db_params'], $configuration);
};

$container['chart_updater'] = function ($c) {
    return new ChartUpdater($c['em']);
};