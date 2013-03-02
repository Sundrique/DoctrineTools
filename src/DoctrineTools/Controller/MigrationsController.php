<?php

namespace DoctrineTools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Request as HttpRequest;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\FileGenerator;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Input\StringInput;

class MigrationsController extends AbstractActionController {

	public function indexAction() {

		$request = $this->getRequest();

		$connection = $this->getServiceLocator()->get('doctrine.connection.orm_default');

		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

		$appConfig = $this->getServiceLocator()->get('Config');
		$migrationsConfig = $appConfig['doctrinetools']['migrations'];

		$helperSet = new \Symfony\Component\Console\Helper\HelperSet();
		$helperSet->set(new \Symfony\Component\Console\Helper\DialogHelper(), 'dialog');
		$helperSet->set(new ConnectionHelper($connection), 'db');
		$helperSet->set(new EntityManagerHelper($entityManager), 'em');

		$configuration = new Configuration($connection);
		$configuration->setMigrationsDirectory($migrationsConfig['directory']);
		$configuration->setMigrationsNamespace($migrationsConfig['namespace']);
		$configuration->setMigrationsTableName($migrationsConfig['table']);

		$executeCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand();
		$executeCommand->setMigrationConfiguration($configuration);

		$generateCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand();
		$generateCommand->setMigrationConfiguration($configuration);

		$migrateCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand();
		$migrateCommand->setMigrationConfiguration($configuration);

		$statusCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand();
		$statusCommand->setMigrationConfiguration($configuration);

		$versionCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand();
		$versionCommand->setMigrationConfiguration($configuration);

		$diffCommand = new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand();
		$diffCommand->setMigrationConfiguration($configuration);

		$allParams = $request->getParams();
		$params = array();
		foreach($allParams as $key=>$param) {
			if (is_numeric($key)) {
				$params[] = $param;
			}
		}

		$input = new StringInput(implode(' ', $params));

		$cli = new \Symfony\Component\Console\Application('Doctrine Migrations', \Doctrine\DBAL\Migrations\MigrationsVersion::VERSION);
		$cli->setCatchExceptions(true);
		$cli->setAutoExit(false);
		$cli->setHelperSet($helperSet);
		$cli->addCommands(array(
			// Migrations Commands
			$executeCommand,
			$generateCommand,
			$migrateCommand,
			$statusCommand,
			$versionCommand,
			$diffCommand
		));

		$cli->run($input);
	}

	/*public function generateAction() {
		$config = $this->getConfiguration();

		$path = $this->generateMigration($config);

		$console = $this->getServiceLocator()->get('console');

		$console->writeLine(sprintf('Generated new migration class to "%s"', $path));
	}

	public function diffAction() {
		$config = $this->getConfiguration();

		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$conn = $em->getConnection();
		$platform = $conn->getDatabasePlatform();
		$metadata = $em->getMetadataFactory()->getAllMetadata();

		$tool = new SchemaTool($em);

		$fromSchema = $conn->getSchemaManager()->createSchema();
		$toSchema = $tool->getSchemaFromMetadata($metadata);

		$up = $this->buildCodeFromSql($fromSchema->getMigrateToSql($toSchema, $platform));
		$down = $this->buildCodeFromSql($fromSchema->getMigrateFromSql($toSchema, $platform));

		$console = $this->getServiceLocator()->get('console');

		if (!$up && !$down) {
			$console->writeLine('No changes detected in your mapping information.');

			return;
		}

		$path = $this->generateMigration($config, $up, $down);

		$console->writeLine(sprintf('Generated new migration class to "%s" from schema differences.', $path));
	}

	public function executeAction() {
		$sql = "
			CREATE TABLE IF NOT EXISTS `migrations` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`version` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT AUTO_INCREMENT=1;
		";

		$config = $this->getConfiguration();

		if (isset($config['write-sql']) && strlen($config['write-sql'])) {
			file_put_contents($config['write-sql'], $sql);
		}
	}

	public function generateMigration($config, $up = null, $down = null) {
		$dir = $config['dir'];
		$namespace = $config['namespace'];

		$version = date('YmdHis');

		$methodParameter = new ParameterGenerator('schema', '\Doctrine\DBAL\Schema\Schema');

		$upMethod = new MethodGenerator('up', array($methodParameter), MethodGenerator::FLAG_PUBLIC, ($up !== null ? $up : ''));
		$downMethod = new MethodGenerator('down', array($methodParameter), MethodGenerator::FLAG_PUBLIC, ($down !== null ? $down : ''));

		$class = new ClassGenerator('Version' . $version, $namespace, null, '\Doctrine\DBAL\Migrations\AbstractMigration');
		$class->addMethods(array(
								$upMethod,
								$downMethod
						   ));

		$file = new FileGenerator();
		$file->setClass($class);

		$path = $dir . '/Version' . $version . '.php';

		if (!file_exists($dir)) {
			throw new \InvalidArgumentException(sprintf('Migrations directory "%s" does not exist.', $dir));
		}

		file_put_contents($path, $file->generate());

		return $path;
	}

	public function getConfiguration() {
		$config = $this->getServiceLocator()->get('Config');
		$migrationsConfig = $config['doctrinetools']['migrations'];
		if ($dir = $this->getRequest()->getParam('dir', false)) {
			$migrationsConfig['dir'] = $dir;
		}
		if ($namespace = $this->getRequest()->getParam('namespace', false)) {
			$migrationsConfig['namespace'] = $namespace;
		}
		if ($writeSql = $this->getRequest()->getParam('write-sql', false)) {
			$migrationsConfig['write-sql'] = $writeSql;
		}
		return $migrationsConfig;
	}

	private function buildCodeFromSql(array $sql) {
		foreach ($sql as $query) {
			$code[] = "\$this->addSql(\"$query\");";
		}

		return implode("\n", $code);
	}*/
}