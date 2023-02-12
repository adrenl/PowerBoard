<?php if(!defined('IN_POWERBOARD')){die();}?><?php include template('common/header','default');?>
<?php if(is_array($out)){foreach($out as $key=>$value){ ?>
<div data-role="collapsible" data-collapsed="false">
    <h4><?php echo $value['name'];?></h4>
    <?php if(is_array($value['childern'])){foreach($value['childern'] as $key1=>$value1){ ?>
        <p>
            <a href="forums.php?mod=display&fid=<?php echo $key1;?>"><?php echo $value1['name'];?></a><br><?php echo $value1['description'];?>
        </p>
    <?php } }?>
</div>
<?php } }?>
<?php include template('common/footer','default');?>