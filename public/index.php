<?php
set_include_path('../lib');
require_once 'controller/controller.class.php';

require_once 'log/log.class.php';

// Logger Setup
$_NYAA_LOG = array();

$logger = new NyaaLog( );
$handler = $logger->createHandler('capture');
$handler->bind($_NYAA_LOG);
$logger->addHandler(NyaaLog::ALL, $handler);
NyaaLog::addStack( $logger );

$Ctrl = NyaaController::factory(
	'web', 
	'../site/root.conf',
	array(
		'root.dir' => realpath(dirname(__FILE__).'/../site')
	)
);
$Ctrl->request( isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']: "", $_POST, $_GET, $_FILES );

// Connect Database
require_once 'db/db.class.php';
$db = NyaaDB::factory( $Ctrl->getConf('db.system') );
$Ctrl->set("db.system", $db);

$Ctrl->init( );
$Ctrl->run( );

foreach($_NYAA_LOG as $log) echo '<li>'.$log.'</li>';
?>
