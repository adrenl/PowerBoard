<?php if(!defined('IN_POWERBOARD')){die();}?><?php $powerboard->page_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if($navtitle){ ?><?php echo $navtitle;?><?php } ?> - <?php echo $_G['config']['basic_bbname'];?></title>
        <link rel="stylesheet" type="text/css" href="data/cache/template/css_default_common.css" />
        <script src="static/javascript/common.js"></script>
        <?php echo isset($puttoheader)?$puttoheader:''?>
    </head>
    <body>
        <div id="nav">你好，请 <a href="user.php?mod=login">登录</a> | <a href="user.php?mod=register">注册</a></div>
        <div class="header">
           <a href="<?php echo $_G['config']['basic_bburl'];?>"><img src="<?php echo $_G['config']['basic_bblogo'];?>"></a><br>
           当前页面 : <?php echo $navtitle;?>
        </div>
        <hr>