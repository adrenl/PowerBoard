"use strict";
function $(id){
    return document.getElementById(id);
}
function _(classname){
    return document.getElementsByClassName(classname);
}
window.onerror=function(message,url,line,column,error){
    alert("PowerBoard JavaScript Error\n错误信息："+message+"\n错误文件："+url+"\n出错行数："+line+"\n出错时间："+Date()+"\n对于出错而影响到你的使用我们感到十分抱歉");
}
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
function newXmlHttp(){
    var xmlHttp=null;
    try{
        xmlHttp=new XMLHttpRequest();
    }catch (e){
        try{
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }catch (e){
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}
function ajax(url,method,data,async,okfunc,errfunc){
    method=in_array(method,['GET','POST','HEAD'])?method:'GET';
    data=data?data:"";
    async=async?async:true;
    var xmlHttp=newXmlHttp();
    if(async==true){
        xmlHttp.onreadystatechange=function(){

            if(xmlHttp.readyState==4 && xmlHttp.status==200){
                if(typeof okfunc=="function"){
                    okfunc()
                }else{
                    eval(okfunc);
                }
            }else if(xmlHttp.readyState==4 && xmlHttp.status!=200){
                if(typeof errfunc=="function"){
                    errfunc()
                }else{
                    eval(errfunc);
                }
            }
        }
    }
    xmlHttp.open(method,url,async);
    if(method=='POST') xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.send(data);
 }
 var classcodes =[];
window.Import={
    LoadFileList:function(_files,succes){
        var FileArray=[];
        if(typeof _files==="object"){
            FileArray=_files;
        }else{
            if(typeof _files==="string"){
                FileArray=_files.split(",");
            }
        }
        if(FileArray!=null && FileArray.length>0){
            var LoadedCount=0;
            for(var i=0;i< FileArray.length;i++){
                loadFile(FileArray[i],function(){
                    LoadedCount++;
                    if(LoadedCount==FileArray.length){
                        succes();
                    }
                })
            }
        }
        function loadFile(url, success) {
            if (!FileIsExt(classcodes,url)) {
                var ThisType=GetFileType(url);
                var fileObj=null;
                if(ThisType==".js"){
                    fileObj=document.createElement('script');
                    fileObj.src = url;
                }else if(ThisType==".css"){
                    fileObj=document.createElement('link');
                    fileObj.href = url;
                    fileObj.type = "text/css";
                    fileObj.rel="stylesheet";
                }
                success = success || function(){};
                fileObj.onload = fileObj.onreadystatechange = function() {
                    if (!this.readyState || 'loaded' === this.readyState || 'complete' === this.readyState) {
                        success();
                        classcodes.push(url)
                    }
                }
                document.getElementsByTagName('head')[0].appendChild(fileObj);
            }else{
                success();
            }
        }
        function GetFileType(url){
            if(url!=null && url.length>0){
                return url.substr(url.lastIndexOf(".")).toLowerCase();
            }
            return "";
        }
        function FileIsExt(FileArray,_url){
            if(FileArray!=null && FileArray.length>0){
                var len =FileArray.length;
                for (var i = 0; i < len; i++){
                    if (FileArray[i] ==_url){
                       return true;
                    }
                }
            }
            return false;
        }
    }
}
function init_aplayer(obj,url){ //aplayer
	Import.LoadFileList(["static/javascript/APlayer/APlayer.min.js","static/javascript/APlayer/APlayer.min.css"],function(){
		const ap=new APlayer({
			container: obj,
			theme: "#33ccff",
			preload: "metadata",
			loop: "none",
			mutex: true,
			audio:[{
				name: getFileName(url),
				url: url,
				artist: ' ',
			}]
		});
	});
}
function init_dplayer(obj,url){ //dplayer
	Import.LoadFileList(["static/javascript/DPlayer/DPlayer.min.js","static/javascript/DPlayer/DPlayer.min.css"],function(){
		const dp = new DPlayer({
			container: obj,
			theme: "#33ccff",
			screenshot: true,
			preload: "metadata",
			mutex: true,
			video: {
				url: url,
				type: "auto",
			},
		});
	});
}
Array.prototype.remove=function(val){ 
    var index=this.indexOf(val);
    if(index>-1){
        this.splice(index, 1);
    }
}
Date.prototype.format=function(fmt){
	var o={
		"M+":this.getMonth()+1,                 //月份
		"d+":this.getDate(),                    //日
		"h+":this.getHours(),                   //小时
		"m+":this.getMinutes(),                 //分
		"s+":this.getSeconds(),                 //秒
		"q+":Math.floor((this.getMonth()+3)/3), //季度
		"S":this.getMilliseconds()             //毫秒
	};
	if(/(y+)/.test(fmt)){
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
	}
        
	for(var k in o){
		if(new RegExp("("+ k +")").test(fmt)){
		fmt = fmt.replace(
			RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));  
		}       
	}
	return fmt;
}
function replaceAll_(v1,v2,v3){
	var regexp=new RegExp(v1,"g");
	return v3.replace(regexp,v2);
}
function getFileName(path){
    var pos1=path.lastIndexOf('/');
    var pos2=path.lastIndexOf('\\');
    var pos=Math.max(pos1, pos2);
    if(pos<0){
        return path;
    }else{
        return path.substring(pos+1);
	}
}