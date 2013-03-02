<?php

namespace DoctrineToolsTest\Controller;

use DoctrineToolsTest\Bootstrap;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

class MigrationsControllerTest extends AbstractConsoleControllerTestCase {

	private static $MIGRATIONS_DIRECTORY = 'DoctrineTestMigrations';

	private static $MIGRATIONS_NAMESPACE = 'DoctrineTestMigrations';

	private static $MIGRATIONS_TABLE = 'test_migrations';

	public function setUp() {
		$this->setApplicationConfig(
			include __DIR__ . '/../../TestConfig.php.dist'
		);

		if (!file_exists(self::$MIGRATIONS_DIRECTORY)) {
			mkdir(self::$MIGRATIONS_DIRECTORY);
		}

		parent::setUp();

		$testConfig = $this->getApplicationServiceLocator()->get('Config');
		$testConfig['doctrinetools']['migrations']['directory'] = self::$MIGRATIONS_DIRECTORY;
		$testConfig['doctrinetools']['migrations']['namespace'] = self::$MIGRATIONS_NAMESPACE;
		$testConfig['doctrinetools']['migrations']['table'] = self::$MIGRATIONS_TABLE;

		$this->getApplicationServiceLocator()->setAllowOverride(true);
		$this->getApplicationServiceLocator()->setService('Config', $testConfig);
	}

	public function tearDown() {
		self::removeDir(self::$MIGRATIONS_DIRECTORY);

		parent::tearDown();
	}

	public function testIndexActionCanBeAccessed() {
		$request = new \Zend\Console\Request(array(
												  'scriptname.php',
												  'migrations:generate'
											 ));
		$this->dispatch($request);

		$this->assertResponseStatusCode(0);
		$this->assertModuleName('doctrinetools');
		$this->assertControllerName('migrations');
		$this->assertControllerClass('migrationscontroller');
		$this->assertActionName('index');
		$this->assertMatchedRouteName('doctrinetools');
	}

	private static function removeDir($dir) {
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			(is_dir($dir . '/' . $file)) ? self::removeDir($dir . '/' . $file) : unlink($dir . '/' . $file);
		}
		return rmdir($dir);
	}
}