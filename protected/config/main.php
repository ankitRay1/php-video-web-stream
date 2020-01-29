<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Web Video Chat',
	// Default app lang
	'language'=>'en',
	'defaultController' => 'Videochat',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>false,
			'loginUrl' => array('videochat/login'),
			'returnUrl' => array('videochat/index'),
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=video',
			'emulatePrepare' => true,
			'username' => 'newuser',
			'password' => 'sakshiRoy1@#',
			'charset' => 'utf8',
			'tablePrefix' => 'vc_',
			'schemaCachingDuration' => 60 * 60 * 24 * 30,
			'enableParamLogging' => false,
			'enableProfiling' => false,
		),
		'cache'=>array(
			'class'=>'CFileCache',
		),
		/*'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),*/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
					//'levels'=>'trace, info',
				),
				// uncomment the following to show log messages on web pages
				/*array(
					'class'=>'CWebLogRoute',
				),*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'tokenTime' => time() + 60 * 60 * 1,
		'clearKey' => false,
		'default_timezone' => 'Europe/Vilnius', // http://www.php.net/manual/en/timezones.php
		'sessIntTime' => 50 * 1000, // Milliseconds 50 * 1000 ms == 50 sec
		'dataSync' => 5 * 1000, // Milliseconds. Informer and online users
		'chatActivity' => array(
			// NoActivity => Time between requests
			0 	=> 1 * 1000,
			3 	=> 2 * 1000,
			10 	=> 5 * 1000,
			20	=> 15 * 1000,
			30	=> 30 * 1000,
		),
	),
);
