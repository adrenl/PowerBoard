<?php
    if(!defined('IN_POWERBOARD')){
    exit('Access Denied');
    }
    function convertip($ip){
        $ipdatafile=POWERBOARD_ROOT.'static/ipdata/tinyipdata.dat';
        static $fp=NULL,$offset=array(),$index=NULL;
        $ipdot=explode('.',$ip);
        $ip=pack('N',ip2long($ip));
        $ipdot[0]=(int)$ipdot[0];
        $ipdot[1]=(int)$ipdot[1];
        if($fp===NULL&&$fp=@fopen($ipdatafile,'rb')){
            $offset=@unpack('Nlen',@fread($fp,4));
            $index=@fread($fp,$offset['len']-4);
        }elseif($fp==FALSE){
            return '-Invalid IP data file';
        }
        $length=$offset['len']-1028;
        $start=@unpack('Vlen',$index[$ipdot[0]*4].$index[$ipdot[0]*4+1].$index[$ipdot[0]*4+2].$index[$ipdot[0]*4+3]);
        for($start=$start['len']*8+1024;$start<$length;$start+=8){
            if($index[$start].$index[$start+1].$index[$start+2].$index[$start+3]>=$ip){
                $index_offset=@unpack('Vlen',$index[$start+4].$index[$start+5].$index[$start+6]."\x0");
                $index_length=@unpack('Clen',$index[$start+7]);
                break;
            }
        }
        @fseek($fp,$offset['len']+$index_offset['len']-1024);
        if($index_length['len']){
            return '-'.@fread($fp,$index_length['len']);
        }else{
            return '-Unknown';
        }
    }
    function _strlen($string){
        return MB_ENABLE?mb_strlen($string):strlen($string);
    }
    function _substr($string,$offset,$length=null,$dot="..."){
        return MB_ENABLE?mb_substr($string,$offset,$length).$dot:strlen($string,$offset,$length).$dot;
    }
    function _strpos($haystack,$needle,$howreturn=false){
        if(empty($haystack)) return false;
        foreach((array)$needle as $v){
            if(strpos($haystack,$v)!==false){
                $return=$howreturn?$v:true;
                return $return;
            }
        }
        return false;
    }
    function is_mobile(){
        $list=array('iphone','android','phone','mobile','wap','netfront','java','opera mobi','opera mini','ucweb','windows ce','symbian','series','webos','sony','blackberry','dopod','nokia','samsung','palmsource','xda','pieplus','meizu','midp','cldc','motorola','foma','docomo','up.browser','up.link','blazer','helio','hosin','huawei','novarra','coolpad','webos','techfaith','palmsource','alcatel','amoi','ktouch','nexian','ericsson','philips','sagem','wellcom','bunjalloo','maui','smartphone','iemobile','spice','bird','zte-','longcos','pantech','gionee','portalmmm','jig browser','hiptop','benq','haier','^lct','320x320','240x320','176x220','windows phone');
        $ua=strtolower($_SERVER['HTTP_USER_AGENT']);
        if(_strpos($ua,$list)){
            return true;
        }else{
        return false;
        }
    }
    function gpcget($k,$type='GP',$filter=true){
        $type=strtoupper($type);
        switch($type){
            case 'G':$var=&$_GET;break;
            case 'P':$var=&$_POST;break;
            case 'C':$var=&$_COOKIE;break;
            default:
                if(isset($_GET[$k])){
                    $var=&$_GET;
                }else{
                    $var=&$_POST;
                }
                break;
            }
        return isset($var[$k])?($filter?strfilter($var[$k]):$var[$k]):NULL;
    }
    function libfile($libname,$folder=''){
        $libpath='source'.$folder;
        if(strstr($libname,'/')){
            list($pre,$name)=explode('/',$libname);
            $path=POWERBOARD_ROOT."{$libpath}/{$pre}/{$pre}_{$name}";
        }else{
            $path=POWERBOARD_ROOT."{$libpath}/{$libname}";
        }
        return $path.'.php';
    }
    function authcode($string,$operation='DECODE',$key='',$expiry=0){
        global $_G;
        $ckey_length = 4;
        $key = md5($key != '' ? $key : $_G['config']['security']['authkey']);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
    function lang($file,$langvar=null,$vars=null,$default=null){
        global $_G;
        @list($path,$file)=explode('/',$file);
        if(!$file){
            $file=$path;
            $path='';
        }
        $key=$path==''?$file:$path.'_'.$file;
        if(!isset($_G['lang'][$key])){
            include POWERBOARD_ROOT.'static/lang/'.($path==''?'':$path.'/').'lang_'.$file.'.php';
            $_G['lang'][$key]=$lang;
        }
        $returnvalue=&$_G['lang'];
        $return=$langvar!==null?(isset($returnvalue[$key][$langvar])?$returnvalue[$key][$langvar]:null):$returnvalue[$key];
        $return=$return === null?($default!==null?$default:$langvar):$return;
        $searchs=$replaces=array();
        if($vars && is_array($vars)){
            foreach($vars as $k => $v){
                $searchs[]='{'.$k.'}';
                $replaces[]=$v;
            }
        }
        if(is_string($return) && strpos($return,'{_G/')!==false){
            preg_match_all('/\{_G\/(.+?)\}/',$return,$gvar);
            foreach($gvar[0] as $k => $v){
                $searchs[]=$v;
                $replaces[]=getglobal($gvar[1][$k]);
            }
        }
        $return=str_replace($searchs,$replaces,$return);
        return $return;
    }
    require libfile('functions/template');
    function template($file,$tplid='default'){
        return template_html_parse($file,$tplid);
    }
    function arraytable($array){ //没什么用，仅仅用来DeBug
        $return='<table border="1" style="border-collapse:collapse;"><tr><th colspan="2">Array</th></tr><tr><th>Key</th><th>Value</th></tr>';
        foreach($array as $key=>$value){
            $return.='<tr><td>'.print_r($key,true).'</td><td>'.(is_array($value)?arraytable($value):print_r($value,true)).'</td></tr>';
        }
        return $return.'</table>';
    }
    function strfilter($string){
        return trim(htmlspecialchars($string));
    }
    function _date($format=0,$timestamp=null){
        global $_G;
        $timestamp=$timestamp?$timestamp:time();
        /**switch($format){
            case 0:
                return date($_G['config']['datetime']['format']['full'],$timestamp);
                break;
            case 1:
                return date($_G['config']['datetime']['format']['only_date'],$timestamp);
                break;
            case 2:
                return date($_G['config']['datetime']['format']['only_time'],$timestamp);
                break;
            default:
                return date($format,$timestamp);
        }
        **/
        return date('Y-m-d H:i:s',$timestamp);
    }
    function avatar($uid,$returnsrc=false){
        $return='data/avatar/'.$uid.'.jpg';
        if(!file_exists($return)){
            $return='static/imgs/default_avatar.jpg';
        }
        return $returnsrc?$return:'<img class="avatar" src="'.$return.'" onerror="this.src=\'static/imgs/default_avatar.jpg\'">';
    }
    function getuserprofile($name,$isuid=false){
        global $db;
        if($isuid){
            $res=$db->query('SELECT * FROM user WHERE uid='.$name);
        }else{
            $res=$db->query('SELECT * FROM user WHERE name="'.$name.'"');
        }
        $profile=$res->fetch_array(MYSQLI_ASSOC);
        return $profile?$profile:false;
    }
    function getuserexists($name,$isuid=false){
        return getuserprofile($name,$isuid)?true:false;
    }
    function msg($message,$url='',$replace=array(),$options=array()){
        global $_G,$navtitle;
        lang('message');
        $navtitle=lang('common','tipsmessage');
        if(isset($_G['lang']['message'][$message])){
            $message=$_G['lang']['message'][$message];
        }
        if($replace){
            foreach($replace as $key=>$value){
                $message=str_replace('{'.$key.'}',$value,$message);
            }
        }
        if(!$url){
            !isset($options['return'])?$options['return']=true:'';
        }else{
            $message.='<script>setTimeout(\'document.location="'.$url.'"\',4000);</script>';
        }
        !isset($options['return'])?$options['return']=false:'';
        include template('common/message');
        exit;
    }
    function getmicrotime(){  
        list($usec, $sec)=explode(" ",microtime());  
        return ((float)$usec+(float)$sec);  
    }
	function _setcookie($name,$value="",$expires=0,$prefix=true){
		global $_G;
		$expires=$expires>0?TIMESTAMP+$expires:($expires<0?TIMESTAMP-3156300:0);
		if($prefix){
			$name=$_G['config']['cookie']['prefix'].$name;
		}
		@setcookie($name,$value,$expires);
	}
?>