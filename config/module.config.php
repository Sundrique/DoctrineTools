<?php
return array(
	'console' => array(
		'router' => array(
			'routes' => array(
				'doctrinetools' => array(
					'type' => 'colon',
					'options' => array(
						'defaults' => array(
							'controller' => 'DoctrineTools\Controller\Index',
							'action' => 'index'
						)
					)
				)
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'DoctrineTools\Controller\Index' => 'DoctrineTools\Controller\IndexController'
		)
	),
	'route_manager' => array(
		'invokables' => array(
			'colon' => 'DoctrineTools\Mvc\Router\Console\SymfonyCli',
		),
	),
	'doctrinetools' => array(
		'migrations' => array(
			'directory' => 'data/DoctrineTools/Migrations',
			'namespace' => 'DoctrineTools\Migrations',
			'table' => 'migrations'
		)
	),
	'service_manager' => array(
		'factories' => array(
			'doctrinetools.migrations_configuration' => function ($serviceManager) {
				$connection = $serviceManager->get('doctrine.connection.orm_default');

				$appConfig = $serviceManager->get('Config');
				$migrationsConfig = $appConfig['doctrinetools']['migrations'];

				$configuration = new \Doctrine\DBAL\Migrations\Configuration\Configuration($connection);
				$configuration->setMigrationsDirectory($migrationsConfig['directory']);
				$configuration->setMigrationsNamespace($migrationsConfig['namespace']);
				$configuration->setMigrationsTableName($migrationsConfig['table']);
				$configuration->registerMigrationsFromDirectory($migrationsConfig['directory']);

				return $configuration;
			},
			'doctrinetools.helper_set' => function ($serviceManager) {
				$connection = $serviceManager->get('doctrine.connection.orm_default');

				$entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

				$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
					'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
					'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection),
					'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
				));

				return $helperSet;
			},
			'doctrinetools.console_application' => function ($serviceManager) {
				$helperSet = $serviceManager->get('doctrinetools.helper_set');

				$cli = new \Symfony\Component\Console\Application('Doctrine Tools', \DoctrineTools\Version::VERSION);
				$cli->setCatchExceptions(true);
				$cli->setAutoExit(false);
				$cli->setHelperSet($helperSet);
				$cli->addCommands(array(
					$serviceManager->get('doctrinetools.execute_command'),
					$serviceManager->get('doctrinetools.generate_command'),
					$serviceManager->get('doctrinetools.migrate_command'),
					$serviceManager->get('doctrinetools.status_command'),
					$serviceManager->get('doctrinetools.version_command'),
					$serviceManager->get('doctrinetools.diff_command')
				));

				return $cli;
			},
			'doctrinetools.generate_command' => new \DoctrineTools\Service\MigrationsCommandFactory('generate'),
			'doctrinetools.execute_command' => new \DoctrineTools\Service\MigrationsCommandFactory('execute'),
			'doctrinetools.migrate_command' => new \DoctrineTools\Service\MigrationsCommandFactory('migrate'),
			'doctrinetools.status_command' => new \DoctrineTools\Service\MigrationsCommandFactory('status'),
			'doctrinetools.version_command' => new \DoctrineTools\Service\MigrationsCommandFactory('version'),
			'doctrinetools.diff_command' => new \DoctrineTools\Service\MigrationsCommandFactory('diff'),
		)
	)
);
