<?php
namespace DoctrineTools\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MigrationsCommandFactory implements FactoryInterface {

	private $name;

	public function __construct($name) {
		$this->name = ucfirst(strtolower($name));
	}

	public function createService(ServiceLocatorInterface $serviceLocator) {
		$className = '\Doctrine\DBAL\Migrations\Tools\Console\Command\\' . $this->name . 'Command';
		if (class_exists($className)) {
			$configuration = $serviceLocator->get('doctrinetools.migrations_configuration');

			$command = new $className;
			$command->setMigrationConfiguration($configuration);

			return $command;
		} else {
			throw new \InvalidArgumentException;
		}
	}
}