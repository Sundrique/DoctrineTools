<?php

namespace DoctrineToolsTest\Util;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

class ServiceManagerFactory {

	protected static $config;

	public static function setConfig(array $config) {
		static::$config = $config;
	}

	public static function getServiceManager() {
		$serviceManager = new ServiceManager(new ServiceManagerConfig(
			isset(static::$config['service_manager']) ? static::$config['service_manager'] : array()
		));
		$serviceManager->setService('ApplicationConfig', static::$config);
		$serviceManager->setFactory('ServiceListener', 'Zend\Mvc\Service\ServiceListenerFactory');

		$moduleManager = $serviceManager->get('ModuleManager');
		$moduleManager->loadModules();
		return $serviceManager;
	}
}
