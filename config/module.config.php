<?php
return array(
	'console' => array(
		'router' => array(
			'routes' => array(
				'doctrinetools' => array(
					'type' => 'colon',
					'options' => array(
						'defaults' => array(
							'controller' => 'migrations',
							'action' => 'index'
						)
						/*'route' => 'doctrine:migrations:diff'*/
						/*'route' => 'doctrine (migrations|orm):controller (diff|execute|generate|migrate|status|version|generateEntities):action [--dir=] [--namespace=] [--write-sql=]',
						'defaults' => array(
							'controller' => 'migrations',
							'action' => 'index'
						)*/

						/*'route' => 'doctrine migrations diff',
						'defaults' => array(
							'controller' => 'migrations',
							'action' => 'index'
						)*/
					)
				)
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'migrations' => 'DoctrineTools\Controller\MigrationsController'/*,
			'orm' => 'DoctrineTools\Controller\ORMController'*/
		)
	),
	'route_manager' => array(
		'invokables' => array(
			'colon' => 'DoctrineTools\Mvc\Router\Console\Colon',
		),
	),
	'doctrinetools' => array(
		'migrations' => array(
			'directory' => 'data/DoctrineTools/Migrations',
			'namespace' => 'DoctrineTools\Migrations',
			'table' => 'migrations'
		)/*,
		'orm' => array(
			'dir' => 'data/DoctrineTools/Entities'
		)*/
	)
);
