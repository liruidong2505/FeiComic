<?php
try {
	header ( 'Content-type:text/html; charset=UTF-8;Cache-control: private, must-revalidate;P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR' );
	date_default_timezone_set ( "Asia/Shanghai" );
	set_include_path ( '.' . PATH_SEPARATOR . './application' . PATH_SEPARATOR . './library/smarty' . PATH_SEPARATOR . './application/fckeditor' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './application/modles' . PATH_SEPARATOR . get_include_path () );
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
	require_once 'configs/config.php';
	require_once 'Zend/Config/Ini.php';
	require_once 'Zend/Loader/Autoloader.php';
	require_once 'Zend/Cache.php';
	require_once 'Zend/Session/Namespace.php';
	require_once 'Zend/Config/Xml.php';
	
	Zend_Loader_Autoloader::getInstance ()->setFallbackAutoloader ( true );
	
	//数据库
	$dbAdapter = Zend_Db::factory ( $config ['db'] ['adpater'], $config ['db'] ['config'] );
	Zend_Db_Table::setDefaultAdapter ( $dbAdapter );
	Zend_Registry::set ( "db", $dbAdapter );
	
	//加载工程配置信息
	$appconfig = new Zend_Config_Ini ( './application/configs/application.ini', 'general' );
	$registry = Zend_Registry::getInstance ();
	$registry->set ( 'appconfig', $appconfig );
	
	$cache_dir = $appconfig->cache_dir;
	if (! is_dir ( $cache_dir )) {
		mkdir ( $cache_dir );
	}
	$backOption = array ('cache_dir' => $cache_dir );
	$frontendOptions = array ('cached_entity' => $appconfig->sesclass );
	$cachecls = Zend_Cache::factory ( 'Class', 'File', $frontendOptions, $backOption );
	Zend_Registry::set ( 'cachecls', $cachecls );
	
	//session
	$videosession = new Zend_Session_Namespace ( 'project' );
	Zend_Registry::set ( 'videosession', $videosession );
	
	//常量
	Zend_Registry::set('constant', $config['constant']);
    
	//视图
	$view = new Zend_View_Smarty ( $config ['view'] );
	Zend_Registry::set ( 'view', $view );
	
	//前端控制器
	$directory = './application/controllers';
	$front = Zend_Controller_Front::getInstance ();
	$front->setParam ( 'useDefaultControllerAlways', false );
	$front->setDefaultControllerName ( 'Movie' );
	$front->setControllerDirectory ( $directory );
	$front->setParam ( 'noViewRenderer', true ); //禁用试图渲染功能
	$front->dispatch ();
    
} catch ( Exception $e ) {
	echo ($e->getMessage ());
}
