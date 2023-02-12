<?php
    class application{
        function init_vars(){
            global $_G;
            global $db;
            define('IN_POWERBOARD',true);
            define('PHP_DIE','<?php die();?>');
            define('POWERBOARD_ROOT',substr(dirname(__FILE__),0,-16));
            require POWERBOARD_ROOT.'config/config.php';
            require POWERBOARD_ROOT.'source/functions/functions_functions.php';
            require POWERBOARD_ROOT.'source/functions/functions_session.php';
            $_G=array(
                'config'=>$_config,
                'lang'=>array()
            );
            define('IN_MOBILE',is_mobile());
            define('TIMESTAMP',time());
            define('MB_ENABLE',function_exists('mb_convert_encoding'));
            define('GZIP_ENABLE',function_exists('ob_gzhandler'));
            $db=new mysqli($_config['db']['server'],$_config['db']['username'],$_config['db']['password'],$_config['db']['name']);
            if($db->connect_error){
                user_error('Opened database failed:'.$db->connect_error,E_USER_ERROR);
            }
            $db->query('set names utf8');
            $res=$db->query('SELECT * FROM settings');
            while($row=$res->fetch_array(MYSQLI_ASSOC)){
                $_G['config'][$row['keys']]=$row['value'];
            }
        }
        function init(){
            $this->init_vars();
            $this->init_user();
            global $_G;
        }
        function init_user(){
            global $_G;
            global $session;
            $session=new session;
        }
        function page_start(){
            global $_G;
            $_G['debug']['start_time']=getmicrotime();
        }
        function page_end(){
            global $_G;
            global $db;
            $endtime=$_G['debug']['end_time']=getmicrotime();
            $loadtime=$endtime-$_G['debug']['start_time'];
            $_G['debug']['load_time']=round($loadtime,9);
            $_G['debug']['queries']=$db->get_connection_stats()['explicit_free_result'];
        }
    }
?>