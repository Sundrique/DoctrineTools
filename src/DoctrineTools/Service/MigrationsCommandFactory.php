<?php
namespace DoctrineTools\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service factory for migrations command
 *
 * @license MIT
 * @author Aleksandr Sandrovskiy <a.sandrovsky@gmail.com>
 */
class MigrationsCommandFactory implements FactoryInterface {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param $name
	 */
	public function __construct($name) {
		$this->name = ucfirst(strtolower($name));
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
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