<?php
	if(!defined('IN_POWERBOARD')){
		exit('Access Denied');
	}
    $navtitle=lang('common','login');
    include template('user/login');
?>