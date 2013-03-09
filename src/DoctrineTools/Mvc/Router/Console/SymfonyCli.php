<?php
namespace DoctrineTools\Mvc\Router\Console;

use Zend\Mvc\Router\Console\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Router\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Route matching commands in Symfony CLI
 *
 * @license MIT
 * @author Aleksandr Sandrovskiy <a.sandrovsky@gmail.com>
 */
class SymfonyCli implements RouteInterface, ServiceLocatorAwareInterface {

	/**
	 * @var
	 */
	private $routePluginManager;

	/**
	 * Default values.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Create a new colon console route.
	 *
	 * @param  array $defaults
	 * @return SymfonyCli
	 */
	public function __construct(array $defaults = array()) {
		$this->defaults = $defaults;
	}

	/**
	 * @param \Zend\Stdlib\RequestInterface $request
	 * @return null|\Zend\Mvc\Router\Console\RouteMatch|\Zend\Mvc\Router\RouteMatch
	 */
	public function match(Request $request) {
		$routePluginManager = $this->getServiceLocator();
		$serviceLocator = $routePluginManager->getServiceLocator();

		if (!$request instanceof ConsoleRequest) {
			return null;
		}

		$params = $request->getParams()->toArray();

		$cli = $serviceLocator->get('doctrinetools.console_application');

		if (!isset($params[0]) || !$cli->has($params[0])) {
			return null;
		}

		return new RouteMatch($this->defaults);
	}

	/**
	 * @param array $params
	 * @param array $options
	 * @return mixed|string
	 */
	public function assemble(array $params = array(), array $options = array()) {
		return '';
	}

	/**
	 * @return array
	 */
	public function getAssembledParams() {
		return array();
	}

	/**
	 * @param array $options
	 * @return SymfonyCli|void
	 * @throws \Zend\Mvc\Router\Exception\InvalidArgumentException
	 */
	public static function factory($options = array()) {
		if ($options instanceof Traversable) {
			$options = ArrayUtils::iteratorToArray($options);
		} elseif (!is_array($options)) {
			throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
		}

		if (!isset($options['defaults'])) {
			$options['defaults'] = array();
		}

		return new static (
			$options['defaults']
		);
	}

	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $routePluginManager
	 */
	public function setServiceLocator(ServiceLocatorInterface $routePluginManager) {
		$this->routePluginManager = $routePluginManager;
	}

	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->routePluginManager;
	}
}