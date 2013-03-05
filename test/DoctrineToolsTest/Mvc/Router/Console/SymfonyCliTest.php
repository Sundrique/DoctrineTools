<?php
namespace DoctrineToolsTest\Mvc\Router\Console;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Console\Request;
use DoctrineTools\Mvc\Router\Console\SymfonyCli;
use DoctrineToolsTest\Util\ServiceManagerFactory;
use Zend\Mvc\Router\RoutePluginManager;

class SymfonyCliTest extends TestCase {

	private $serviceLocator;

	private $routePluginManager;

	public function setUp() {
		$this->serviceLocator = ServiceManagerFactory::getServiceManager();
		$this->routePluginManager = new RoutePluginManager();
		$this->routePluginManager->setServiceLocator($this->serviceLocator);
		parent::setUp();
	}

	public function tearDown() {
		$this->serviceLocator = null;
		parent::tearDown();
	}


	public function testMatching() {
		$request = new Request(array('scriptname.php', 'migrations:diff'));
		$route = new SymfonyCli();
		$route->setServiceLocator($this->routePluginManager);
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
	}

	public function testMatchingWithParams() {
		$request = new Request(array('scriptname.php', 'migrations:diff', '--help'));
		$route = new SymfonyCli();
		$route->setServiceLocator($this->routePluginManager);
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
	}

	public function testListMatching() {
		$request = new Request(array('scriptname.php', 'list', 'migrations'));
		$route = new SymfonyCli();
		$route->setServiceLocator($this->routePluginManager);
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
	}

	public function testNotMatching() {
		$request = new Request(array('scriptname.php', 'orm:diff'));
		$route = new SymfonyCli();
		$route->setServiceLocator($this->routePluginManager);
		$match = $route->match($request);

		$this->assertNull($match, "The route must not match");
	}
}