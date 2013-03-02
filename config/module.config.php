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
					)
				)
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'migrations' => 'DoctrineTools\Controller\MigrationsController'
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
		)
	)
);
