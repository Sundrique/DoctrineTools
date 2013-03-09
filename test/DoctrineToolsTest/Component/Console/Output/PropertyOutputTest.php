<?php
namespace DoctrineToolsTest\Component\Console\Output;

use PHPUnit_Framework_TestCase as TestCase;

class PropertyOutputTest extends TestCase {

	public function testWrite() {
		$message = 'message';

		$output = new \DoctrineTools\Component\Console\Output\PropertyOutput();
		$output->write($message);
		$this->assertEquals($message, $output->getMessage());
	}
}