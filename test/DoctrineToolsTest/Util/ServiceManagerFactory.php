<?php

namespace DoctrineToolsTest\Util;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

/**
 * Factory to build configured service manager for testing purpose
 *
 * @license MIT
 * @author Aleksandr Sandrovskiy <a.sandrovsky@gmail.com>
 */
class ServiceManagerFactory {

	/**
	 * @var
	 */
	protected static $config;

	/**
	 * @param array $config
	 */
	public static function setConfig(array $config) {
		static::$config = $config;
	}

	/**
	 * @return \Zend\ServiceManager\ServiceManager
	 */
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
