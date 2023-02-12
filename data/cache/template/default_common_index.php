<?php if(!defined('IN_POWERBOARD')){die();}?><?php include template('common/header','default');?>
<?php if(is_array($out)){foreach($out as $key=>$value){ ?>
<div class="div_father">
    <div class="div_title"><?php echo $value['name'];?></div>
    <?php if(is_array($value['childern'])){foreach($value['childern'] as $key1=>$value1){ ?>
        <div class="div_content div_with_color_list">
            <a href="forums.php?mod=display&fid=<?php echo $key1;?>"><?php echo $value1['name'];?></a><br><?php echo $value1['description'];?>
        </div>
    <?php } }?>
</div>
<?php } }?>
<?php include template('common/footer','default');?>