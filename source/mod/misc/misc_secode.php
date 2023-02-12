<?php
	if(!defined('IN_POWERBOARD')){//or $_G['config']['basic_bburl']!=dirname($_SERVER['HTTP_REFERER']).'/') {
		exit('Access Denied');
	}
	@header("Expires: -1");
	@header("Cache-Control: no-store,private,post-check=0,pre-check=0,max-age=0",FALSE);
	@header("Pragma: no-cache");
	ob_clean();
	include libfile('functions/secode');
	$code=new secode;
	$code->width=$_G['config']['secode_width'];
	$code->height=$_G['config']['secode_height'];
	$code->type=$_G['config']['secode_type'];
	$code->usepicbg=$_G['config']['secode_usepicbg'];
	$code->shadow=$_G['config']['secode_shadow'];
	$code->display();
?>