<?php
	if(!defined('IN_POWERBOARD')){
		exit('Access Denied');
	}
	class secode{
		var $width=90;
		var $height=70;
		var $type=0;
		var $usepicbg=false;
		var $shadow=true;
		var $datapath=POWERBOARD_ROOT.'static/imgs/secode/';
		var $image;
		function display(){
			if($this->type==0){ //image
				$this->image();
			}elseif($this->type==1){ //sounds
				$this->sounds();
			}else{ //bitmap
				$this->bitmap();
			}
		}
		function text($type=0){
			if($type==0){
				return '23456789abcdefghjkmnpqrstuwxyz';
			}elseif($type==1){
				return '2346789bcefghjkmpqrtvwxy';
			}else{
				return 'abcdefghijklmnopqrstuvwxyz';
			}
		}
		function image(){
			$this->image=imagecreatefromstring($this->image_background());
			$this->adulterate();
			$this->giffont();
			header('Content-type: image/png');
			imagepng($this->image);
			imagedestroy($this->image);
		}
		function adulterate(){
			$linenums=$this->height/10;
			for($i=0; $i<=$linenums;$i++){
				$color=imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
				$x=rand(0,$this->width);
				$y=rand(0,$this->height);
				if(rand(0,1)){
					$w=rand(0,$this->width);
					$h=rand(0,$this->height);
					$s=rand(0,360);
					$e=rand(0,360);
					for($j=0;$j<7;$j++){
						imagearc($this->image,$x+$j,$y,$w,$h,$s,$e,$color);
					}
				}else{
					$xe=rand(0,$this->width);
					$ye=rand(0,$this->height);
					imageline($this->image,$x,$y,$xe,$ye,$color);
					for($j=0;$j<10;$j++){
						imageline($this->image,$x+$j,$y,$xe,$ye,$color);
					}
				}
				for($j=0;$j<max($this->height,$this->width)/2;$j++){
					imagesetpixel($this->image,rand(0,$this->width),rand(0,$this->height),$color);
				}
			}
		}
		function giffont(){
			$nowgif=$secode=$nowstr='';
			$widthtotal=0;
			$font=array();
			$text=$this->text(1);
			for($i=0;$i<=3;$i++){
				$secode.=$nowstr=$text[rand(0,23)];
				$nowgif=$this->datapath.'giffont/'.strtolower($nowstr).'.gif';
				if(file_exists($nowgif)){
					$font[$i]['file']=$nowgif;
					$font[$i]['data']=getimagesize($nowgif);
					$font[$i]['width']=$font[$i]['data'][0] + rand(0,6) - 4;
					$font[$i]['height']=$font[$i]['data'][1] + rand(0,6) - 4;
					$font[$i]['width']+=rand(0,$this->width/5-$font[$i]['width']) or rand($this->width/5-$font[$i]['width'],0);
					$widthtotal+=$font[$i]['width'];
					$font[$i]['exists']=true;
				}else{
					$font[$i]['exists']=false;
				}
			}
			$x=rand(1,$this->width-$widthtotal);
			for($i=0;$i<=3;$i++){
				$color=array(rand(0,255),rand(0,255),rand(0,255));
				if($font[$i]['exists']){
					$gif=imagecreatefromgif($font[$i]['file']);
					$y=rand(0,$this->height-$font[$i]['height']);
					if($this->shadow){
						imagecolorset($gif,0,0,0,0);
						imagecopyresized($this->image,$gif,$x+1,$y+1,0,0,$font[$i]['width'],$font[$i]['height'],$font[$i]['data'][0],$font[$i]['data'][1]);
					}
					imagecolorset($gif,0,$color[0],$color[1],$color[2]);
					imagecopyresized($this->image,$gif,$x,$y,0,0,$font[$i]['width'],$font[$i]['height'],$font[$i]['data'][0],$font[$i]['data'][1]);
					imagedestroy($gif);
				}else{
					$y = rand(0,$this->height-20);
					if($this->shadow) {
						imagestring($this->image,5,$x+1,$y+1,$secode[$i],imagecolorallocate($this->image,0,0,0));
					}
					imagestring($this->image,5,$x,$y,$secode[$i],imagecolorallocate($this->image,$color[0],$color[1],$color[2]));
				}
				$x+=$font[$i]['width'];
			}
			$_SESSION['secode']=$_G['secode']=$secode;
		}
		function image_background(){
			$backgrounds=array();
			$bg=imagecreatetruecolor($this->width,$this->height);
			if($this->usepicbg==true){
				if($handle=@opendir($this->datapath.'backgrounds/')){
					while($bgfile=@readdir($handle)){
						if(preg_match('/\.jpg$/i',$bgfile)){
							$backgrounds[]=$this->datapath.'backgrounds/'.$bgfile;
						}
					}
					@closedir();
				}
				if($backgrounds){
					$imwm=imagecreatefromjpeg($backgrounds[array_rand($backgrounds)]);
					$colorindex=imagecolorat($imwm,0,0);
					$c=imagecolorsforindex($imwm,$colorindex);
					$colorindex=imagecolorat($imwm,1,0);
					imagesetpixel($imwm,0,0,$colorindex);
					$c[0]=$c['red'];$c[1]=$c['green'];$c[2]=$c['blue'];
					imagecopymerge($bg,$imwm,0,0,rand(0,200-$this->width),rand(0,80-$this->height),imagesx($imwm),imagesy($imwm),100);
					imagedestroy($imwm);
				}
			}else{
				for($i=0;$i<3;$i++){
					$start[$i]=rand(0,255);$end[$i]=rand(0,255);$step[$i]=($end[$i]-$start[$i])/$this->width;$c[$i]=$start[$i];
				}
				for($i=0;$i<$this->width;$i++){
					imageline($bg,$i,0,$i,$this->height,imagecolorallocate($bg,$c[0],$c[1],$c[2]));
					$c[0]+=$step[0];$c[1]+=$step[1];$c[2]+=$step[2];
				}
				$c[0]-=20;$c[1]-=20;$c[2]-=20;
			}
			ob_start();
			imagepng($bg);
			imagedestroy($bg);
			$bgcontent=ob_get_contents();
			ob_end_clean();
			return $bgcontent;
		}

		function bitmap(){
			$secode=rand(0000,9999);
			$numbers=array(
					0=>array('3c','66','66','66','66','66','66','66','66','3c'),
					1=>array('1c','0c','0c','0c','0c','0c','0c','0c','1c','0c'),
					2=>array('7e','60','60','30','18','0c','06','06','66','3c'),
					3=>array('3c','66','06','06','06','1c','06','06','66','3c'),
					4=>array('1e','0c','7e','4c','2c','2c','1c','1c','0c','0c'),
					5=>array('3c','66','06','06','06','7c','60','60','60','7e'),
					6=>array('3c','66','66','66','66','7c','60','60','30','1c'),
					7=>array('30','30','18','18','0c','0c','06','06','66','7e'),
					8=>array('3c','66','66','66','66','3c','66','66','66','3c'),
					9=>array('38','0c','06','06','3e','66','66','66','66','3c')
				);
			for($i=0;$i<10;$i++){
				for($j=0;$j<6;$j++){
					$a1=substr('012',mt_rand(0,2),1).substr('012345',mt_rand(0,5),1);
					$a2=substr('012345',mt_rand(0,5),1).substr('0123',mt_rand(0,3),1);
					mt_rand(0,1)==1?array_push($numbers[$i],$a1):array_unshift($numbers[$i],$a1);
					mt_rand(0,1)==0?array_push($numbers[$i],$a1):array_unshift($numbers[$i],$a2);
				}
			}
			$bitmap=array();
			for($i=0;$i<20;$i++){
				for($j=0;$j<4;$j++){
					$n=substr($secode,$j,1);
					$bytes=$numbers[$n][$i];
					$a=mt_rand(0,14);
					switch($a){
						case 1:str_replace('9','8',$bytes);break;
						case 3:str_replace('c','e',$bytes);break;
						case 6:str_replace('3','b',$bytes);break;
						case 8:str_replace('8','9',$bytes);break;
						case 0:str_replace('e','f',$bytes);break;
					}
					array_push($bitmap,$bytes);
				}
			}
			for ($i=0;$i<8;$i++){
				$a =substr('012',mt_rand(0,2),1).substr('012345',mt_rand(0,5),1);
				array_unshift($bitmap,$a);
				array_push($bitmap,$a);
			}
			$image=pack('H*','424d9e000000000000003e000000280000002000000018000000010001000000'.'0000600000000000000000000000000000000000000000000000FFFFFF00'.implode('',$bitmap));
			$_SESSION['secode']=$_G['secode']=$secode;
			header('Content-Type: image/bmp');
			echo $image;
		}
		function sounds(){
            header('Content-Type: audio/mpeg');
			$text=$this->text(2);
			$secode=$nowstr='';
			for($i=0;$i<=3;$i++){
				$secode.=$nowstr=$text[rand(0,25)];
				readfile($this->datapath.'sounds/'.strtolower($nowstr).'.mp3');
			}
			$_SESSION['secode']=$_G['secode']=$secode;
		}
	}
?>