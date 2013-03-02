<?php
namespace DoctrineTools;

use Zend\ModuleManager\Feature,
	Zend\Console\Adapter\AdapterInterface as Console;

class Module implements Feature\AutoloaderProviderInterface, Feature\ConfigProviderInterface {

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__.'/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__
				)
			)
		);
	}

	public function getConfig() {
		return include __DIR__.'/config/module.config.php';
	}
}