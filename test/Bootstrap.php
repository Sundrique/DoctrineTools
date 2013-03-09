<?php
chdir(__DIR__);

if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
	$loader = include __DIR__ . '/../../../vendor/autoload.php';
} else {
	throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$loader->add('DoctrineToolsTest\\', __DIR__);
$loader->add('DoctrineTools\\', __DIR__ . '/../src/');

if (file_exists(__DIR__ . '/TestConfig.php')) {
	$config = require __DIR__ . '/TestConfig.php';
} else {
	$config = require __DIR__ . '/TestConfig.php.dist';
}

\DoctrineToolsTest\Util\ServiceManagerFactory::setConfig($config);