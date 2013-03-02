<?php
namespace DoctrineTools\Mvc\Router\Console;

use Zend\Mvc\Router\Console\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Router\Exception;

class Colon implements RouteInterface {

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
	 * @return Colon
	 */
	public function __construct(array $defaults = array()) {
		$this->defaults = $defaults;
	}

	/**
	 * @param \Zend\Stdlib\RequestInterface $request
	 * @return null|\Zend\Mvc\Router\Console\RouteMatch|\Zend\Mvc\Router\RouteMatch
	 */
	public function match(Request $request) {
		if (!$request instanceof ConsoleRequest) {
			return null;
		}

		$params = $request->getParams()->toArray();
		$matches = array();

		if ($params[0] === 'list') {
			$namespace = $params[1];
		} else {
			$namespace = $params[0];
		}

		$namespace = explode(':', $namespace);

		if ($namespace[0] !== 'migrations') {
			return null;
		}

		return new RouteMatch(array_merge($this->defaults, $matches));
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
	 * @return Colon|void
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
}