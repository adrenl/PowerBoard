/**
	File name:editor.js
	Last upload date:2022/7/9
	By:NewAdryKB
	Powered by PowerBoard
**/
var editort=$("editor_textarea");
var toolbar=$("editor_toolbar");
var editordiv=$("editor");
var Fonts=new Array("宋体","新宋体","黑体","仿宋","楷体","微软雅黑","Arial","Comic Sans MS","Courier New","Tahoma","Times New Roman","Verdana");
var smload=false;
var timer=0;
var Colors=new Array('#000000','#800000','#008000','#000080','#800080','#008080','#808080','#C0C0C0','#FF0000','#00FF00','#FFFF00','#0000FF','#FF00FF','#00FFFF','#FFC0CB','#D9D919','#4F4F2F','#856363','#215E21','#FF6EC7','#CC3299','#A67D3D','#CD7F32','#32CD99','#FF7F00','#38B0DE','#EBC79E','#FFFFFF');
var useattachment=new Array();
window.onload=function(){
	var FontE=$('FontSet');
	for(var i=0;i<Fonts.length;i++){
		FontE.innerHTML+='<option style="font-family:\''+Fonts[i]+'\'" value="'+Fonts[i]+'">'+Fonts[i]+'</option>';
	}
	var ColorSetE=$('ColorSet');
	var BackColorSetE=$('BackColortSet');
	for(var i=0;i<Colors.length;i++){
		ColorSetE.innerHTML+='<option style="color:'+Colors[i]+'" value="'+Colors[i]+'">'+Colors[i]+'</option>';
		BackColorSetE.innerHTML+='<option style="background-color:'+Colors[i]+'" value="'+Colors[i]+'">'+Colors[i]+'</option>';
	} 
	var SizeE=$('SizeSet');
	for(var i=0;i<=30;i++){
		SizeE.innerHTML+='<option style="font-size:'+i+'px" value="'+i+'px">'+i+'px</option>';
	}
	var eles=document.getElementsByClassName('e_btn');
	for(var i=0;i<eles.length;i++){
		eles[i].onclick=function(){
			code(this.innerHTML);
		}
	}
	tominimode(e_minimode);
	autosavecheck();
}
editort.onkeyup=function(){
	$('counttext').innerHTML="[字数："+editort.value.length+" 字|系统限制："+e_minlength+"~"+e_maxlength+" 字]"
}
function insert(myValue){
	var myField=editor_textarea;
    //IE support
    if (document.selection){
        myField.focus();
        sel=document.selection.createRange();
        sel.text=myValue;
        sel.select();
    }
    //MOZILLA/NETSCAPE support
    else if (myField.selectionStart || myField.selectionStart=='0'){
        var startPos=myField.selectionStart;
        var endPos=myField.selectionEnd;
        // save scrollTop before insert
        var restoreTop=myField.scrollTop;
        myField.value= myField.value.substring(0,startPos)+myValue+myField.value.substring(endPos,myField.value.length);
        if (restoreTop>0){
			myField.scrollTop=restoreTop;
        }
        myField.focus();
        myField.selectionStart=startPos+myValue.length;
		myField.selectionEnd=startPos+myValue.length;
	}else{
		myField.value+=myValue;
        myField.focus();
    }
	//editort.focus();
	editort.onkeyup();
}
function code(type,arg){
	if(type=='b'){
		dialog('<input type="text" id="v1">','confirm','输入设置成粗体的文本',function(){insert('[b]'+$('v1').value+'[/b]');});
	}else if(type=='i'){
		dialog('<input type="text" id="v1">','confirm','输入设置成斜体的文本',function(){insert('[i]'+$('v1').value+'[/i]');});
	}else if(type=='u'){
		dialog('<input type="text" id="v1">','confirm','输入添加下划线的文本',function(){insert('[u]'+$('v1').value+'[/u]');});
	}else if(type=='d'){
		dialog('<input type="text" id="v1">','confirm','输入添加删除线的文本',function(){insert('[s]'+$('v1').value+'[/s]');});
	}else if(type=='tc'){
		clr=$('ColorSet').value;
		if(clr=='') return;
		dialog('<input type="text" id="v1">','confirm','输入设置成<span style="color:'+clr+';">'+clr+'</span>颜色的文本',function(){insert('[color='+clr+']'+$('v1').value+'[/color]');});
	}else if(type=='tbc'){
		clr=$('BackColortSet').value;
		if(clr=='') return;
		dialog('<input type="text" id="v1">','confirm','输入设置成<span style="background-color:'+clr+';">'+clr+'</span>背景色的文本',function(){insert('[bgcolor='+clr+']'+$('v1').value+'[/bgcolor]');});
	}else if(type=='font'){
		ft=$('FontSet').value;
		if(ft=='') return;
		dialog('<input type="text" id="v1">','confirm','输入设置成<span style="font-family:\''+ft+'\';">'+ft+'</span>字体的文本',function(){insert('[font='+ft+']'+$('v1').value+'[/font]');});
	}else if(type=='size'){
		s=$('SizeSet').value;
		if(s=='') return;
		dialog('<input type="text" id="v1">','confirm','输入设置成'+s+'字号的文本',function(){insert('[size='+s+']'+$('v1').value+'[/size]');});
	}else if(type=='al'){
		dialog('<input type="text" id="v1">','confirm','输入左对齐的文本',function(){insert('[align=left]'+$('v1').value+'[/align]');});
	}else if(type=='ac'){
		dialog('<input type="text" id="v1">','confirm','输入居于中间的文本',function(){insert('[align=center]'+$('v1').value+'[/align]');});
	}else if(type=='ar'){
		dialog('<input type="text" id="v1">','confirm','输入右对齐的文本',function(){insert('[align=right]'+$('v1').value+'[/align]');});
	}else if(type=='fl'){
		insert("[float=left][/float]");
	}else if(type=='fr'){
		insert("[float=right][/float]");
	}else if(type=='ol' || type=='ul'){
		dialog('点击“添加”来添加一个项目,没有内容的项目不会被添加<div id="insertzone"><input type="text" name="dialoginput"></div><input type="button" value="添加" onclick="var newii=document.createElement(\'input\');newii.type=\'text\';newii.name=\'dialoginput\';$(\'insertzone\').appendChild(newii);">','confirm','插入一个'+(type=='ol'?'有序':'无序')+'列表',function(){
			var toinsert="";
			var insertss=document.getElementsByName('dialoginput');
			for(var i=0;i<insertss.length;i++){
				if(insertss[i].value==''){
					continue;
				}
				toinsert+='[li]'+insertss[i].value+'[/li]\n';
			}
			insert('['+type+']\n'+toinsert+'[/'+type+']\n');
		});
	}else if(type=='link'){
		dialog('超链接指向的地址(邮箱在前面加入“mailto:”)<br><input type="text" id="v1"><br>超链接显示的文本(可空)<br><input type="text" id="v2">','confirm','插入超链接',function(){
			if($('v2').value==''){
				insert('[url]'+$('v1').value+'[/url]');
			}else{
				insert('[url='+$('v1').value+']'+$('v2').value+'[/url]');
			}
		});
	}else if(type=='table'){
		dialog('行数<br><input type="number" id="v1"><br>列数<br><input type="number" id="v2"><br>表格宽度<br><input type="text" id="v3">','confirm','插入表格',function(){
			var col=$('v1').value;
			var row=$('v2').value;
			var width=$('v3').value?$('v3').value:"100%";
			if(isNaN(col)==true || isNaN(row)==true){
				return;
			}
			insertt="[table="+width+"]\n";
			for(var i=0;i<col;i++){ 
				insertt+="[tr]\n";
				for(var j=0;j<row;j++){
					insertt+="[td][/td]\n";
				}
				insertt+="[/tr]\n";
			}
			insertt+="[/table]\n";
			insert(insertt);
		});
	}else if(type=='hr'){
		insert('[hr/]');
	}else if(type=='img'){
		dialog('图片地址<br><input type="text" id="v1"><br>图片标题(可空)<br><input type="text" id="v2">','confirm','插入图片',function(){
			if($('v2').value==''){
				insert('[img]'+$('v1').value+'[/img]');
			}else{
				insert('[img='+$('v1').value+']'+$('v2').value+'[/img]');
			}
		});
	}else if(type=='video'){
		dialog('视频地址<br><input type="text" id="v1"><br>宽<input type="number" id="v2" value="320" class="wnunberi"><br>高<input type="number" value="240" id="v3" class="wnunberi">','confirm','插入视频',function(){insert('[video='+$('v2').value+','+$('v3').value+']'+$('v1').value+'[/video]')});
	}else if(type=='audio'){
		dialog('音频地址<br><input type="text" id="v1">','confirm','插入音频',function(){insert('[audio]'+$('v1').value+'[/audio]')});
	}else if(type=='smilies'){
		if(typeof smilies_file!="object" && smilies_key!="object") return;
		var sf=$("smilies_fieldset");
		if(smload==false){
			for(var k=0;k<smilies_file.length;k++){
				var newimg=document.createElement("img");
				newimg.src=BBURL+"/files/imgs/smilies/"+smilies_file[k];
				newimg.title=newimg.alt=smilies_key[k];
				newimg.classList.add("e_f_smilies");
				newimg.onclick=function(){
					insert(this.alt);
				}
				sf.appendChild(newimg);
			}
			smload=true;
		}
		switchdisplay("smilies_fieldset");
	}else if(type=='attachment'){
		if(e_useattachment==0) return;
		switchdisplay("attachment_fieldset");
		if(typeof e_original_attach=="object" && e_original_attach!=null){
			for(var key in e_original_attach){
				insert_to_attach_list(key,e_original_attach[key]);
				useattachment.push(parseInt(key));
			}
			e_original_attach=null;
			$("editor_useattachment").value=JSON.stringify(useattachment);
		}
	}else if(type=='sup'){
		dialog('<input type="text" id="v1">','confirm','输入上标文本',function(){insert('[sup]'+$('v1').value+'[/sup]');});
	}else if(type=='sub'){
		dialog('<input type="text" id="v1">','confirm','输入下标文本',function(){insert('[sub]'+$('v1').value+'[/sub]');});
	}else if(type=='marquee'){
		dialog('<input type="text" id="v1">','confirm','输入飞行文本',function(){insert('[marquee]'+$('v1').value+'[/marquee]');});
	}else if(type=='code'){
		insert("[code]\n<?php\necho \"Hello world!\";\n?>\n在此输入代码[/code]");
	}else if(type=='quote'){
		insert("[quote]在此输入引用[/quote]");
	}
}
function savedata(){
	if(typeof(Storage)!=="undefined"){
		localStorage.setItem("Editor_SaveData",editort.value);
		$('bottom_msg').innerHTML="内容已于"+new Date().format('hh:mm:ss')+"保存";
	}
}
function loaddata(){
	if(typeof(Storage)!=="undefined"){
		data=localStorage.getItem('Editor_SaveData');
		if(data==null){
			dialog('无内容可恢复！');
			return;
		}
		dialog('“恢复内容”会覆盖你当前的内容，继续吗？','confirm',null,function(){editort.value=data;$('bottom_msg').innerHTML="内容已恢复";});
	}
}
function autosavecheck(){
	if($('autosave').checked==true){
		timer=setInterval("savedata()",25000);
	}else{
		clearInterval(timer);
	}
}
function tominimode(mode){
	if(mode==true){
		_("e_alignleft")[0].style.display="none";
		_("e_aligncenter")[0].style.display="none";
		_("e_alignright")[0].style.display="none";
		_("e_floatleft")[0].style.display="none";
		_("e_floatright")[0].style.display="none";
		_("e_ul")[0].style.display="none";
		_("e_ol")[0].style.display="none";
		_("e_table")[0].style.display="none";
		_("e_hr")[0].style.display="none";
		_("e_audio")[0].style.display="none";
		_("e_video")[0].style.display="none";
		e_useattachment==1?_("e_attachment")[0].style.display="none":'';
		_("e_sup")[0].style.display="none";
		_("e_sub")[0].style.display="none";
		_("e_quote")[0].style.display="none";
		_("e_marquee")[0].style.display="none";
		_("e_code")[0].style.display="none";
		$("FontSet").style.display="none";
		$("SizeSet").style.display="none";
		$("BackColortSet").style.display="none";
	}else{
		_("e_alignleft")[0].style.display="inline-block";
		_("e_aligncenter")[0].style.display="inline-block";
		_("e_alignright")[0].style.display="inline-block";
		_("e_floatleft")[0].style.display="inline-block";
		_("e_floatright")[0].style.display="inline-block";
		_("e_ul")[0].style.display="inline-block";
		_("e_ol")[0].style.display="inline-block";
		_("e_table")[0].style.display="inline-block";
		_("e_hr")[0].style.display="inline-block";
		_("e_audio")[0].style.display="inline-block";
		_("e_video")[0].style.display="inline-block";
		e_useattachment==1?_("e_attachment")[0].style.display="inline-block":'';
		_("e_sup")[0].style.display="inline-block";
		_("e_sub")[0].style.display="inline-block";
		_("e_quote")[0].style.display="inline-block";
		_("e_marquee")[0].style.display="inline-block";
		_("e_code")[0].style.display="inline-block";
		$("FontSet").style.display="inline-block";
		$("SizeSet").style.display="inline-block";
		$("BackColortSet").style.display="inline-block";
	}
}
function insert_to_attach_list(aid,filename){
	if(e_useattachment==0) return;
	var al=$("attachmentlist");
	var new_attach_div=document.createElement("div");
	var new_attach_div_id='attachdiv_'+Math.random()+Math.random();
	var new_attach_filename_s_id='attach_filename_'+Math.random();
	var new_attach_do_s_id='attach_do_'+Math.random();
	new_attach_div.id=new_attach_div_id;
	new_attach_div.innerHTML='<span id="'+new_attach_do_s_id+'">'+(isNaN(aid)==true?aid:'<a href="javascript:;" onclick="insert(\'[attach]'+aid+'[/attach]\')">[插入]</a><a href="javascript:;" onclick="deleteattachment('+aid+');this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);">[删除]</a><a href="javascript:;" onclick="updata_attachment('+aid+',\''+new_attach_filename_s_id+'\')">[更新]</a></span>')+'</span>&nbsp;&nbsp;<span id="'+new_attach_filename_s_id+'">'+filename+'</span>';
	al.appendChild(new_attach_div);
	return [new_attach_do_s_id,new_attach_filename_s_id];
}
function attachmentcheck(filename,size){
	if(new RegExp("\.("+e_attach_canfiletype+")$","i").test(filename)==false){
		dialog("不允许的文件格式");
		return 1;
	}
	if(size>e_attach_maxsize){
		dialog("上传的文件大小太大");
		return 2;
	}
	return 0;
}
function attachmentinput(){
	if(e_useattachment==0) return;
	var willupload=$('newinsert');
	if(attachmentcheck(willupload.value,willupload.files[0].size)!=0) return;
	var uploadajax=newXmlHttp();
	var ids=insert_to_attach_list("即将上传...",getFileName(willupload.value));
	uploadajax.onreadystatechange=function(){
		if(uploadajax.readyState==4){
			if(uploadajax.status==200){
				var aid=uploadajax.responseText;
				if(/^\d+$/.test(aid)==false){
					$(ids[0]).innerHTML='<a href="javascript:;" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);">[删除]</a> 上传错误：'+aid;
					return;
				}
				$(ids[0]).innerHTML='<a href="javascript:;" onclick="insert(\'[attach]'+aid+'[/attach]\')">[插入]</a><a href="javascript:;" onclick="deleteattachment('+aid+');this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);">[删除]</a><a href="javascript:;" onclick="updata_attachment('+aid+',\''+ids[1]+'\')">[更新]</a></span>';
				useattachment.push(parseInt(aid));
				$("editor_useattachment").value=JSON.stringify(useattachment);
			}else{
				$(ids[0]).innerHTML='<a href="javascript:;" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);">[删除]</a> 上传错误，返回的 HTTP 状态码：'+uploadajax.status;
			}
		}
    }
	uploadajax.upload.onprogress=function(up){
        $(ids[0]).innerHTML=Math.floor((up.loaded / up.total) * 100) + "%";
	}
	var uploadform=new FormData();
	uploadform.append('attachment[]',willupload.files[0]);
	uploadajax.open("post", "forums.php?mod=ajaxupload");
	uploadajax.send(uploadform);
	willupload.outerHTML=willupload.outerHTML;
}
function deleteattachment(aid){
	quicklyajax("forums.php?mod=ajaxupload&action=delete&aid="+aid,"get","",true,function(){
		dialog("删除成功");
		useattachment.remove(parseInt(aid));
		$("editor_useattachment").value=JSON.stringify(useattachment);
	});
}
function updata_attachment(aid,changeid){
	dialog('<input type="file" id="updata_attachment_input"><span style="display:none;" id="updata_attachment_span">即将上传...</span>','message',null,null,null,"取消",null);
	$("updata_attachment_input").onchange=function(){
		var uai=$("updata_attachment_input");
		if(attachmentcheck(updata_attachment_input.value,updata_attachment_input.files[0].size)!=0) return;
		var uas=$("updata_attachment_span");
		uai.hidden=true;
		uas.style.display="inline";
		dialog_confirm.hidden=true;
		dialog_cancal_XX.hidden=true;
		var uploadajax=newXmlHttp();
		uploadajax.onreadystatechange=function(){
			if(uploadajax.readyState==4){
				if(uploadajax.status==200){
					var aid=uploadajax.responseText;
					if(/^\d+$/.test(aid)==false){
						uas.innerHTML="上传错误："+aid;
					}else{
						$(changeid).innerHTML=getFileName(uai.value);
						uas.innerHTML="上传成功";
					}
				}else{
					uas.innerHTML="上传错误，返回的 HTTP 状态码："+uploadajax.status;
				}
				setTimeout('$("dialogbg").parentNode.removeChild($("dialogbg"));',2000);
			}
		}
		uploadajax.upload.onprogress=function(up){
			uas.innerHTML="上传中 "+Math.floor((up.loaded / up.total) * 100) + "%";
		}
		var uploadform=new FormData();
		uploadform.append('attachment[]',uai.files[0]);
		uploadajax.open("post", "forums.php?mod=ajaxupload&action=updata&aid="+aid);
		uploadajax.send(uploadform);
	}
}
function oneditorsubmit(){
	var smtc=editort.value;
	if(smtc.length<e_minlength || smtc.length>e_maxlength){
		return false;
	}
	$('editor_data').value=smtc;
	return true;
}