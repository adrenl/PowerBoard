<?php if(!defined('IN_POWERBOARD')){die();}?><?php $powerboard->page_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="static/javascript/jquery_mobile/jquery.mobile-1.4.5.min.css">
        <script src="static/javascript/jquery_mobile/jquery.min.js"></script>
        <script src="static/javascript/jquery_mobile/jquery.mobile-1.4.5.min.js"></script>
        <script src="static/javascript/common.js"></script>
        <link rel="stylesheet" href="data/cache/template/css_default_m_common.css">
        <script>
            jQuery.mobile.ajaxEnabled=false;
            jQuery.noConflict(true);
        </script>
        <title><?php if($navtitle){ ?><?php echo $navtitle;?><?php } ?> - <?php echo $_G['config']['basic_bbname'];?></title>
        <?php echo isset($puttoheader)?$puttoheader:''?>
    </head>
    <body>
		<div data-role="page">
			<div data-role="header">
                <a href="./" class="ui-btn ui-corner-all ui-shadow ui-icon-home ui-btn-icon-left">首页</a>
                <p style="text-align:center;v;"><?php echo $navtitle;?></p>
			</div>
 			<div data-role="navbar">
				<ul>
					<li><a href="user.php?mod=login">登录</a></li>
					<li><a href="user.php?mod=register">注册</a></li>
				</ul>
			</div>
            <div data-role="main" class="ui-content">