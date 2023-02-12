<?php
    if(!defined('IN_POWERBOARD')){
        exit('Access Denied');
    }
    function template_html_parse($file,$tplid){
        global $_G;
        $langvar=array();
        $tplpath="template/{$tplid}/";
        $tplhtmlpath="{$tplpath}{$file}.html";
        $tplhtmltemppath="data/cache/template/{$tplid}_".str_replace('/','_',$file).'.php';
        if(!file_exists($tplpath)){
            user_error('Template not found:'.$tplid);
        }
        if(!file_exists($tplhtmlpath)){
            $tplhtmlpath=str_replace($tplid,'default',$tplhtmlpath);
            $tplhtmltemppath=str_replace($tplid,'default',$tplhtmltemppath);
        }
        if((defined('IN_MOBILE') && IN_MOBILE) || defined('IN_VIEW')){
            $tplhtmlpath="{$tplpath}mobile/{$file}.html";
            $tplhtmltemppath="data/cache/template/{$tplid}_m_".str_replace('/','_',$file).'.php';
            if(!file_exists($tplhtmlpath)){
                $tplhtmlpath="{$tplpath}{$file}.html";
                $tplhtmltemppath="data/cache/template/{$tplid}_".str_replace('/','_',$file).'.php';
            }
        }
        if(file_exists($tplhtmltemppath) && filemtime($tplhtmltemppath)==filemtime($tplhtmlpath)){
            //return $tplhtmltemppath;
        }
        $parse=file_get_contents($tplhtmlpath);
        //Parse start
        $parse=preg_replace('/\{template\s+(\S+)\}/is',"<?php include template('\\1','{$tplid}');?>",$parse);
        $parse=preg_replace_callback('/\{subtemplate\s+(\S+)\}/is',function($m) use($tplid){
            $replace="\n<!--sub:{$m[1]}-->\n".file_get_contents('template/'.$tplid.'/'.$m[1].'.html')."\n<!--endsub:{$m[1]}-->\n";
            return str_replace($m[0],$replace,$m[0]);
        },$parse);
        $parse=preg_replace_callback('/\{la\s+(\S+)\}/is',function($m) use($langvar){
            if($langvar==array()) $langvar=lang('common');
            if(isset($langvar[$m[1]])){
                return str_replace($m[0],$langvar[$m[1]],$m[0]);
            }else{
                return "?{$m[1]}?";
            }
        },$parse);
        $parse=preg_replace('/\{foreach\s+(\S+)\s+(\S+)\}/is','<?php if(is_array(\\1)){foreach(\\1 as \\2){ ?>',$parse);
        $parse=preg_replace('/\{foreach\s+(\S+)+\s(\S+)+\s(\S+)\}/is','<?php if(is_array(\\1)){foreach(\\1 as \\2=>\\3){ ?>',$parse);
        $parse=preg_replace('/\{for\s+(\S+)\s+(\S+)\s+(\S+)\}/is','<?php for(\\1;\\2;\\3){ ?>',$parse);
        $parse=str_replace('{/foreach}','<?php } }?>',$parse);
        $parse=str_replace('{/for}','<?php } ?>',$parse);
        $parse=preg_replace('/\{if\s+(.+?)\}/is','<?php if(\\1){ ?>',$parse);
        $parse=preg_replace('/\{elseif\s+(.+?)\}/is','<?php }elseif(\\1){ ?>',$parse);
        $parse=str_replace('{else}','<?php }else{ ?>',$parse);
        $parse=str_replace('{/if}','<?php } ?>',$parse);
        $parse=preg_replace('/\{out\s+(.+?)\}/is','<?php echo \\1?>',$parse);
        $parse=preg_replace_callback('/\{css\s+(.+?)\}/is',function($m) use($tplid){
            $return=template_css_parse($tplid,$m[1]);
            return str_replace($m[0],$return,$m[0]);
        },$parse);
        $parse=preg_replace_callback('/\{avatar\s+(.+?)\s*\}/is',function($m) use($_G){
            return str_replace($m[0],'<?=avatar('.$m[1].')?>',$m[0]);
        },$parse);
        $parse=preg_replace_callback('/\{date\s+(.+?)\s*\}/is',function($m) use($_G){
            return str_replace($m[0],'<?=_date(0,'.$m[1].')?>',$m[0]);
        },$parse);
        $parse=preg_replace('/\{block\s+(.+?)\}(.+?)\{\/block\}/is',"<?php $\\1= <<<EOF\n\\2\nEOF;\n?>",$parse);
        $parse=preg_replace('/\{eval\s+(.+?)\s*\}/is','<?php \\1 ?>',$parse);
        $parse=preg_replace('/\{eval\}(.+?)\{\/eval\}/is','<?php \\1 ?>',$parse);
        $parse=preg_replace("/\{(\\\$[a-zA-Z0-9_\-\>\[\]\'\"\$\.\x7f-\xff]+)\}/s",'<?php echo \\1;?>',$parse);
        $parse=str_replace('{IMGDIR}','static/imgs/common/',$parse);
        $parse=str_replace('{BADLINK}','javascript:;',$parse);
        //Parse end
        file_put_contents($tplhtmltemppath,"<?php if(!defined('IN_POWERBOARD')){die();}?>".$parse);
        touch($tplhtmltemppath,filemtime($tplhtmlpath));
        return $tplhtmltemppath;
    }
    function template_css_parse($tplid,$cssname='common'){
        global $_G;
        $tplpath="template/{$tplid}/";
        $tplcsspath="{$tplpath}css/{$cssname}.css";
        $tplcsstemppath="data/cache/template/css_{$tplid}_{$cssname}.css";
        if(file_exists($tplcsstemppath) && filemtime($tplcsstemppath)==filemtime($tplcsspath)){
            //return $tplcsstemppath;
        }
        $parse=file_get_contents($tplcsspath);
        //Parse start
        $parse=preg_replace("/([\n\r]+)\t+/is","",$parse);
        //$parse=str_replace('{DEFAULT_BORDER}','1px solid #86b9d6',$parse);
        //$parse=str_replace('{BACKGROUND}','url({IMGDIR}bg.png) white',$parse);
        //$parse=str_replace('{PAGE_FONTFAMILY}','"New SimSun","SimSun",Tahoma,Helvetica,"Microsoft Yahei",sans-serif',$parse);
        //$parse=str_replace('{PAGE_FONTSIZE}','12px',$parse);
        $parse=str_replace('{IMGDIR}',$_G['config']['basic_bburl'].'files/imgs/common/',$parse);
        $parse=str_replace('{FILESURL}',$_G['config']['basic_bburl'].'files/',$parse);
        $parse=str_replace('{BBURL}',$_G['config']['basic_bburl'],$parse);
        $parse=str_replace('{TPLPATH}',$_G['config']['basic_bburl'].$tplpath,$parse);
        $parse=str_replace(array("\r","\n","\t",'  ','    ','    ','    '),'',$parse);
        //Parse end
        file_put_contents($tplcsstemppath,$parse);
        touch($tplcsstemppath,filemtime($tplcsspath));
        return $tplcsstemppath;
    }
?>