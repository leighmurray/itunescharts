<?php

use Doctrine\DBAL\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/../bootstrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($container['em']);