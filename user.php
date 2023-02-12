<?php
	require 'source/functions/functions_application.php';
	$powerboard=new application;
	$powerboard->init();
    include libfile('functions/user');
	$mods=array('login','register');
	in_array(gpcget('mod'),$mods)?include 'source/mod/user/user_'.gpcget('mod').'.php':msg('mod_unknow');
?>