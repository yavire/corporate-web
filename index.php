<?php

	session_start();

	//header('Content-Type: text/html; charset=ISO-8859-1'); 

	//opcache_reset();
	define( "DEBUGGING", true );
	require 'libsSmarty/Smarty.class.php';
	define('YAV_DIR', getcwd());
	set_include_path('.:/opt/plesk/php/5.6/share/pear');

	$smarty = new Smarty;
	//$smarty->setTemplateDir('./docs');
	$smarty->setCompileDir('./templates_c');
	$smarty->setConfigDir('./config');
	$smarty->setCacheDir('./cache');

	$smarty->force_compile = true;
	$smarty->debugging = false;
	$smarty->caching = true;
	$smarty->cache_lifetime = 120;

    $smarty->display('docs/index.tpl');


 ?>