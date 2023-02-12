<?php
	require 'source/functions/functions_application.php';
	$powerboard=new application;
	$powerboard->init();
	$mods=array('secode','mobile','help','rss');
	in_array(gpcget('mod'),$mods)?include 'source/mod/misc/misc_'.gpcget('mod').'.php':msg('mod_unknow');
?>