<?php if(!defined('IN_POWERBOARD')){die();}?><?php include template('common/header','default');?>
<img src="static/imgs/common/rss.gif">
<div class="div_father">
    <div class="div_title"><?php echo $forumname;?> 帖子列表</div>
        <div class="div_content">
            <table style="width:100%;">
                <tr style="border-bottom:1px solid #b2b2b2">
                    <td style="width:64%;">标题</td>
                    <td style="width:8%;">发布者</td>
                    <td style="width:10%;">发布时间</td>
                    <td style="width:10%;">最后回复时间</td>
                    <td style="width:8%;">最后回复者</td>
                </tr>
                <?php if(is_array($topics)){foreach($topics as $value){ ?>
                 <tr class="div_with_color_list">
                    <td><a href="forums.php?mod=view&tid=<?php echo $value['tid'];?>"><?php echo $value['title'];?></a></td>
                    <td><?php echo $value['author'];?></td>
                    <td><?php echo _date(0,$value['pubtime'])?></td>
                    <td><?php echo _date(0,$value['lastposttime'])?></td>
                    <td><?php echo $value['lastposter'];?></td>
                </tr>
                <?php } }?>
            </table>
        </div>
</div>
<?php include template('common/footer','default');?>