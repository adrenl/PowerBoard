<?php
	/**
		File name:functions_bbcode.php
		Last upload date:2022/7/10
		By:NewAdryKB
		Powered by PowerBoard
	**/
	if(!defined('IN_POWERBOARD')){
		exit('Access Denied');
	}
	//include_once libfile('functions/upload');
	include_once template('forums/bbcode');
	function bbcode2html($string,$noparsebasic=false,$noparsemedia=false,$noparsesmilies=false){
		global $_G;
		if($noparsebasic==false){
			$string=preg_replace_callback('/\[code\](.*?)\[\/code\]/is',function($m){
				return tpl_bb_code(str_replace("\n","<li>",str_replace(array('[',']'),array('&#91;','&#93;'),$m[1])));
			},$string); //code
			$string=preg_replace_callback('/\[quote\](.*?)\[\/quote\]/is',function($m){
				return tpl_bb_quote($m[1]);
			},$string); //quote
			$bbcode_search=array(
				'/\[b\](.*?)\[\/b\]/is',
				'/\[i\](.*?)\[\/i\]/is',
				'/\[u\](.*?)\[\/u\]/is',
				'/\[s\](.*?)\[\/s\]/is',
				'/\[color\=(.*?)\](.*?)\[\/color\]/is',
				'/\[bgcolor\=(.*?)\](.*?)\[\/bgcolor\]/is',
				'/\[font\=(.*?)\](.*?)\[\/font\]/is',
				'/\[size\=(.*?)\](.*?)\[\/size\]/is',
				'/\[url\](.*?)\[\/url\]/is',
				'/\[url\=(.*?)\](.*?)\[\/url\]/is',
				'/\[align\=(left|center|right)\](.*?)\[\/align\]/is',
				'/\[float=(left|right)\](.*?)\[\/float\]/is',
				'/\[img\](.*?)\[\/img\]/is',
				'/\[img\=(.*?)\](.*?)\[\/img\]/is',
				'/\[table\][\n\r\t]*(.*?)[\n\r\t]*\[\/table\]/is',
				'/\[table=(.*?)\][\n\r\t]*(.*?)[\n\r\t]*\[\/table\]/is',
				'/[\n\r\t]*\[tr\](.*?)\[\/tr\][\n\r\t]*/is',
				'/[\n\r\t]*\[td\](.*?)\[\/td\][\n\r\t]*/is',
				'/\[ul\][\n\r\t]*(.*?)[\n\r\t]*\[\/ul\]/is',
				'/\[ol\][\n\r\t]*(.*?)[\n\r\t]*\[\/ol\]/is',
				'/[\n\r\t]*\[li\](.*?)\[\/li\][\n\r\t]*/is',
				'/\[hr\/\]/is',
				'/\[marquee\](.*?)\[\/marquee\]/is',
				'/\[sup\](.*?)\[\/sup\]/is',
				'/\[sub\](.*?)\[\/sub\]/is',
			);
			$bbcode_replace=array(
				'<strong>$1</strong>',
				'<em>$1</em>',
				'<ins>$1</ins>',
				'<del>$1</del>',
				'<span style="color:$1;">$2</span>',
				'<span style="background-color:$1;">$2</span>',
				'<span style="font-family:$1;">$2</span>',
				'<span style="font-size:$1;">$2</span>',
				'<a href="$1">$1</a>',
				'<a href="$1">$2</a>',
				'<p style="text-align:$1;">$2</p>',
				'<span style="float:$1;">$2</span>',
				'<img data-src="$1" class="lazyload">',
				'<img data-src="$1" alt="$2" title="$2" class="lazyload">',
				'<table border="1px">$1</table>',
				'<table border="1px" style="width:$1">$2</table>',
				'<tr>$1</tr>',
				'<td>$1</td>',
				'<ul>$1</ul>',
				'<ol>$1</ol>',
				'<li>$1</li>',
				'<hr>',
				'<marquee onmouseout="this.start()" onmouseover="this.stop()" behavior="alternate">$1</marquee>',
				'<sup>$1</sup>',
				'<sub>$1</sub>',
			);
			$string=preg_replace($bbcode_search,$bbcode_replace,$string);
			$string=preg_replace_callback('/\[attach\](\d+)\[\/attach\]/is',function($m){
				return tpl_bb_attachment(info_attachment($m[1]));
			},$string);
		}
		if($noparsemedia==false){
			$string=preg_replace_callback('/\[audio\](.*?)\[\/audio\]/is',function($m){
				return tpl_bb_aplayer($m[1]);
			},$string);
			$string=preg_replace_callback('/\[video\=(\d+),(\d+)\](.*?)\[\/video\]/is',function($m){
				return tpl_bb_dplayer($m[3],$m[1],$m[2]);
			},$string);
			$string=preg_replace_callback('/\[video\](.*?)\[\/video\]/is',function($m){
				return tpl_bb_dplayer($m[1]);
			},$string);
		}
		if($noparsesmilies==false){
			foreach($_G['config']['smilies'] as $k=>$v){
				$string=str_replace($k,"<img data-src=\"files/imgs/smilies/{$v}\" class=\"lazyload\" title=\"{$k}\" alt=\"{$k}\">",$string);
			}
		}
		$string=str_replace("\n","<br>",$string);
		$string=str_replace("  ","&nbsp;",$string);
		$string=str_replace("	","&nbsp;&nbsp;&nbsp;&nbsp;",$string);
		return $string;
	}
?>