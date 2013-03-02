<?php
namespace DoctrineToolsTest\Mvc\Router\Console;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Console\Request;
use DoctrineTools\Mvc\Router\Console\Colon;

require_once(__DIR__ . '/../../../../../src/DoctrineTools/Mvc/Router/Console/Colon.php');

class ColonTest extends TestCase {

	public function testMatching() {
		$request = new Request(array('scriptname.php', 'migrations:diff'));
		$route = Colon::factory(array(
									 'defaults' => array(
										 'controller' => 'migrations',
										 'action' => 'index'
									 )
								));
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
		$this->assertEquals('migrations', $match->getParam('controller'));
	}

	public function testMatchingWithParams() {
		$request = new Request(array('scriptname.php', 'migrations:diff', '--help'));
		$route = Colon::factory(array(
									 'defaults' => array(
										 'controller' => 'migrations',
										 'action' => 'index'
									 )
								));
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
		$this->assertEquals('migrations', $match->getParam('controller'));
	}

	public function testListMatching() {
		$request = new Request(array('scriptname.php', 'list', 'migrations'));
		$route = Colon::factory(array(
									 'defaults' => array(
										 'controller' => 'migrations',
										 'action' => 'index'
									 )
								));
		$match = $route->match($request);

		$this->assertInstanceOf('Zend\Mvc\Router\Console\RouteMatch', $match, "The route matches");
	}

	public function testNotMatching() {
		$request = new Request(array('scriptname.php', 'orm:diff'));
		$route = Colon::factory(array(
									 'defaults' => array(
										 'controller' => 'migrations',
										 'action' => 'index'
									 )
								));
		$match = $route->match($request);

		$this->assertNull($match, "The route must not match");
	}
}