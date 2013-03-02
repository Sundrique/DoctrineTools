<?php

namespace DoctrineTools\Component\Console\Helper;

use Symfony\Component\Console\Helper\Helper;

class DbHelper extends Helper
{
	private $connection;

	public function setConnection($connection) {
		$this->connection = $connection;
	}

	public function getConnection() {
		return $this->connection;
	}

	/**
	 * Returns the helper's canonical name
	 */
	public function getName()
	{
		return 'db';
	}
}
