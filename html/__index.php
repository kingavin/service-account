<?php
chdir(dirname(__DIR__));

define("BASE_PATH", getenv('BASE_PATH'));

include BASE_PATH.'/inc/Zend/Loader/StandardAutoloader.php';

$autoLoader = new Zend\Loader\StandardAutoloader(array(
	'namespaces' => array(
		'Zend'		=> BASE_PATH.'/inc/Zend',
		'Core'		=> BASE_PATH.'/inc/Core',
		'Doctrine'	=> BASE_PATH.'/inc/Doctrine',
		'Brick'		=> BASE_PATH.'/extension/Brick',
		'Fucms'		=> '../lib/Fucms',
		'Document'	=> '../lib/Document'
	)
));
$autoLoader->register();

$application = Zend\Mvc\Application::init(include 'config/application.config.php');
$application->run();

//$finishTime = microtime();
//echo $finishTime - $time;