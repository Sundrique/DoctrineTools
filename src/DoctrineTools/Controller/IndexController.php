<?php

namespace DoctrineTools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineTools\Component\Console\Input\StringInput;

class IndexController extends AbstractActionController {

	public function indexAction() {

		$request = $this->getRequest();

		$allParams = $request->getParams();
		$params = array();
		foreach ($allParams as $key => $param) {
			if (is_numeric($key)) {
				$params[] = $param;
			}
		}

		$input = new StringInput(implode(' ', $params));

		$cli = $this->getServiceLocator()->get('doctrinetools.console_application');

		$cli->run($input);
	}
}