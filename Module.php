<?php
namespace DoctrineTools;

use Zend\ModuleManager\Feature;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface;
use Zend\Mvc\MvcEvent;
use DoctrineTools\Component\Console\Input\StringInput;
use DoctrineTools\Component\Console\Output\PropertyOutput;

class Module implements Feature\AutoloaderProviderInterface, Feature\ConfigProviderInterface {

	private $serviceManager;

	public function onBootstrap(MvcEvent $event){
		$this->serviceManager = $event->getApplication()->getServiceManager();
	}

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}

	public function getConsoleUsage(Console $console) {
		$input = new StringInput('list');
		$output = new PropertyOutput();

		$cli = $this->serviceManager->get('doctrinetools.console_application');

		$cli->run($input, $output);

		return $output->getMessage();
	}
}