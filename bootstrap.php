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
    return new ChartUpdater($c['em'], $c['fb_writer']);
};

$container['config'] = function ($c) {
    return \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/config/config.yml'));
};

$container['fb_writer'] = function ($c) {
    return new FacebookWriter(
        $c['config']['facebook']['app_id'],
        $c['config']['facebook']['app_secret'],
        $c['config']['itunes']['affiliate_link']
    );
};