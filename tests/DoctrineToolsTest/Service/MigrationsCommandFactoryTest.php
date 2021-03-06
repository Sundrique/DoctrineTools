<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

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