Doctrine Tools
==============

Introduction
------------

This module provides an unified endpoint for Doctrine command line tools.

Installation
------------

First, add the following line into your `composer.json` file:

```json
"require": {
	"sundrique/doctrine-tools": ">=0.1"
}
```

Then, enable the module by adding `DoctrineTools` in your application.config.php file.

```php
<?php
return array(
	'modules' => array(
		'DoctrineTools',
		'DoctrineModule',
		'DoctrineORMModule',
		'Application',
	),
);
```

Create directory `data/DoctrineTools/Migrations` and make sure your application has write access to it.

Configuration
-------------

If you have already configured [DoctrineORMModule](http://www.github.com/doctrine/DoctrineORMModule) no extra configuration required. Otherwise you need to configure it first.

You can also overwrite default directory where migrations files will be stored, migrations namespace and migrations table name.

```php
<?php
return array(
    'doctrinetools' => array(
		'migrations' => array(
			'directory' => 'path/to/MyDoctrineMigrations',
			'namespace' => 'MyDoctrineMigrations',
			'table' => 'my_migrations'
		)
	)
);
```