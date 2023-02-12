<?php if(!defined('IN_POWERBOARD')){die();}?><?php $puttoheader='<link rel="stylesheet" type="text/css" href="data/cache/template/css_default_view.css">' ?>
<?php include template('common/header','default');?>
<div class="div_father">
    <div class="div_title"><?php echo $topic['title'];?> <span class="floor"><?php echo $topic['floor'];?>#</span></div>
        <div class="div_content nopadding">
            <div class="pcolumn_left">
                <?=avatar($topic['author_uid'])?><br>
                <?php echo $topic['author'];?>
            </div><div class="pcolumn_right">
                <?php echo $topic['content'];?>
            </div>
        </div>
</div>
<?php if(isset($post)){ ?>
    <?php if(is_array($post)){foreach($post as $value){ ?>
    <div class="div_father">
        <div class="div_title"><?php echo $value['title']?$value['title']:'&nbsp;'?> <span class="floor"><?php echo $value['floor'];?>#</span></div>
            <div class="div_content nopadding">
                <div class="pcolumn_left">
                    <?=avatar($value['author_uid'])?><br>
                    <?php echo $value['author'];?>
                </div><div class="pcolumn_right">
                    <?php echo $value['content'];?>
                </div>
            </div>
    </div>
    <?php } }?>
<?php } ?>
<?php include template('common/footer','default');?>