<?php

namespace DoctrineTools;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Mvc\MvcEvent;
use DoctrineTools\Component\Console\Input\StringInput;
use DoctrineTools\Component\Console\Output\PropertyOutput;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

/**
 * Doctrine Tools module
 *
 * @license MIT
 * @author Aleksandr Sandrovskiy <a.sandrovsky@gmail.com>
 */
class Module implements Feature\AutoloaderProviderInterface, Feature\ConfigProviderInterface {

	private $serviceManager;

	/**
	 * {@inheritDoc}
	 */
	public function onBootstrap(MvcEvent $event) {
		$this->serviceManager = $event->getApplication()->getServiceManager();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAutoloaderConfig() {
		return array(
			AutoloaderFactory::STANDARD_AUTOLOADER => array(
				StandardAutoloader::LOAD_NS => array(
					__NAMESPACE__ => __DIR__,
				),
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfig() {
		return include __DIR__ . '/../../config/module.config.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConsoleUsage(Console $console) {
		$input = new StringInput('list');
		$output = new PropertyOutput();

		$cli = $this->serviceManager->get('doctrinetools.console_application');

		$cli->run($input, $output);

		return $output->getMessage();
	}
}