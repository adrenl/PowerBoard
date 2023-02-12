<?php if(!defined('IN_POWERBOARD')){die();}?><?php $puttoheader='<script src="static/javascript/login.js"></script>' ?>
<?php include template('common/header','default');?>
<script>
</script>
<div class="div_father">
    <div class="div_title">登录</div>
    <div class="div_content reginfo">
        <form method="post" onsubmit="on_login();return false;">
            <table align="center">
                <tr>
                    <td>用户名</td>
                    <td><input type="text" size="25" name="username" id="username" required></td>
                </tr>
                <tr>
                    <td>密码</td>
                    <td><input type="password" size="25" name="password" id="password" required></td>
                </tr>
                <tr>
                    <td>验证码</td>
                    <td>
<!--sub:common/seccheck-->
<?php $tpl_sec_url=$_G['config']['basic_bburl'].'misc.php?mod=secode'; ?>
<?php $tpl_sec_id='secode_'.rand(0,999); ?>
<?php if($_G['config']['secode_type']==0 || $_G['config']['secode_type']==2){ ?>
	<input type="text" name="secode" id="secode" required>&nbsp;<img src="<?php echo $tpl_sec_url;?>" id="<?php echo $tpl_sec_id;?>">&nbsp;<button onclick="$('<?php echo $tpl_sec_id;?>').src='<?php echo $tpl_sec_url;?>&'+Math.random()">刷新验证码</button>

<?php }else{ ?>
	<input type="text" name="secode" id="secode" required>&nbsp;<audio type="audio/mpeg" src="<?php echo $tpl_sec_url;?>" id="<?php echo $tpl_sec_id;?>"></audio><button onclick="$('<?php echo $tpl_sec_id;?>').src='<?php echo $tpl_sec_url;?>&'+Math.random()">刷新验证码</button> <button onclick="$('<?php echo $tpl_sec_id;?>').play();">播放</button>
<?php } ?>
<!--endsub:common/seccheck-->
</td>
                </tr>
                
            </table>
            <input type="submit"class="submitbtn" value="登录">
        </form>
    </div>
</div>
<?php include template('common/footer','default');?>