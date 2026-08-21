<?php

declare(strict_types=1);

use Mago\Sdk\Worker;
use MagoCakePHP\CakePhpExtension;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

(new Worker(CakePhpExtension::create()))->run();
