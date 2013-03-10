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
