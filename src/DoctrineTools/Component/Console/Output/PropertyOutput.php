<?php

namespace DoctrineTools\Component\Console\Output;

use \Symfony\Component\Console\Output\Output;
use \Symfony\Component\Console\Formatter\OutputFormatterInterface;

class PropertyOutput extends Output {

	private $message;

	public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null) {
		if (null === $decorated) {
			$decorated = $this->hasColorSupport();
		}

		parent::__construct($verbosity, $decorated, $formatter);
	}

	protected function doWrite($message, $newline) {
		$this->message = $message;
	}

	public function getMessage() {
		return $this->message;
	}

	protected function hasColorSupport() {
		if (DIRECTORY_SEPARATOR == '\\') {
			return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI');
		}

		return function_exists('posix_isatty') && @posix_isatty(STDOUT);
	}
}
