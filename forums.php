<?php
    require 'source/functions/functions_application.php';
    $powerboard=new application;
    $powerboard->init();
    $mods=array('display','view','postdo','ajaxupload');
    in_array(gpcget('mod'),$mods)?include 'source/mod/forums/forums_'.gpcget('mod').'.php':msg('submit_no_validate');
?>