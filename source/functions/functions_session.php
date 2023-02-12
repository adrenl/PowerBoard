<?php
    if(!defined('IN_POWERBOARD')){
	    exit('Access Denied');
    }
    class session{
        function __construct(){
            $_SESSION=array();
            echo 'SESSION OK';
        }
        function start(){
        }
        function update(){
        }
    }
?>