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

Usage
-----

```sh
$ /path/to/your/app/public/index.php <command>
```

Available commands
------------------

The following commands are currently available:

* `help` Displays help for a command.
* `list` Lists commands.
* `migrations:diff` Generate a migration by comparing your current database to your mapping information.
* `migrations:execute` Execute a single migration version up or down manually.
* `migrations:generate` Generate a blank migration class.
* `migrations:migrate` Execute a migration to a specified version or the latest available version.
* `migrations:status` View the status of a set of migrations.
* `migrations:version` Manually add and delete migration versions from the version table.