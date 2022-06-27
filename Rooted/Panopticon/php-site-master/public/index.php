<?php

error_reporting(E_ALL);

try {

	/**
	 * Read the configuration from an external file
	 */
	require __DIR__.'/../app/config/config.php';

	$loader = new \Phalcon\Loader();

	/**
	 * We're a registering a set of directories taken from the configuration file
	 */
	$loader->registerDirs(
		array(
			__DIR__.$config->phalcon->controllersDir,
			__DIR__.$config->phalcon->libraryDir,
			__DIR__.$config->phalcon->modelsDir
		)
	)->register();

	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new \Phalcon\DI\FactoryDefault();

	/**
	 * Load router from external file
	 */
	$di->set('router', function(){
		require __DIR__.'/../app/config/routes.php';
		return $router;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function() use ($config){
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri($config->phalcon->baseUri);
		return $url;
	});

	/**
	 * Setup the view service
	 */
	$di->set('view', function() use ($config) {
		$view = new \Phalcon\Mvc\View();
		$view->setViewsDir(__DIR__.$config->phalcon->viewsDir);
		return $view;
	});

	//Set the views cache service
	$di->set('viewCache', function(){

		//Cache data for one day by default
		$frontCache = new Phalcon\Cache\Frontend\Output(array(
			"lifetime" => 2592000
		));

		//File backend settings
		$cache = new Phalcon\Cache\Backend\File($frontCache, array(
			"cacheDir" => __DIR__."/../app/cache/",
			"prefix" => "php"
		));

		return $cache;
	});

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	$di->set('db', function() use ($config) {
		return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" => $config->database->host,
			"username" => $config->database->username,
			"password" => $config->database->password,
			"dbname" => $config->database->dbname
		));
	});

	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function(){
		$session = new Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});

	/**
	 * Register the flash service with custom CSS classes
	 */
	$di->set('flash', function(){
		$flash = new Phalcon\Flash\Direct(array(
			'error' => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
			'warning' => 'alert alert-warning',
		));
		return $flash;
	});

	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
