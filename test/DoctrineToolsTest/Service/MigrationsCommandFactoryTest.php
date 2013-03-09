<?php
namespace DoctrineToolsTest\Service;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;
use DoctrineToolsTest\Util\ServiceManagerFactory;

class MigrationsCommandFactoryTest extends TestCase {

	/**
	 * @var
	 */
	private $serviceLocator;

	/**
	 * {@inheritDoc}
	 */
	public function setUp() {
		$this->serviceLocator = ServiceManagerFactory::getServiceManager();
		parent::setUp();
	}

	/**
	 * {@inheritDoc}
	 */
	public function tearDown() {
		$this->serviceLocator = null;
		parent::tearDown();
	}

	public function testExecuteFactory() {
		$factory = new \DoctrineTools\Service\MigrationsCommandFactory('execute');
		$command = $factory->createService($this->serviceLocator);
		$this->assertInstanceOf('\Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand', $command);
	}

	public function testDiffFactory() {
		$factory = new \DoctrineTools\Service\MigrationsCommandFactory('diff');
		$command = $factory->createService($this->serviceLocator);
		$this->assertInstanceOf('\Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand', $command);
	}

	public function testThrowException() {
		$this->setExpectedException('InvalidArgumentException');
		$factory = new \DoctrineTools\Service\MigrationsCommandFactory('unknowncommand');
		$command = $factory->createService($this->serviceLocator);
	}
}