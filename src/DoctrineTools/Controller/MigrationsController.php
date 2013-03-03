<?php

namespace DoctrineTools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\FileGenerator;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use DoctrineTools\Component\Console\Input\StringInput;

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
}